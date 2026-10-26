<?php

namespace App\Models;

use Database\Factories\StockCardEntryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'stock_card_id',
    'entry_date',
    'reference',
    'receipt_qty',
    'issue_to',
    'issuance_qty',
    'balance_qty',
    'remarks',
    'row_order',
    'requisition_request_id',
    'purchase_request_id',
    'user_id',
])]
class StockCardEntry extends Model
{
    /** @use HasFactory<StockCardEntryFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
            'receipt_qty' => 'decimal:2',
            'issuance_qty' => 'decimal:2',
            'balance_qty' => 'decimal:2',
            'row_order' => 'integer',
        ];
    }

    /**
     * Get the parent stock card.
     *
     * @return BelongsTo<StockCard, $this>
     */
    public function stockCard(): BelongsTo
    {
        return $this->belongsTo(StockCard::class, 'stock_card_id');
    }

    /**
     * Get the optional linked requisition request.
     *
     * @return BelongsTo<RequisitionRequest, $this>
     */
    public function requisitionRequest(): BelongsTo
    {
        return $this->belongsTo(RequisitionRequest::class, 'requisition_request_id');
    }

    /**
     * Get the optional linked purchase request.
     *
     * @return BelongsTo<PurchaseRequest, $this>
     */
    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }

    /**
     * Get the user who recorded this transaction row.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
