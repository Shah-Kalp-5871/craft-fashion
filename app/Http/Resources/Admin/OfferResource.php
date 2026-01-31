<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'status' => $this->status,
            'status_text' => $this->status ? 'Active' : 'Inactive',
            'offer_type' => $this->offer_type,
            'offer_type_text' => $this->getOfferTypeText(),
            'discount_value' => $this->discount_value,
            'buy_qty' => $this->buy_qty,
            'get_qty' => $this->get_qty,
            'min_cart_amount' => $this->min_cart_amount,
            'max_cart_amount' => $this->max_cart_amount,
            'max_discount' => $this->max_discount,
            'max_uses' => $this->max_uses,
            'uses_per_customer' => $this->uses_per_customer,
            'used_count' => $this->used_count,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'is_auto_apply' => $this->is_auto_apply,
            'is_stackable' => $this->is_stackable,
            'is_exclusive' => $this->is_exclusive,
            'customer_segment_id' => $this->customer_segment_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at_formatted' => $this->created_at ? $this->created_at->format('M d, Y H:i') : null,
            'starts_at_formatted' => $this->starts_at ? $this->starts_at->format('M d, Y H:i') : null,
            'ends_at_formatted' => $this->ends_at ? $this->ends_at->format('M d, Y H:i') : null,
            'is_active' => $this->isActive(),
            'days_remaining' => $this->getDaysRemaining(),
            'categories' => $this->whenLoaded('categories', function() {
                return $this->categories->map(function($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name
                    ];
                });
            }),
            'variants' => $this->whenLoaded('variants', function() {
                return $this->variants->map(function($variant) {
                    return [
                        'id' => $variant->id,
                        'product_name' => $variant->product ? $variant->product->name : 'N/A',
                        'variant_name' => $variant->name,
                        'sku' => $variant->sku
                    ];
                });
            }),
            'rewards' => $this->whenLoaded('rewards', function() {
                return $this->rewards->map(function($reward) {
                    return [
                        'id' => $reward->id,
                        'reward_product_id' => $reward->reward_product_id,
                        'reward_product_name' => $reward->product ? $reward->product->name : 'N/A',
                        'reward_variant_id' => $reward->reward_variant_id,
                        'reward_variant_name' => $reward->variant ? $reward->variant->name : null,
                        'reward_qty' => $reward->reward_qty,
                        'same_as_buy_product' => $reward->same_as_buy_product
                    ];
                });
            }),
            'usage_count' => $this->usages_count ?? 0,
            'customer_segment' => $this->whenLoaded('customerSegment', function() {
                return [
                    'id' => $this->customerSegment->id,
                    'name' => $this->customerSegment->name
                ];
            }),
        ];
    }

    /**
     * Get offer type text.
     */
    private function getOfferTypeText()
    {
        $types = [
            'percentage' => 'Percentage Discount',
            'fixed' => 'Fixed Amount',
            'bogo' => 'Buy One Get One',
            'buy_x_get_y' => 'Buy X Get Y',
            'free_shipping' => 'Free Shipping',
            'tiered' => 'Tiered Discount'
        ];

        return $types[$this->offer_type] ?? $this->offer_type;
    }

    /**
     * Check if offer is currently active.
     */
    private function isActive()
    {
        if (!$this->status) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $this->starts_at > $now) {
            return false;
        }

        if ($this->ends_at && $this->ends_at < $now) {
            return false;
        }

        return true;
    }

    /**
     * Get days remaining until offer ends.
     */
    private function getDaysRemaining()
    {
        if (!$this->ends_at || $this->ends_at < now()) {
            return 0;
        }

        return now()->diffInDays($this->ends_at, false);
    }
}
