<?php

namespace App\Services\Customer;

use Razorpay\Api\Api;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentAttempt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RazorpayService
{
    protected $razorpay;
    protected $keyId;
    protected $keySecret;

    public function __construct()
    {
        $this->keyId = config('services.razorpay.key_id');
        $this->keySecret = config('services.razorpay.key_secret');

        if ($this->keyId && $this->keySecret) {
            try {
                $this->razorpay = new Api($this->keyId, $this->keySecret);
            } catch (\Exception $e) {
                Log::error('Razorpay initialization failed: ' . $e->getMessage());
            }
        }
    }

    protected function ensureConfigured()
    {
        if (!$this->razorpay) {
            throw new \Exception('Razorpay credentials not configured. Please add RAZORPAY_KEY_ID and RAZORPAY_KEY_SECRET to your .env file.');
        }
    }

    /**
     * Create Razorpay order
     */
    public function createOrder($order, $amount)
    {
        $this->ensureConfigured();
        DB::beginTransaction();

        try {
            Log::info('Creating Razorpay order', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'amount' => $amount
            ]);

            // Create Razorpay order
            $razorpayOrder = $this->razorpay->order->create([
                'amount' => $amount * 100, // Convert to paise
                'currency' => 'INR',
                'receipt' => $order->order_number,
                'payment_capture' => 1, // Auto capture
                'notes' => [
                    'order_id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'order_number' => $order->order_number
                ]
            ]);

            // Log payment attempt
            PaymentAttempt::create([
                'order_id' => $order->id,
                'gateway' => 'razorpay',
                'gateway_order_id' => $razorpayOrder->id,
                'amount' => $amount,
                'currency' => 'INR',
                'status' => 'initiated',
                'request_data' => $razorpayOrder->toArray(),
            ]);

            DB::commit();

            Log::info('Razorpay order created successfully', [
                'razorpay_order_id' => $razorpayOrder->id,
                'order_id' => $order->id
            ]);

            return [
                'success' => true,
                'order_id' => $razorpayOrder->id,
                'amount' => $razorpayOrder->amount,
                'currency' => $razorpayOrder->currency,
                'key_id' => $this->keyId
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Razorpay Order Creation Failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to create payment order. Please try again.'
            ];
        }
    }

    /**
     * Create Razorpay order WITHOUT DB order
     * (Used for online payment before checkout confirmation)
     */
    public function createOrderByAmount(int $amountInPaise): array
    {
        try {
            $this->ensureConfigured();
            Log::info('Creating Razorpay order by amount', [
                'amount' => $amountInPaise
            ]);

            $order = $this->razorpay->order->create([
                'amount' => $amountInPaise, // already in paise
                'currency' => 'INR',
                'payment_capture' => 1,
            ]);

            return [
                'success' => true,
                'order_id' => $order['id'],
                'key_id' => $this->keyId,
                'amount' => $order['amount'],
                'currency' => $order['currency'],
            ];

        } catch (\Exception $e) {
            Log::error('Razorpay order creation failed', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Unable to initiate payment. Please try again.',
            ];
        }
    }


    /**
     * Verify payment signature
     */
    public function verifyPayment($paymentId, $orderId, $signature)
    {
        try {
            $this->ensureConfigured();
            Log::info('Verifying Razorpay payment', [
                'payment_id' => $paymentId,
                'order_id' => $orderId
            ]);

            $attributes = [
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature
            ];

            $this->razorpay->utility->verifyPaymentSignature($attributes);

            // Get payment details from Razorpay
            $payment = $this->razorpay->payment->fetch($paymentId);

            Log::info('Razorpay payment verified successfully', [
                'payment_id' => $paymentId,
                'status' => $payment->status
            ]);

            return [
                'success' => true,
                'payment' => $payment->toArray(),
                'message' => 'Payment verified successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Razorpay Payment Verification Failed', [
                'payment_id' => $paymentId,
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Payment verification failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Process payment after verification
     */
    public function processPayment(Order $order, array $paymentData)
    {
        DB::beginTransaction();

        try {
            // Verify payment first
            $verification = $this->verifyPayment(
                $paymentData['razorpay_payment_id'],
                $paymentData['razorpay_order_id'],
                $paymentData['razorpay_signature']
            );

            if (!$verification['success']) {
                throw new \Exception($verification['message']);
            }

            // Get payment details
            $razorpayPayment = $this->razorpay->payment->fetch($paymentData['razorpay_payment_id']);

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'razorpay',
                'amount' => $order->grand_total,
                'currency' => 'INR',
                'status' => $razorpayPayment->status,
                'transaction_id' => $razorpayPayment->id,
                'gateway_order_id' => $paymentData['razorpay_order_id'],
                'payment_details' => [
                    'method' => $razorpayPayment->method ?? 'card',
                    'card_type' => $razorpayPayment->card->type ?? null,
                    'card_network' => $razorpayPayment->card->network ?? null,
                    'bank' => $razorpayPayment->bank ?? null,
                    'wallet' => $razorpayPayment->wallet ?? null,
                    'vpa' => $razorpayPayment->vpa ?? null,
                ],
                'gateway_response' => $razorpayPayment->toArray(),
            ]);

            // Update payment attempt
            PaymentAttempt::where('gateway_order_id', $paymentData['razorpay_order_id'])
                ->update([
                    'status' => $razorpayPayment->status,
                    'gateway_payment_id' => $razorpayPayment->id,
                    'response_data' => $razorpayPayment->toArray(),
                    'updated_at' => now()
                ]);

            // Update order payment status
            $order->update([
                'payment_status' => in_array($razorpayPayment->status, ['captured', 'authorized']) ? 'paid' : 'failed',
                'status' => in_array($razorpayPayment->status, ['captured', 'authorized']) ? 'confirmed' : 'failed'
            ]);

            DB::commit();

            Log::info('Razorpay payment processed successfully', [
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'razorpay_payment_id' => $razorpayPayment->id
            ]);

            return [
                'success' => true,
                'payment' => $payment,
                'order' => $order
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Razorpay Payment Processing Failed', [
                'order_id' => $order->id,
                'payment_data' => $paymentData,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Refund payment
     */
    public function refundPayment($paymentId, $amount, $notes = [])
    {
        try {
            $this->ensureConfigured();
            Log::info('Initiating Razorpay refund', [
                'payment_id' => $paymentId,
                'amount' => $amount
            ]);

            $refund = $this->razorpay->payment->fetch($paymentId)->refund([
                'amount' => $amount * 100,
                'speed' => 'normal',
                'notes' => array_merge($notes, [
                    'refund_initiated_at' => now()->toISOString()
                ])
            ]);

            Log::info('Razorpay refund successful', [
                'payment_id' => $paymentId,
                'refund_id' => $refund->id,
                'amount' => $amount
            ]);

            return [
                'success' => true,
                'refund' => $refund->toArray(),
                'message' => 'Refund initiated successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Razorpay Refund Failed', [
                'payment_id' => $paymentId,
                'amount' => $amount,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Refund failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus($paymentId)
    {
        try {
            $this->ensureConfigured();
            $payment = $this->razorpay->payment->fetch($paymentId);

            return [
                'success' => true,
                'status' => $payment->status,
                'payment' => $payment->toArray()
            ];
        } catch (\Exception $e) {
            Log::error('Razorpay Status Check Failed', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function fetchOrder(string $razorpayOrderId)
    {
        $this->ensureConfigured();
        return $this->razorpay->order->fetch($razorpayOrderId);
    }

}
