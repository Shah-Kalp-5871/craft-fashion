<?php

namespace App\Services\Customer;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\CustomerAddress;
use Exception;

class ShiprocketService
{
    protected $baseUrl;
    protected $email;
    protected $password;
    protected $tokenCacheKey = 'shiprocket_auth_token';
    protected $client;

    public function __construct()
    {
        $this->baseUrl = config('services.shiprocket.base_url');
        $this->email = config('services.shiprocket.email');
        $this->password = config('services.shiprocket.password');

        if ($this->email && $this->password) {
            // Bypass SSL verification for now to fix production error
            $this->client = Http::withoutVerifying();
        }
    }

    protected function ensureConfigured()
    {
        if (!$this->email || !$this->password) {
            throw new Exception('Shiprocket credentials not configured. Please add SHIPROCKET_EMAIL and SHIPROCKET_PASSWORD to your .env file.');
        }
    }

    /**
     * Get authentication token
     */
    private function getToken(): string
    {
        $this->ensureConfigured();
        // Check cache first
        if (Cache::has($this->tokenCacheKey)) {
            $token = Cache::get($this->tokenCacheKey);
            Log::info('Using cached Shiprocket token');
            return $token;
        }

        try {
            Log::info('Requesting new Shiprocket token');

            $response = $this->client->post($this->baseUrl . 'auth/login', [
                'email' => $this->email,
                'password' => $this->password
            ]);

            if (!$response->successful()) {
                Log::error('Shiprocket authentication failed', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                throw new Exception('Failed to authenticate with shipping service');
            }

            $data = $response->json();

            if (empty($data['token'])) {
                throw new Exception('No token received from shipping service');
            }

            $token = $data['token'];

            // Cache token for 23 hours (tokens typically expire in 24 hours)
            Cache::put($this->tokenCacheKey, $token, now()->addHours(23));

            Log::info('Shiprocket token obtained and cached');

            return $token;

        } catch (Exception $e) {
            Log::error('Shiprocket token error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new Exception('Shipping service unavailable. Please try again later.');
        }
    }

    /**
     * Check serviceability for a pincode
     */
    public function checkServiceability(string $pincode, float $weight, array $dimensions = [])
    {
        try {
            $this->ensureConfigured();
            $pickupPostcode = config('services.shiprocket.pickup_pincode');
            
            Log::info('Checking Shiprocket serviceability', [
                'pincode' => $pincode,
                'weight' => $weight,
                'pickup_postcode' => $pickupPostcode,
                'base_url' => $this->baseUrl
            ]);

            if (empty($pickupPostcode)) {
                Log::error('Shiprocket pickup_pincode is not configured');
                return [
                    'success' => false,
                    'message' => 'Shipping configuration error: Missing pickup pincode'
                ];
            }

            $params = [
                'pickup_postcode' => $pickupPostcode,
                'delivery_postcode' => $pincode,
                'weight' => $weight,
                'length' => $dimensions['length'] ?? 10,
                'breadth' => $dimensions['width'] ?? 10,
                'height' => $dimensions['height'] ?? 10,
                'cod' => 1 // Check for COD availability too
            ];

            // Manual query string construction to ensure control over params if needed
            // But Http client should handle it. Added logging of params.
            
            $response = $this->client->withToken($this->getToken())
                ->acceptJson()
                ->get($this->baseUrl . 'courier/serviceability', $params);

            if (!$response->successful()) {
                // Handle Token Expiry
                if ($response->status() === 401 || isset($response->json()['message']) && $response->json()['message'] === 'token_expired') {
                     Log::info('Shiprocket token expired (401). Retrying with fresh token.');
                     Cache::forget($this->tokenCacheKey);
                     
                     // Retry once with new token
                     $response = $this->client->withToken($this->getToken())
                        ->acceptJson()
                        ->get($this->baseUrl . 'courier/serviceability', $params);
                }

                if (!$response->successful()) {
                    Log::error('Shiprocket serviceability check failed', [
                        'status' => $response->status(),
                        'response' => $response->json(),
                        'sent_params' => $params
                    ]);
                    
                    $errorMsg = 'Shipping service unavailable.';
                    if ($response->status() === 403) {
                         $errorMsg = 'Shipping service unauthorized (403).';
                    }
                    
                    // improved error debugging
                    $apiError = $response->json()['message'] ?? 'Unknown error';
                    $errorMsg .= ' Reason: ' . $apiError;

                    return [
                        'success' => false,
                        'message' => $errorMsg,
                        'debug_error' => $apiError
                    ];
                }
            }

            $data = $response->json();

            Log::info('Shiprocket API Response', ['data' => $data]);

            // Process and format available couriers
            $availableCouriers = $this->processAvailableCouriers($data);

            if (empty($availableCouriers)) {
                 return [
                    'success' => false,
                    'message' => 'No delivery partners available for this pin code.'
                ];
            }

            Log::info('Shiprocket serviceability check successful', [
                'pincode' => $pincode,
                'available_couriers' => count($availableCouriers)
            ]);

            return [
                'success' => true,
                'available_couriers' => $availableCouriers,
                'estimated_delivery' => $data['estimated_delivery_days'] ?? null,
                'raw_data' => $data
            ];

        } catch (Exception $e) {
            Log::error('Shiprocket serviceability error', [
                'pincode' => $pincode,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Check if it's a configuration error (development)
            if (str_contains($e->getMessage(), 'credentials not configured')) {
                return [
                    'success' => false,
                    'message' => 'Shipping configuration is incomplete. Please contact support.'
                ];
            }

            return [
                'success' => false,
                'message' => 'Unable to verify delivery availability at this moment. Please try again or proceed.'
            ];
        }
    }

    /**
     * Process available couriers into top 3 options
     */
    private function processAvailableCouriers(array $data): array
    {
        $couriers = $data['data']['available_courier_companies'] ?? [];

        // Sort by rate (lowest first) and then by estimated days
        usort($couriers, function ($a, $b) {
            if ($a['rate'] == $b['rate']) {
                return $a['estimated_delivery_days'] <=> $b['estimated_delivery_days'];
            }
            return $a['rate'] <=> $b['rate'];
        });

        // Take top 3 couriers
        $topCouriers = array_slice($couriers, 0, 3);

        // Format for frontend
        $formattedCouriers = [];
        foreach ($topCouriers as $courier) {
            $formattedCouriers[] = [
                'courier_id' => $courier['courier_company_id'],
                'name' => $courier['courier_name'],
                'rate' => $courier['rate'],
                'estimated_days' => $courier['estimated_delivery_days'],
                'service_type' => $courier['service_type'] ?? 'Surface',
            ];
        }

        return $formattedCouriers;
    }

    /**
     * Create shipment order
     */
    public function createOrder(Order $order)
    {
        try {
            $this->ensureConfigured();
            Log::info('Creating Shiprocket order', [
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);

            $shippingAddress = $order->shipping_address;

            // Check serviceability first
            $weight = $this->calculateOrderWeight($order);
            $serviceability = $this->checkServiceability($shippingAddress['pincode'], $weight);

            if (!$serviceability || empty($serviceability['available_couriers'])) {
                throw new Exception('Shipping not available to this location');
            }

            // Prepare order data for Shiprocket
            $orderData = [
                'order_id' => $order->order_number,
                'order_date' => $order->created_at->format('Y-m-d'),
                'pickup_location' => config('services.shiprocket.pickup_location', 'Primary'),
                'channel_id' => '',
                'comment' => '',
                'reseller_name' => '',
                'company_name' => '',
                'billing_customer_name' => $shippingAddress['name'],
                'billing_last_name' => '',
                'billing_address' => $shippingAddress['address'],
                'billing_address_2' => $shippingAddress['address2'] ?? '',
                'billing_city' => $shippingAddress['city'],
                'billing_pincode' => $shippingAddress['pincode'],
                'billing_state' => $shippingAddress['state'],
                'billing_country' => $shippingAddress['country'],
                'billing_email' => $shippingAddress['email'],
                'billing_phone' => $shippingAddress['phone'],
                'shipping_is_billing' => 1,
                'order_items' => $this->prepareOrderItems($order),
                'payment_method' => $order->payment_method === 'cod' ? 'COD' : 'Prepaid',
                'shipping_charges' => $order->shipping_total,
                'giftwrap_charges' => 0,
                'transaction_charges' => 0,
                'total_discount' => $order->discount_total,
                'sub_total' => $order->subtotal + $order->tax_total,
            ];
            
            // Add dimensions and weight
            $dimensions = $this->calculateOrderDimensions($order);
            $orderData = array_merge($orderData, $dimensions);
            $orderData['weight'] = $weight;

            // Add COD amount if applicable
            if ($order->payment_method === 'cod') {
                $orderData['cod_amount'] = $order->grand_total;
            }

            Log::info('Sending order to Shiprocket', [
                'order_data' => $orderData
            ]);

            $response = $this->client->withToken($this->getToken())
                ->post($this->baseUrl . 'orders/create/adhoc', $orderData);

            if (!$response->successful()) {
                Log::error('Shiprocket order creation failed', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                throw new Exception('Failed to create shipping order');
            }

            $shiprocketData = $response->json();
            
            if (!isset($shiprocketData['order_id'])) {
                Log::error('Shiprocket response missing order_id', ['response' => $shiprocketData]);
                throw new Exception('Invalid response from shipping provider');
            }

            // Create shipment record
            $shipment = Shipment::create([
                'order_id' => $order->id,
                'shiprocket_order_id' => $shiprocketData['order_id'],
                'shipment_id' => $shiprocketData['shipment_id'] ?? null,
                'status' => 'created',
                'courier_id' => $shiprocketData['courier_company_id'] ?? null,
                'courier_name' => $shiprocketData['courier_name'] ?? null,
                'tracking_number' => $shiprocketData['awb_code'] ?? null,
                'shipping_label_url' => $shiprocketData['label_url'] ?? null,
                'manifest_url' => $shiprocketData['manifest_url'] ?? null,
                'shiprocket_response' => $shiprocketData,
            ]);

            Log::info('Shiprocket order created successfully', [
                'order_id' => $order->id,
                'shipment_id' => $shipment->id,
                'tracking_number' => $shiprocketData['awb_code']
            ]);

            return [
                'success' => true,
                'shipment' => $shipment,
                'tracking_number' => $shiprocketData['awb_code'],
                'label_url' => $shiprocketData['label_url'] ?? null
            ];

        } catch (Exception $e) {
            Log::error('Shiprocket create order error', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Don't throw exception, just log it
            // Order can still be created without Shiprocket
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function calculateOrderDimensions(Order $order): array
    {
        $maxLength = 10;
        $maxWidth = 10;
        $maxHeight = 10;

        foreach ($order->items as $item) {
             // We need to access ProductVariant. 
             // OrderItem has relation ->variant()
             $variant = $item->variant;
             
             if ($variant) {
                 $l = $variant->length ?? ($item->product->length ?? 10);
                 $w = $variant->width ?? ($item->product->width ?? 10);
                 $h = $variant->height ?? ($item->product->height ?? 10);
                 
                 if ($l > $maxLength) $maxLength = $l;
                 if ($w > $maxWidth) $maxWidth = $w;
                 if ($h > $maxHeight) $maxHeight = $h;
             }
        }

        return [
            'length' => $maxLength,
            'breadth' => $maxWidth,
            'height' => $maxHeight
        ];
    }

    /**
     * Calculate total weight of order
     */
    private function calculateOrderWeight(Order $order): float
    {
        $weight = 0;

        foreach ($order->items as $item) {
            $variant = $item->variant;
            // Use 0.1 as default now as per migration
            $productWeight = $variant->weight ?? ($item->product->weight ?? 0.1);
            $weight += $productWeight * $item->quantity;
        }

        return max($weight, 0.1); 
    }

    /**
     * Prepare order items for Shiprocket
     */
    private function prepareOrderItems(Order $order): array
    {
        $items = [];

        foreach ($order->items as $index => $item) {
            $items[] = [
                'name' => $item->product_name,
                'sku' => $item->sku,
                'units' => $item->quantity,
                'selling_price' => $item->unit_price,
                'discount' => $item->discount_amount,
                'tax' => 0, // Tax already included in subtotal
                'hsn' => '', // Add HSN if available
            ];
        }

        return $items;
    }

    /**
     * Generate shipping label
     */
    public function generateLabel($shiprocketOrderId)
    {
        try {
            $this->ensureConfigured();
            $response = $this->client->withToken($this->getToken())
                ->get($this->baseUrl . 'courier/generate/label', [
                    'order_ids' => [$shiprocketOrderId]
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'label_url' => $response->json('label_url')
                ];
            }

            Log::error('Shiprocket label generation failed', $response->json());
            return null;

        } catch (Exception $e) {
            Log::error('Shiprocket label generation error', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Track shipment
     */
    public function trackShipment($shipmentId)
    {
        try {
            $this->ensureConfigured();
            $response = $this->client->withToken($this->getToken())
                ->get($this->baseUrl . 'courier/track/shipment/' . $shipmentId);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'tracking_data' => $response->json()
                ];
            }

            Log::error('Shiprocket tracking failed', $response->json());
            return null;

        } catch (Exception $e) {
            Log::error('Shiprocket tracking error', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
    /**
     * Get all pickup locations
     */
    public function getPickupLocations()
    {
        try {
            $this->ensureConfigured();
            $response = $this->client->withToken($this->getToken())
                ->get($this->baseUrl . 'settings/company/pickup');

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            Log::error('Shiprocket fetch pickup locations failed', $response->json());
            return [
                'success' => false,
                'message' => 'Failed to fetch pickup locations'
            ];

        } catch (Exception $e) {
            Log::error('Shiprocket fetch pickup locations error', [
                'error' => $e->getMessage()
            ]);
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
