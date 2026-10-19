<?php

namespace App\Models;

use Database\Factories\RequisitionRequestItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'requisition_request_id',
    'inventory_item_variant_id',
    'stock_no',
    'item_name',
    'variant_name',
    'description',
    'unit',
    'quantity_requested',
    'quantity_issued',
    'unit_cost',
    'total_cost',
    'remarks',
    'status',
])]
class RequisitionRequestItem extends Model
{
    /** @use HasFactory<RequisitionRequestItemFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'requisition_request_id' => 'integer',
            'inventory_item_variant_id' => 'integer',
            'quantity_requested' => 'integer',
            'quantity_issued' => 'integer',
            'unit_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
        ];
    }

    /**
     * Get the parent requisition request.
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(RequisitionRequest::class, 'requisition_request_id');
    }

    /**
     * Get the inventory item variant linked to this line item.
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(InventoryItemVariant::class, 'inventory_item_variant_id');
    }
}
