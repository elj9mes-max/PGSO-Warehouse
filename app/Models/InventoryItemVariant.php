<?php

namespace App\Models;

use Database\Factories\InventoryItemVariantFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'inventory_item_id',
    'variant_name',
    'item_code',
    'category',
    'sku',
    'barcode',
    'variant_description',
    'unit',
    'quantity',
    'available_for_transfer',
    'reorder_level',
    'unit_cost',
    'location',
    'status',
    'is_for_inspection',
    'isForInspection',
])]
class InventoryItemVariant extends Model
{
    /** @use HasFactory<InventoryItemVariantFactory> */
    use HasFactory;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (self $variant): void {
            if ($variant->available_for_transfer === null) {
                $variant->available_for_transfer = (int) ($variant->quantity ?? 0);
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'available_for_transfer' => 'integer',
            'reorder_level' => 'integer',
            'unit_cost' => 'decimal:2',
            'is_for_inspection' => 'boolean',
        ];
    }

    /**
     * Accessor for isForInspection (camelCase alias).
     */
    public function getIsForInspectionAttribute(): bool
    {
        return (bool) ($this->attributes['is_for_inspection'] ?? true);
    }

    /**
     * Mutator for isForInspection (camelCase alias).
     */
    public function setIsForInspectionAttribute($value): void
    {
        $this->attributes['is_for_inspection'] = (bool) $value;
    }

    /**
     * Scope to only include inspected (active) variants.
     */
    public function scopeInspected($query)
    {
        return $query->where('is_for_inspection', false);
    }

    /**
     * Scope to only include variants pending inspection.
     */
    public function scopeForInspection($query)
    {
        return $query->where('is_for_inspection', true);
    }

    /**
     * Get the inventory item that owns this variant.
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    /**
     * Get the office inventory assignments for this variant.
     */
    public function officeInventories(): HasMany
    {
        return $this->hasMany(OfficeInventory::class, 'inventory_item_variants_id');
    }

    /**
     * Get the offices that hold this variant.
     */
    public function offices(): BelongsToMany
    {
        return $this->belongsToMany(
            Office::class,
            'office_inventories',
            'inventory_item_variants_id',
            'office_id'
        )->withPivot('quantity')->withTimestamps();
    }

    /**
     * Get the inventory batches for this variant.
     */
    public function batches(): HasMany
    {
        return $this->hasMany(InventoryBatch::class, 'inventory_item_variant_id');
    }

    /**
     * Get active batches with remaining quantity.
     */
    public function activeBatches(): HasMany
    {
        return $this->batches()->where('status', 'active')->where('quantity_remaining', '>', 0);
    }

    /**
     * Determine the effective unit of measurement for this variant.
     */
    public function getEffectiveUnitAttribute(): string
    {
        return $this->unit ?: ($this->inventoryItem?->unit ?? 'pcs');
    }

    /**
     * Determine if the variant is low on stock.
     */
    public function isLowStock(): bool
    {
        return $this->quantity > 0 && $this->quantity <= $this->reorder_level;
    }

    /**
     * Determine if the variant is out of stock.
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity <= 0;
    }

    /**
     * Determine if the variant is in stock.
     */
    public function isInStock(): bool
    {
        return $this->quantity > $this->reorder_level;
    }
}
