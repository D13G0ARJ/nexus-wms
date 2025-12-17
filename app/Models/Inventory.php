<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Inventory extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = true;

    protected $fillable = [
        'variant_id',
        'warehouse_id',
        'quantity',
        'reserved_quantity',
        'aisle',
        'rack',
        'bin',
        'min_stock',
        'max_stock',
        'last_count_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'reserved_quantity' => 'integer',
        'min_stock' => 'integer',
        'max_stock' => 'integer',
        'last_count_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the product variant
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /**
     * Get the warehouse
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get available quantity (quantity - reserved)
     */
    protected function availableQuantity(): Attribute
    {
        return Attribute::make(
            get: fn () => max(0, $this->quantity - $this->reserved_quantity),
        );
    }

    /**
     * Check if stock is below minimum
     */
    public function isBelowMinStock(): bool
    {
        return $this->quantity < $this->min_stock;
    }

    /**
     * Check if stock is above maximum
     */
    public function isAboveMaxStock(): bool
    {
        return $this->quantity > $this->max_stock;
    }
}
