<?php

namespace App\Models;

use Database\Factories\PurchaseRequestItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'purchase_request_id',
    'inventory_item_variant_id',
    'item_code',
    'barcode',
    'item_name',
    'variant_name',
    'description',
    'unit',
    'quantity_requested',
    'estimated_unit_cost',
    'estimated_total_cost',
    'remarks',
])]
class PurchaseRequestItem extends Model
{
    /** @use HasFactory<PurchaseRequestItemFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'purchase_request_id' => 'integer',
            'inventory_item_variant_id' => 'integer',
            'quantity_requested' => 'integer',
            'estimated_unit_cost' => 'decimal:2',
            'estimated_total_cost' => 'decimal:2',
        ];
    }

    /**
     * Get the parent purchase request.
     */
    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }

    /**
     * Get the inventory item variant linked to this line item.
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(InventoryItemVariant::class, 'inventory_item_variant_id');
    }
}
