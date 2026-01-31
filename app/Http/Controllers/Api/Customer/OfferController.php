<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\JsonResponse;

class OfferController extends Controller
{
    public function getActiveOffers(): JsonResponse
    {
        try {
            $offers = Offer::active()
                ->where('status', true)
                ->where(function($q) {
                    $q->where(function($query) {
                        $query->whereNotNull('discount_value')
                              ->where('discount_value', '>', 0);
                    })
                    ->orWhereIn('offer_type', ['bogo', 'buy_x_get_y', 'free_shipping']);
                })
                ->select('id', 'name', 'code', 'offer_type', 'discount_value', 'buy_qty', 'get_qty', 'min_cart_amount', 'ends_at')
                ->orderByRaw('CASE WHEN discount_value IS NULL THEN 1 ELSE 0 END')
                ->orderBy('discount_value', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $offers,
                'message' => 'Active offers retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch offers',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
