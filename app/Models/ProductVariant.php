<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'attributes',
        'price_modifier',
        'status',
    ];

    protected $casts = [
        'attributes' => 'array',
        'price_modifier' => 'decimal:4',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the product this variant belongs to
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get warehouses where this variant is stocked (through inventory pivot)
     */
    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'inventory', 'variant_id', 'warehouse_id')
            ->using(Inventory::class)
            ->withPivot(['quantity', 'reserved_quantity', 'aisle', 'rack', 'bin', 'min_stock', 'max_stock', 'last_count_at'])
            ->withTimestamps();
    }

    /**
     * Get inventory records for this variant
     */
    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class, 'variant_id');
    }

    /**
     * Get stock movements for this variant
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'variant_id');
    }

    /**
     * Get order items for this variant
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }

    /**
     * Get the final price (base_price + price_modifier)
     */
    public function getFinalPriceAttribute(): string
    {
        return bcadd($this->product->base_price, $this->price_modifier, 4);
    }
}
