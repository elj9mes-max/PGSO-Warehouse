<?php

namespace App\Models;

use Database\Factories\SsmiReportItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'ssmi_report_id',
    'row_order',
    'inventory_item_variant_id',
    'stock_no',
    'description',
    'unit',
    'ris_quantities',
    'total_quantity',
    'unit_cost',
    'total_cost',
])]
class SsmiReportItem extends Model
{
    /** @use HasFactory<SsmiReportItemFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'row_order' => 'integer',
            'ssmi_report_id' => 'integer',
            'inventory_item_variant_id' => 'integer',
            'ris_quantities' => 'array',
            'total_quantity' => 'decimal:2',
            'unit_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
        ];
    }

    /**
     * Get the parent SSMI report.
     *
     * @return BelongsTo<SsmiReport, $this>
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(SsmiReport::class, 'ssmi_report_id');
    }

    /**
     * Get the associated inventory item variant.
     *
     * @return BelongsTo<InventoryItemVariant, $this>
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(InventoryItemVariant::class, 'inventory_item_variant_id');
    }
}
