<?php

namespace App\Models;

use Database\Factories\InventoryItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'item_code',
    'name',
    'description',
    'category',
    'unit',
    'quantity',
    'reorder_level',
    'unit_cost',
    'location',
    'status',
])]
class InventoryItem extends Model
{
    /** @use HasFactory<InventoryItemFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'reorder_level' => 'integer',
            'unit_cost' => 'decimal:2',
        ];
    }

    /**
     * Get the variants associated with this inventory item.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(InventoryItemVariant::class, 'inventory_item_id');
    }

    /**
     * Get the variants that have passed inspection and are ready for inventory.
     */
    public function inspectedVariants(): HasMany
    {
        return $this->variants()->where('is_for_inspection', false);
    }

    /**
     * Get the inventory batches for this item.
     */
    public function batches(): HasMany
    {
        return $this->hasMany(InventoryBatch::class, 'inventory_item_id');
    }

    /**
     * Check if this item has variants.
     */
    public function hasVariants(): bool
    {
        return $this->variants()->exists();
    }

    /**
     * Get total quantity across inspected variants if any exist, otherwise return base quantity.
     */
    public function getTotalStockAttribute(): int
    {
        if ($this->relationLoaded('variants')) {
            $inspected = $this->variants->where('is_for_inspection', false);

            return $inspected->isNotEmpty()
                ? (int) $inspected->sum('quantity')
                : ($this->variants->isEmpty() ? $this->quantity : 0);
        }

        if ($this->variants()->exists()) {
            return (int) $this->variants()->where('is_for_inspection', false)->sum('quantity');
        }

        return $this->quantity;
    }

    /**
     * Determine if the item is low on stock.
     */
    public function isLowStock(): bool
    {
        return $this->quantity > 0 && $this->quantity <= $this->reorder_level;
    }

    /**
     * Determine if the item is out of stock.
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity <= 0;
    }

    /**
     * Determine if the item is in stock.
     */
    public function isInStock(): bool
    {
        return $this->quantity > $this->reorder_level;
    }
}
