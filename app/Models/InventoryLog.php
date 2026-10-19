<?php

namespace App\Models;

use App\Enums\InventoryLogAction;
use Database\Factories\InventoryLogFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'action_type',
    'batch_id',
    'reference_number',
    'user_id',
    'office_id',
    'inventory_item_id',
    'inventory_item_variant_id',
    'office_name',
    'item_name',
    'variant_name',
    'quantity',
    'quantity_before',
    'quantity_after',
    'recipient',
    'purpose',
    'old_values',
    'new_values',
    'remarks',
    'ip_address',
    'user_agent',
])]
class InventoryLog extends Model
{
    /** @use HasFactory<InventoryLogFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'action_type' => InventoryLogAction::class,
            'quantity' => 'integer',
            'quantity_before' => 'integer',
            'quantity_after' => 'integer',
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    /**
     * Get the user who performed this action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the associated office (source or destination).
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Get the parent inventory item.
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * Get the specific item variant.
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(InventoryItemVariant::class, 'inventory_item_variant_id');
    }

    /**
     * Scope query to stock transfers.
     */
    public function scopeTransfers(Builder $query): Builder
    {
        return $query->where('action_type', InventoryLogAction::Transfer);
    }

    /**
     * Scope query to stock releases / issuances.
     */
    public function scopeReleases(Builder $query): Builder
    {
        return $query->where('action_type', InventoryLogAction::Release);
    }

    /**
     * Scope query to item or variant edits.
     */
    public function scopeEdits(Builder $query): Builder
    {
        return $query->whereIn('action_type', [
            InventoryLogAction::ItemUpdated,
            InventoryLogAction::VariantUpdated,
        ]);
    }

    /**
     * Scope query to inventory additions.
     */
    public function scopeInventoryAdditions(Builder $query): Builder
    {
        return $query->whereIn('action_type', [
            InventoryLogAction::ItemCreated,
            InventoryLogAction::VariantCreated,
            InventoryLogAction::InventoryAdded,
        ]);
    }
}
