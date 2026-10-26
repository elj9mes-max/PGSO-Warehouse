<?php

namespace App\Models;

use Database\Factories\SsmiReportFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'report_no',
    'period_text',
    'period_date',
    'office_id',
    'user_id',
    'ris_columns',
    'total_amount',
    'status',
    'prepared_by_name',
    'prepared_by_designation',
    'approved_by_name',
    'approved_by_designation',
    'accounting_officer_name',
    'accounting_received_at',
    'remarks',
])]
class SsmiReport extends Model
{
    /** @use HasFactory<SsmiReportFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ris_columns' => 'array',
            'period_date' => 'date',
            'total_amount' => 'decimal:2',
            'accounting_received_at' => 'datetime',
        ];
    }

    /**
     * Get the matrix line items belonging to this SSMI report.
     *
     * @return HasMany<SsmiReportItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(SsmiReportItem::class)->orderBy('row_order')->orderBy('id');
    }

    /**
     * Get the associated office scope if restricted to a single office.
     *
     * @return BelongsTo<Office, $this>
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Get the user who created or last saved this report.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a unique sequential report number.
     */
    public static function generateReportNo(): string
    {
        $yearMonth = date('Y-m');
        $count = static::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->count() + 1;

        return sprintf('SSMI-%s-%04d', $yearMonth, $count);
    }

    /**
     * Record an approved or issued requisition request directly into the SSMI database.
     */
    public static function recordRequisition(RequisitionRequest $requisition): ?self
    {
        // Reload items with variants and office
        $requisition->loadMissing(['items.variant.inventoryItem', 'office']);

        // Gather approved items with quantity > 0
        $issuedItems = $requisition->items->filter(function ($item) {
            $qty = (float) ($item->quantity_issued ?: 0);

            return $qty > 0;
        });

        if ($issuedItems->isEmpty()) {
            return null;
        }

        $risNo = trim($requisition->ris_no ?: $requisition->request_number);
        if (empty($risNo)) {
            $risNo = 'RIS-'.$requisition->id;
        }

        // Find an open draft SSMI report that has an empty slot or already contains this RIS
        $allDrafts = static::with('items')
            ->where('status', 'draft')
            ->latest()
            ->get();

        $targetReport = null;
        $targetColIdx = -1;

        foreach ($allDrafts as $draft) {
            $cols = is_array($draft->ris_columns) ? $draft->ris_columns : array_fill(0, 8, '');
            // Check if already contains this RIS
            $existingIdx = array_search($risNo, $cols, true);
            if ($existingIdx !== false) {
                $targetReport = $draft;
                $targetColIdx = (int) $existingIdx;
                break;
            }

            // Check if has an empty slot (less than 8 columns)
            for ($i = 0; $i < 8; $i++) {
                if (empty($cols[$i])) {
                    $targetReport = $draft;
                    $targetColIdx = $i;
                    break 2;
                }
            }
        }

        // If no open draft report with space found, create a new SSMI report
        if (! $targetReport) {
            $targetColIdx = 0;
            $cols = array_fill(0, 8, '');
            $cols[0] = $risNo;

            $periodText = $requisition->issued_at
                ? $requisition->issued_at->format('m/d/Y')
                : ($requisition->approved_at ? $requisition->approved_at->format('m/d/Y') : now()->format('m/d/Y'));

            $targetReport = static::create([
                'report_no' => static::generateReportNo(),
                'period_text' => $periodText,
                'period_date' => now()->toDateString(),
                'office_id' => $requisition->office_id,
                'user_id' => auth()->id() ?? $requisition->approved_by_id,
                'ris_columns' => $cols,
                'status' => 'draft',
                'prepared_by_name' => 'Raymond G. Amat',
                'prepared_by_designation' => 'AA-III Warehouse',
                'approved_by_name' => 'Joel Friginal',
                'approved_by_designation' => 'SAO',
            ]);
        } else {
            $cols = is_array($targetReport->ris_columns) ? $targetReport->ris_columns : array_fill(0, 8, '');
            $cols[$targetColIdx] = $risNo;
            $targetReport->ris_columns = $cols;
            $targetReport->save();
        }

        // Record or update the matrix rows in ssmi_report_items
        $existingItems = $targetReport->items()->get();

        foreach ($issuedItems as $reqItem) {
            $stockNo = trim($reqItem->stock_no ?: ($reqItem->variant?->sku ?? ''));
            $desc = trim($reqItem->item_name ?: ($reqItem->variant?->variant_name ?: 'Standard Item'));
            $unit = trim($reqItem->unit ?: ($reqItem->variant?->unit ?: 'pcs.'));
            $unitCost = (float) ($reqItem->unit_cost ?: ($reqItem->variant?->unit_cost ?? 0));
            $qty = (float) $reqItem->quantity_issued;

            // Match existing row by stock_no or description
            $matchedItem = $existingItems->first(function ($ei) use ($stockNo, $desc) {
                if ($stockNo !== '' && $ei->stock_no === $stockNo) {
                    return true;
                }

                return strcasecmp(trim($ei->description), $desc) === 0;
            });

            if ($matchedItem) {
                $risQtys = is_array($matchedItem->ris_quantities) ? $matchedItem->ris_quantities : array_fill(0, 8, '');
                $risQtys[$targetColIdx] = $qty;
                $matchedItem->ris_quantities = $risQtys;

                $totalQty = 0;
                foreach ($risQtys as $q) {
                    if (is_numeric($q)) {
                        $totalQty += (float) $q;
                    }
                }
                $matchedItem->total_quantity = $totalQty;
                $matchedItem->total_cost = $totalQty * (float) $matchedItem->unit_cost;
                $matchedItem->save();
            } else {
                $risQtys = array_fill(0, 8, '');
                $risQtys[$targetColIdx] = $qty;

                $nextOrder = $existingItems->count() + 1;
                $newItem = SsmiReportItem::create([
                    'ssmi_report_id' => $targetReport->id,
                    'row_order' => $nextOrder,
                    'inventory_item_variant_id' => $reqItem->inventory_item_variant_id,
                    'stock_no' => $stockNo ?: null,
                    'description' => $desc,
                    'unit' => $unit,
                    'ris_quantities' => $risQtys,
                    'total_quantity' => $qty,
                    'unit_cost' => $unitCost,
                    'total_cost' => $qty * $unitCost,
                ]);
                $existingItems->push($newItem);
            }
        }

        // Update overall report total amount
        $targetReport->update([
            'total_amount' => $targetReport->items()->sum('total_cost'),
        ]);

        return $targetReport;
    }
}
