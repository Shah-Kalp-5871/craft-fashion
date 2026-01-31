<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperProductVariant
 */
class ProductVariant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'sku',
        'combination_hash',
        'price',
        'compare_price',
        'cost_price',
        'stock_quantity',
        'reserved_quantity',
        'stock_status',
        'is_default',
        'status',
        'weight',
        'length',
        'width',
        'height',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'is_default' => 'boolean',
        'status' => 'boolean',
        'weight' => 'decimal:3',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'variant_attributes',
            'variant_id',
            'attribute_value_id'
        )
            ->withPivot('attribute_id')
            ->withTimestamps();
    }


    public function variantAttributes(): HasMany
    {
        return $this->hasMany(VariantAttribute::class, 'variant_id');
    }


    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'variant_images', 'variant_id', 'media_id')
            ->withPivot('is_primary', 'sort_order')
            ->withTimestamps();
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(VariantImage::class, 'variant_id')
            ->where('is_primary', true);
    }


    public function priceHistories(): HasMany
    {
        return $this->hasMany(PriceHistory::class);
    }

    public function tierPrices(): HasMany
    {
        return $this->hasMany(TierPrice::class);
    }

    public function stockHistories(): HasMany
    {
        return $this->hasMany(StockHistory::class);
    }

    public function warehouseStocks(): HasMany
    {
        return $this->hasMany(WarehouseStock::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function offers(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class, 'offer_variants');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function inventoryTransferItems(): HasMany
    {
        return $this->hasMany(InventoryTransferItem::class);
    }

    public function getStockAvailableAttribute()
    {
        return $this->stock_quantity - $this->reserved_quantity;
    }

    public function getDisplayImageAttribute()
    {
        $filePath = $this->primaryImage?->media?->file_path
            ?? $this->images->first()?->file_path
            ?? null;
        
        return $filePath ? asset('storage/' . $filePath) : null;
    }

}
