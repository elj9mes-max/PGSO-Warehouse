<?php

namespace App\Models;

use Database\Factories\StockCardFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'inventory_item_variant_id',
    'inventory_item_id',
    'office_id',
    'user_id',
    'office',
    'item_name',
    'unit',
    'stock_no',
    'card_no',
])]
class StockCard extends Model
{
    /** @use HasFactory<StockCardFactory> */
    use HasFactory;

    /**
     * Get the ledger entries belonging to this stock card.
     *
     * @return HasMany<StockCardEntry, $this>
     */
    public function entries(): HasMany
    {
        return $this->hasMany(StockCardEntry::class)->orderBy('row_order')->orderBy('id');
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

    /**
     * Legacy accessor stub for parent item.
     */
    public function getItemAttribute()
    {
        return null;
    }

    /**
     * Get the associated office.
     *
     * @return BelongsTo<Office, $this>
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    /**
     * Get the assigned office relation.
     *
     * @return BelongsTo<Office, $this>
     */
    public function assignedOffice(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    /**
     * Get the user who opened or created this card.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Record an inbound receipt (e.g. Purchase Order delivery) into the appropriate stock cards.
     */
    public static function recordReceipt(
        InventoryItemVariant $variant,
        float|int $quantity,
        string $poNumber,
        ?int $officeId = null,
        ?string $officeName = null,
        ?string $deliveryDate = null,
        ?string $remarks = null,
        ?int $userId = null
    ): void {
        $deliveryDate = $deliveryDate ?: now()->toDateString();
        $vItem = $variant->inventoryItem;
        $unit = $variant->effective_unit ?? ($variant->unit ?? ($vItem?->unit ?? 'unit'));
        $stockNo = $variant->item_code ?: ($variant->sku ?: ($vItem?->item_code ?: ($variant->barcode ?: '')));
        $itemName = $variant->variant_name ?: ($vItem?->name ?? 'Standard Item');
        $userId = $userId ?: auth()->id();

        $office = $officeId ? Office::find($officeId) : null;
        $officeCode = $office?->office_code ?: ($office?->office_name ?: 'PGSO');
        $officeName = $officeName ?: ($office?->office_name ?: '');

        $targets = [];
        if ($officeId) {
            $targets[] = [
                'office_id' => $officeId,
                'office' => $officeName,
                'issue_to' => $officeCode,
                'remarks' => null,
            ];
        }
        $targets[] = [
            'office_id' => null,
            'office' => '',
            'issue_to' => $officeCode,
            'remarks' => null,
        ];

        foreach ($targets as $target) {
            $cardQuery = self::where('inventory_item_variant_id', $variant->id);
            if ($target['office_id']) {
                $cardQuery->where('office_id', $target['office_id']);
            } else {
                $cardQuery->whereNull('office_id');
            }
            $card = $cardQuery->latest()->first();

            if (! $card) {
                $card = self::create([
                    'inventory_item_variant_id' => $variant->id,
                    'inventory_item_id' => null,
                    'office_id' => $target['office_id'],
                    'office' => $target['office'],
                    'item_name' => $itemName,
                    'unit' => $unit,
                    'stock_no' => $stockNo,
                    'card_no' => 'Sheet 1',
                    'user_id' => $userId,
                ]);
            } else {
                $dirty = false;
                if (empty($card->office) && $target['office']) {
                    $card->office = $target['office'];
                    $dirty = true;
                }
                if (empty($card->unit) && $unit) {
                    $card->unit = $unit;
                    $dirty = true;
                }
                if (empty($card->stock_no) && $stockNo) {
                    $card->stock_no = $stockNo;
                    $dirty = true;
                }
                if (empty($card->item_name) && $itemName) {
                    $card->item_name = $itemName;
                    $dirty = true;
                }
                if ($dirty) {
                    $card->save();
                }
            }

            $lastEntry = StockCardEntry::where('stock_card_id', $card->id)
                ->orderByDesc('row_order')
                ->orderByDesc('id')
                ->first();
            $previousBalance = $lastEntry ? (float) $lastEntry->balance_qty : 0;
            $newBalance = $previousBalance + (float) $quantity;
            $nextOrder = ($lastEntry ? (int) $lastEntry->row_order : 0) + 1;

            StockCardEntry::create([
                'stock_card_id' => $card->id,
                'entry_date' => $deliveryDate,
                'reference' => $poNumber,
                'receipt_qty' => (float) $quantity,
                'issue_to' => $target['issue_to'],
                'issuance_qty' => null,
                'balance_qty' => $newBalance,
                'remarks' => null,
                'row_order' => $nextOrder,
                'user_id' => $userId,
            ]);
        }
    }

    /**
     * Record an outbound issuance (e.g. requisition release) into the appropriate stock cards.
     */
    public static function recordIssuance(
        InventoryItemVariant $variant,
        float|int $quantity,
        ?string $issueTo = null,
        ?int $officeId = null,
        ?string $officeName = null,
        ?string $reference = null,
        ?string $date = null,
        ?string $remarks = null,
        ?int $userId = null
    ): void {
        $date = $date ?: now()->toDateString();
        $userId = $userId ?: auth()->id();

        $office = $officeId ? Office::find($officeId) : null;
        $officeCode = $office?->office_code ?: ($office?->office_name ?: ($officeName ?: ''));
        $officeName = $officeName ?: ($office?->office_name ?: '');

        $rawRis = $reference ?: ($issueTo ?: 'RIS');
        if ($officeCode && ! str_contains(strtoupper($rawRis), strtoupper($officeCode))) {
            $risDisplay = "{$rawRis} - {$officeCode}";
        } else {
            $risDisplay = $rawRis;
        }

        $remarksText = $remarks ?: null;

        $targets = [];
        if ($officeId) {
            $targets[] = [
                'office_id' => $officeId,
                'office' => $officeName ?: '',
                'issue_to' => $risDisplay,
                'remarks' => $remarksText,
            ];
        }
        $targets[] = [
            'office_id' => null,
            'office' => '',
            'issue_to' => $risDisplay,
            'remarks' => $remarksText,
        ];

        foreach ($targets as $target) {
            $cardQuery = self::where('inventory_item_variant_id', $variant->id);
            if ($target['office_id']) {
                $cardQuery->where('office_id', $target['office_id']);
            } else {
                $cardQuery->whereNull('office_id');
            }
            $card = $cardQuery->latest()->first();

            if (! $card) {
                $vItem = $variant->inventoryItem;
                $card = self::create([
                    'inventory_item_variant_id' => $variant->id,
                    'inventory_item_id' => null,
                    'office_id' => $target['office_id'],
                    'office' => $target['office'],
                    'item_name' => $variant->variant_name ?: ($vItem?->name ?? 'Standard Item'),
                    'unit' => $variant->effective_unit ?? ($variant->unit ?? ($vItem?->unit ?? 'unit')),
                    'stock_no' => $variant->item_code ?: ($variant->sku ?: ($vItem?->item_code ?: ($variant->barcode ?: ''))),
                    'card_no' => 'Sheet 1',
                    'user_id' => $userId,
                ]);
            }

            $lastEntry = StockCardEntry::where('stock_card_id', $card->id)
                ->orderByDesc('row_order')
                ->orderByDesc('id')
                ->first();
            $previousBalance = $lastEntry ? (float) $lastEntry->balance_qty : 0;
            $newBalance = max(0, $previousBalance - (float) $quantity);
            $nextOrder = ($lastEntry ? (int) $lastEntry->row_order : 0) + 1;

            StockCardEntry::create([
                'stock_card_id' => $card->id,
                'entry_date' => $date,
                'reference' => null,
                'receipt_qty' => null,
                'issue_to' => $target['issue_to'],
                'issuance_qty' => (float) $quantity,
                'balance_qty' => $newBalance,
                'remarks' => $target['remarks'],
                'row_order' => $nextOrder,
                'user_id' => $userId,
            ]);
        }
    }

    /**
     * Get or create the consolidated stock card for all offices, synchronizing entries from office cards if needed.
     */
    public static function getOrCreateConsolidatedCard(InventoryItemVariant $variant): self
    {
        $card = self::where('inventory_item_variant_id', $variant->id)
            ->whereNull('office_id')
            ->first();

        if (! $card) {
            $vItem = $variant->inventoryItem;
            $unit = $variant->effective_unit ?? ($variant->unit ?? ($vItem?->unit ?? 'unit'));
            $stockNo = $variant->item_code ?: ($variant->sku ?: ($vItem?->item_code ?: ($variant->barcode ?: '')));
            $itemName = $variant->variant_name ?: ($vItem?->name ?? 'Standard Item');

            $card = self::create([
                'inventory_item_variant_id' => $variant->id,
                'inventory_item_id' => null,
                'office_id' => null,
                'office' => '',
                'item_name' => $itemName,
                'unit' => $unit,
                'stock_no' => $stockNo,
                'card_no' => 'Sheet 1',
                'user_id' => auth()->id(),
            ]);
        }

        // If the consolidated card has no entries but office-specific cards have entries, import them
        if ($card->entries()->doesntExist()) {
            $officeEntries = StockCardEntry::whereHas('stockCard', function ($q) use ($variant) {
                $q->where('inventory_item_variant_id', $variant->id)
                    ->whereNotNull('office_id');
            })->orderBy('entry_date')->orderBy('id')->get();

            $runningBalance = 0;
            $order = 1;
            foreach ($officeEntries as $entry) {
                $receipt = (float) $entry->receipt_qty;
                $issuance = (float) $entry->issuance_qty;
                if ($receipt > 0) {
                    $runningBalance += $receipt;
                } elseif ($issuance > 0) {
                    $runningBalance = max(0, $runningBalance - $issuance);
                }

                StockCardEntry::create([
                    'stock_card_id' => $card->id,
                    'entry_date' => $entry->entry_date,
                    'reference' => $entry->reference,
                    'receipt_qty' => $entry->receipt_qty,
                    'issue_to' => $entry->issue_to,
                    'issuance_qty' => $entry->issuance_qty,
                    'balance_qty' => $runningBalance,
                    'remarks' => null,
                    'row_order' => $order++,
                    'user_id' => $entry->user_id,
                ]);
            }
        }

        return $card;
    }

    /**
     * Initialize a stock card for a new variant.
     */
    public static function initializeForVariant(InventoryItemVariant $variant, ?int $userId = null): self
    {
        $vItem = $variant->inventoryItem;
        $unit = $variant->effective_unit ?? ($variant->unit ?? ($vItem?->unit ?? 'unit'));
        $stockNo = $variant->item_code ?: ($variant->sku ?: ($vItem?->item_code ?: ($variant->barcode ?: '')));
        $itemName = $variant->variant_name ?: ($vItem?->name ?? 'Standard Item');
        $userId = $userId ?: auth()->id();

        $card = self::firstOrCreate(
            [
                'inventory_item_variant_id' => $variant->id,
                'office_id' => null,
            ],
            [
                'inventory_item_id' => null,
                'office' => '',
                'item_name' => $itemName,
                'unit' => $unit,
                'stock_no' => $stockNo,
                'card_no' => 'Sheet 1',
                'user_id' => $userId,
            ]
        );

        if ((int) $variant->quantity > 0 && $card->entries()->doesntExist()) {
            StockCardEntry::create([
                'stock_card_id' => $card->id,
                'entry_date' => now()->toDateString(),
                'reference' => 'Beginning Balance',
                'receipt_qty' => (float) $variant->quantity,
                'issue_to' => null,
                'issuance_qty' => null,
                'balance_qty' => (float) $variant->quantity,
                'remarks' => 'Initial inventory catalog quantity',
                'row_order' => 1,
                'user_id' => $userId,
            ]);
        }

        return $card;
    }
}
