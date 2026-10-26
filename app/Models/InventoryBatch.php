<?php

namespace App\Models;

use Database\Factories\InventoryBatchFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'batch_number',
    'inventory_item_id',
    'inventory_item_variant_id',
    'office_id',
    'po_number',
    'air_number',
    'pr_number',
    'supplier',
    'delivery_date',
    'unit_cost',
    'quantity_received',
    'quantity_remaining',
    'status',
    'remarks',
])]
class InventoryBatch extends Model
{
    /** @use HasFactory<InventoryBatchFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'delivery_date' => 'date',
            'unit_cost' => 'decimal:2',
            'quantity_received' => 'integer',
            'quantity_remaining' => 'integer',
        ];
    }

    /**
     * Legacy relationship to parent inventory item.
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    /**
     * Get the item variant.
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(InventoryItemVariant::class, 'inventory_item_variant_id');
    }

    /**
     * Get the destination office (null for Central Warehouse).
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Scope query to active batches with remaining stock.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active')->where('quantity_remaining', '>', 0);
    }

    /**
     * Scope query to central warehouse batches.
     */
    public function scopeCentralWarehouse(Builder $query): Builder
    {
        return $query->whereNull('office_id');
    }

    /**
     * Scope query to specific office batches.
     */
    public function scopeForOffice(Builder $query, int $officeId): Builder
    {
        return $query->where('office_id', $officeId);
    }

    /**
     * Determine if the batch is depleted.
     */
    public function isDepleted(): bool
    {
        return $this->quantity_remaining <= 0;
    }
}
