<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\InventoryItemVariant;
use App\Models\InventoryLog;
use App\Models\Office;
use App\Models\OfficeInventory;
use App\Models\RequisitionRequest;
use App\Models\SsmiReport;
use App\Models\SsmiReportItem;
use App\Models\StockCard;
use App\Models\StockCardEntry;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class ReportController extends Controller
{
    /**
     * Display Audit & Reports Center overview.
     */
    public function index(Request $request): View
    {
        $variants = InventoryItemVariant::with(['inventoryItem', 'officeInventories.office'])
            ->where('is_for_inspection', false)
            ->orderBy('variant_name')
            ->get();

        $offices = Office::with(['officeInventories.variant.inventoryItem'])->orderBy('office_name')->get();

        $logs = InventoryLog::with(['user', 'office', 'variant.inventoryItem'])
            ->orderByDesc('created_at')
            ->get();

        $officeInventories = OfficeInventory::with(['office', 'variant.inventoryItem'])
            ->where('quantity', '>', 0)
            ->orderBy('office_id')
            ->get();

        $requisitions = RequisitionRequest::with(['office', 'items.variant', 'user'])
            ->whereIn('status', ['issued', 'approved'])
            ->latest('issued_at')
            ->get();

        $releaseLogs = InventoryLog::releases()
            ->with(['office', 'variant', 'user'])
            ->latest()
            ->get();

        $categories = Category::orderBy('name')->pluck('name');

        $totalWarehouseUnits = (int) $variants->sum('quantity');
        $totalOfficeUnits = (int) $officeInventories->sum('quantity');
        $totalLogsCount = (int) $logs->count();
        $totalOfficesCount = (int) $offices->count();
        $totalReleasesUnits = (int) $releaseLogs->sum('quantity');

        return view('admin.report.report-page', compact(
            'variants',
            'offices',
            'logs',
            'officeInventories',
            'requisitions',
            'releaseLogs',
            'categories',
            'totalWarehouseUnits',
            'totalOfficeUnits',
            'totalLogsCount',
            'totalOfficesCount',
            'totalReleasesUnits'
        ));
    }

    /**
     * Redirect singular /report to reports index.
     */
    public function redirectReport(): RedirectResponse
    {
        return redirect()->route('admin.reports.index');
    }

    /**
     * Display Stock Card report ledger.
     */
    public function stockCard(Request $request): View
    {
        $variants = InventoryItemVariant::with(['inventoryItem', 'officeInventories.office'])
            ->where('is_for_inspection', false)
            ->orderBy('variant_name')
            ->get();

        $offices = Office::orderBy('office_name')->get();

        $selectedVariantId = $request->query('variant_id');
        $isAllItemsMode = ($selectedVariantId === 'all');

        $selectedVariant = (! $isAllItemsMode && $selectedVariantId)
            ? $variants->firstWhere('id', (int) $selectedVariantId)
            : null;

        $selectedOfficeId = $request->query('office_id');
        $selectedOffice = $selectedOfficeId
            ? $offices->firstWhere('id', (int) $selectedOfficeId)
            : null;

        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');
        $isDateFilterActive = ! empty($fromDate) || ! empty($toDate);

        $stockCard = null;
        $initialRows = [];
        $bulkCards = [];

        if ($isAllItemsMode || ($isDateFilterActive && ! $selectedVariant)) {
            $isAllItemsMode = true;

            $cardsQuery = StockCard::with('entries');
            if ($selectedOffice) {
                $cardsQuery->where('office_id', $selectedOffice->id);
            } else {
                $cardsQuery->whereNull('office_id');
            }
            $existingCards = $cardsQuery->get()->keyBy('inventory_item_variant_id');

            foreach ($variants as $variant) {
                $card = $existingCards->get($variant->id);
                $vItem = $variant->inventoryItem;

                $itemName = $card?->item_name ?: ($variant->variant_name ?: ($vItem?->name ?? 'Standard Item'));
                $unit = $card?->unit ?: ($variant->effective_unit ?? ($variant->unit ?? 'unit'));
                $stockNo = $card?->stock_no ?: ($variant->item_code ?: ($variant->sku ?: ($variant->barcode ?: '')));
                $officeName = $card?->office ?: ($selectedOffice?->office_name ?? 'PROVINCIAL GENERAL SERVICES OFFICE');

                $cardRows = [];

                if ($card && $card->entries) {
                    $entries = $card->entries;

                    $filteredEntries = $entries;
                    if ($fromDate && $toDate) {
                        $filteredEntries = $entries->filter(function ($e) use ($fromDate, $toDate) {
                            return $e->entry_date && $e->entry_date->format('Y-m-d') >= $fromDate && $e->entry_date->format('Y-m-d') <= $toDate;
                        });
                    } elseif ($fromDate) {
                        $filteredEntries = $entries->filter(function ($e) use ($fromDate) {
                            return $e->entry_date && $e->entry_date->format('Y-m-d') >= $fromDate;
                        });
                    } elseif ($toDate) {
                        $filteredEntries = $entries->filter(function ($e) use ($toDate) {
                            return $e->entry_date && $e->entry_date->format('Y-m-d') <= $toDate;
                        });
                    }

                    // When date filter is active, only show cards with activity within the filtered date range
                    if ($isDateFilterActive && $filteredEntries->isEmpty()) {
                        continue;
                    }

                    if ($fromDate) {
                        $priorEntry = $entries->filter(function ($e) use ($fromDate) {
                            return $e->entry_date && $e->entry_date->format('Y-m-d') < $fromDate;
                        })->sortByDesc('row_order')->sortByDesc('id')->first();

                        if ($priorEntry && $priorEntry->balance_qty !== null) {
                            $cardRows[] = [
                                'id' => 'bf-'.$variant->id,
                                'date' => Carbon::parse($fromDate)->format('m/d/y'),
                                'po' => 'Bal. Brought Fwd',
                                'receiptQty' => '',
                                'risOffice' => '',
                                'issuanceQty' => '',
                                'balanceQty' => (float) $priorEntry->balance_qty,
                                'remarks' => 'Carried forward',
                            ];
                        }
                    }

                    foreach ($filteredEntries->sortBy('row_order')->values() as $entry) {
                        $cardRows[] = [
                            'id' => $entry->id,
                            'date' => $entry->entry_date?->format('m/d/y') ?: '',
                            'po' => $entry->reference ?? '',
                            'receiptQty' => $entry->receipt_qty !== null ? (float) $entry->receipt_qty : '',
                            'risOffice' => $entry->issue_to ?? '',
                            'issuanceQty' => $entry->issuance_qty !== null ? (float) $entry->issuance_qty : '',
                            'balanceQty' => $entry->balance_qty !== null ? (float) $entry->balance_qty : '',
                            'remarks' => $entry->remarks ?? '',
                        ];
                    }
                } else {
                    if ($isDateFilterActive) {
                        continue;
                    }
                }

                $bulkCards[] = [
                    'id' => $card?->id,
                    'variant_id' => $variant->id,
                    'item_name' => $itemName,
                    'unit' => $unit,
                    'stock_no' => $stockNo,
                    'office' => $officeName,
                    'rows' => $cardRows,
                ];
            }
        } else {
            if ($selectedVariant) {
                if ($selectedOffice) {
                    $stockCard = StockCard::with('entries')
                        ->where('inventory_item_variant_id', $selectedVariant->id)
                        ->where('office_id', $selectedOffice->id)
                        ->latest()
                        ->first();
                } else {
                    $stockCard = StockCard::with('entries')
                        ->where('inventory_item_variant_id', $selectedVariant->id)
                        ->whereNull('office_id')
                        ->latest()
                        ->first();

                    if (! $stockCard) {
                        $stockCard = StockCard::getOrCreateConsolidatedCard($selectedVariant);
                        $stockCard->load('entries');
                    }
                }

                if ($stockCard && $stockCard->entries->isNotEmpty()) {
                    $entries = $stockCard->entries;

                    if ($isDateFilterActive) {
                        $filteredEntries = $entries;
                        if ($fromDate && $toDate) {
                            $filteredEntries = $entries->filter(function ($e) use ($fromDate, $toDate) {
                                return $e->entry_date && $e->entry_date->format('Y-m-d') >= $fromDate && $e->entry_date->format('Y-m-d') <= $toDate;
                            });
                        } elseif ($fromDate) {
                            $filteredEntries = $entries->filter(function ($e) use ($fromDate) {
                                return $e->entry_date && $e->entry_date->format('Y-m-d') >= $fromDate;
                            });
                        } elseif ($toDate) {
                            $filteredEntries = $entries->filter(function ($e) use ($toDate) {
                                return $e->entry_date && $e->entry_date->format('Y-m-d') <= $toDate;
                            });
                        }

                        if ($fromDate) {
                            $priorEntry = $entries->filter(function ($e) use ($fromDate) {
                                return $e->entry_date && $e->entry_date->format('Y-m-d') < $fromDate;
                            })->sortByDesc('row_order')->sortByDesc('id')->first();

                            if ($priorEntry && $priorEntry->balance_qty !== null) {
                                $initialRows[] = [
                                    'id' => 'bf-'.$selectedVariant->id,
                                    'date' => Carbon::parse($fromDate)->format('m/d/y'),
                                    'po' => 'Bal. Brought Fwd',
                                    'receiptQty' => '',
                                    'risOffice' => '',
                                    'issuanceQty' => '',
                                    'balanceQty' => (float) $priorEntry->balance_qty,
                                    'remarks' => 'Carried forward',
                                ];
                            }
                        }

                        foreach ($filteredEntries->sortBy('row_order')->values() as $entry) {
                            $initialRows[] = [
                                'id' => $entry->id,
                                'date' => $entry->entry_date?->format('m/d/y') ?: '',
                                'po' => $entry->reference ?? '',
                                'receiptQty' => $entry->receipt_qty !== null ? (float) $entry->receipt_qty : '',
                                'risOffice' => $entry->issue_to ?? '',
                                'issuanceQty' => $entry->issuance_qty !== null ? (float) $entry->issuance_qty : '',
                                'balanceQty' => $entry->balance_qty !== null ? (float) $entry->balance_qty : '',
                                'remarks' => $entry->remarks ?? '',
                            ];
                        }
                    } else {
                        $initialRows = $entries->sortBy('row_order')->values()->map(function ($entry) {
                            return [
                                'id' => $entry->id,
                                'date' => $entry->entry_date?->format('m/d/y') ?: '',
                                'po' => $entry->reference ?? '',
                                'receiptQty' => $entry->receipt_qty !== null ? (float) $entry->receipt_qty : '',
                                'risOffice' => $entry->issue_to ?? '',
                                'issuanceQty' => $entry->issuance_qty !== null ? (float) $entry->issuance_qty : '',
                                'balanceQty' => $entry->balance_qty !== null ? (float) $entry->balance_qty : '',
                                'remarks' => $entry->remarks ?? '',
                            ];
                        })->all();
                    }
                }
            }
        }

        return view('admin.report.stock-card', compact(
            'variants',
            'offices',
            'selectedVariant',
            'selectedVariantId',
            'selectedOffice',
            'selectedOfficeId',
            'stockCard',
            'initialRows',
            'isAllItemsMode',
            'bulkCards',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * Return Stock Card ledger JSON data for a specific variant/office.
     */
    public function stockCardData(Request $request): JsonResponse
    {
        $variantId = $request->query('variant_id');
        $officeId = $request->query('office_id');

        if (! $variantId) {
            return response()->json([
                'success' => true,
                'stock_card' => null,
                'defaults' => null,
                'rows' => [],
            ]);
        }

        $variant = InventoryItemVariant::with(['inventoryItem'])->find($variantId);
        if (! $variant) {
            return response()->json(['success' => false, 'message' => 'Variant not found.'], 404);
        }

        $selectedOffice = $officeId ? Office::find($officeId) : null;

        $card = null;
        if ($selectedOffice) {
            $card = StockCard::with('entries')
                ->where('inventory_item_variant_id', $variant->id)
                ->where('office_id', $selectedOffice->id)
                ->latest()
                ->first();
        } else {
            $card = StockCard::with('entries')
                ->where('inventory_item_variant_id', $variant->id)
                ->whereNull('office_id')
                ->latest()
                ->first();
        }

        $vItem = $variant->inventoryItem;
        $defaults = [
            'variant_id' => $variant->id,
            'office_id' => $selectedOffice?->id,
            'item_name' => $variant->variant_name ?: ($vItem?->name ?? 'Standard Item'),
            'unit' => $variant->effective_unit ?? ($variant->unit ?? 'unit'),
            'stock_no' => $variant->item_code ?: ($variant->sku ?: ''),
            'office' => $selectedOffice ? $selectedOffice->office_name : '',
            'card_no' => 'Sheet 1',
        ];

        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');
        $isDateFilterActive = ! empty($fromDate) || ! empty($toDate);

        $rows = [];
        if ($card && $card->entries->isNotEmpty()) {
            $entries = $card->entries;

            if ($isDateFilterActive) {
                $filteredEntries = $entries;
                if ($fromDate && $toDate) {
                    $filteredEntries = $entries->filter(function ($e) use ($fromDate, $toDate) {
                        return $e->entry_date && $e->entry_date->format('Y-m-d') >= $fromDate && $e->entry_date->format('Y-m-d') <= $toDate;
                    });
                } elseif ($fromDate) {
                    $filteredEntries = $entries->filter(function ($e) use ($fromDate) {
                        return $e->entry_date && $e->entry_date->format('Y-m-d') >= $fromDate;
                    });
                } elseif ($toDate) {
                    $filteredEntries = $entries->filter(function ($e) use ($toDate) {
                        return $e->entry_date && $e->entry_date->format('Y-m-d') <= $toDate;
                    });
                }

                if ($fromDate) {
                    $priorEntry = $entries->filter(function ($e) use ($fromDate) {
                        return $e->entry_date && $e->entry_date->format('Y-m-d') < $fromDate;
                    })->sortByDesc('row_order')->sortByDesc('id')->first();

                    if ($priorEntry && $priorEntry->balance_qty !== null) {
                        $rows[] = [
                            'id' => 'bf-'.$variant->id,
                            'date' => Carbon::parse($fromDate)->format('m/d/y'),
                            'po' => 'Bal. Brought Fwd',
                            'receiptQty' => '',
                            'risOffice' => '',
                            'issuanceQty' => '',
                            'balanceQty' => (float) $priorEntry->balance_qty,
                            'remarks' => 'Carried forward',
                        ];
                    }
                }

                foreach ($filteredEntries->sortBy('row_order')->values() as $entry) {
                    $rows[] = [
                        'id' => $entry->id,
                        'date' => $entry->entry_date?->format('m/d/y') ?: '',
                        'po' => $entry->reference ?? '',
                        'receiptQty' => $entry->receipt_qty !== null ? (float) $entry->receipt_qty : '',
                        'risOffice' => $entry->issue_to ?? '',
                        'issuanceQty' => $entry->issuance_qty !== null ? (float) $entry->issuance_qty : '',
                        'balanceQty' => $entry->balance_qty !== null ? (float) $entry->balance_qty : '',
                        'remarks' => $entry->remarks ?? '',
                    ];
                }
            } else {
                $rows = $entries->sortBy('row_order')->values()->map(function ($entry) {
                    return [
                        'id' => $entry->id,
                        'date' => $entry->entry_date?->format('m/d/y') ?: '',
                        'po' => $entry->reference ?? '',
                        'receiptQty' => $entry->receipt_qty !== null ? (float) $entry->receipt_qty : '',
                        'risOffice' => $entry->issue_to ?? '',
                        'issuanceQty' => $entry->issuance_qty !== null ? (float) $entry->issuance_qty : '',
                        'balanceQty' => $entry->balance_qty !== null ? (float) $entry->balance_qty : '',
                        'remarks' => $entry->remarks ?? '',
                    ];
                })->all();
            }
        }

        return response()->json([
            'success' => true,
            'stock_card' => $card ? [
                'id' => $card->id,
                'office_id' => $card->office_id,
                'office' => $card->office,
                'item_name' => $card->item_name,
                'unit' => $card->unit,
                'stock_no' => $card->stock_no,
                'card_no' => $card->card_no,
            ] : null,
            'defaults' => $defaults,
            'rows' => $rows,
        ]);
    }

    /**
     * Save/update Stock Card and its ledger entries.
     */
    public function saveStockCard(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'stock_card_id' => 'nullable|integer',
            'variant_id' => 'nullable|integer|exists:inventory_item_variants,id',
            'office_id' => 'nullable|integer|exists:offices,id',
            'office' => 'nullable|string|max:191',
            'item_name' => 'required|string|max:191',
            'unit' => 'nullable|string|max:50',
            'stock_no' => 'nullable|string|max:100',
            'card_no' => 'nullable|string|max:50',
            'rows' => 'nullable|array',
            'rows.*.date' => 'nullable|string|max:50',
            'rows.*.po' => 'nullable|string|max:191',
            'rows.*.receiptQty' => 'nullable|numeric|min:0',
            'rows.*.risOffice' => 'nullable|string|max:191',
            'rows.*.issuanceQty' => 'nullable|numeric|min:0',
            'rows.*.balanceQty' => 'nullable|numeric',
            'rows.*.remarks' => 'nullable|string|max:1000',
        ]);

        $stockCard = DB::transaction(function () use ($validated) {
            $cardId = $validated['stock_card_id'] ?? null;
            $card = null;

            if ($cardId) {
                $card = StockCard::find($cardId);
            }

            if (! $card && ! empty($validated['variant_id'])) {
                $q = StockCard::where('inventory_item_variant_id', $validated['variant_id']);
                if (! empty($validated['office_id'])) {
                    $q->where('office_id', $validated['office_id']);
                } else {
                    $q->whereNull('office_id');
                }
                $card = $q->latest()->first();
            }

            if (! $card) {
                $card = new StockCard;
                $card->user_id = auth()->id();
            }

            $card->inventory_item_variant_id = $validated['variant_id'] ?? $card->inventory_item_variant_id;
            if ($card->inventory_item_variant_id) {
                $card->inventory_item_id = null;
            }
            $card->office_id = ! empty($validated['office_id']) ? (int) $validated['office_id'] : null;
            $card->office = ! empty($validated['office']) ? trim($validated['office']) : '';
            $card->item_name = $validated['item_name'];
            $card->unit = $validated['unit'] ?? null;
            $card->stock_no = $validated['stock_no'] ?? null;
            $card->card_no = $validated['card_no'] ?? null;
            $card->save();

            // Synchronize entries: delete previous entries and insert new ones preserving sequence
            $card->entries()->delete();

            $rows = $validated['rows'] ?? [];
            $order = 1;
            foreach ($rows as $row) {
                $date = ! empty($row['date']) ? $row['date'] : null;
                $po = ! empty($row['po']) ? trim($row['po']) : null;
                $receiptQty = isset($row['receiptQty']) && $row['receiptQty'] !== '' ? (float) $row['receiptQty'] : null;
                $risOffice = ! empty($row['risOffice']) ? trim($row['risOffice']) : null;
                $issuanceQty = isset($row['issuanceQty']) && $row['issuanceQty'] !== '' ? (float) $row['issuanceQty'] : null;
                $balanceQty = isset($row['balanceQty']) && $row['balanceQty'] !== '' ? (float) $row['balanceQty'] : 0;
                $remarks = ! empty($row['remarks']) ? trim($row['remarks']) : null;

                // Only save rows that have at least some data
                if ($date || $po || $receiptQty !== null || $risOffice || $issuanceQty !== null || $balanceQty != 0 || $remarks) {
                    $parsedDate = null;
                    if ($date) {
                        try {
                            $parsedDate = Carbon::parse($date)->format('Y-m-d');
                        } catch (Exception $e) {
                            $parsedDate = null;
                        }
                    }

                    StockCardEntry::create([
                        'stock_card_id' => $card->id,
                        'entry_date' => $parsedDate,
                        'reference' => $po,
                        'receipt_qty' => $receiptQty,
                        'issue_to' => $risOffice,
                        'issuance_qty' => $issuanceQty,
                        'balance_qty' => $balanceQty,
                        'remarks' => $remarks,
                        'row_order' => $order++,
                        'user_id' => auth()->id(),
                    ]);
                }
            }

            return $card;
        });

        return response()->json([
            'success' => true,
            'message' => 'Stock Card and ledger entries saved successfully!',
            'stock_card_id' => $stockCard->id,
        ]);
    }

    /**
     * Display Summary of Supplies and Materials Issued (SSMI) Report.
     */
    public function ssmi(Request $request): View
    {
        $fromDate = $request->query('from_date', '');
        $toDate = $request->query('to_date', '');

        // Direct SSMI database query across all offices
        $ssmiQuery = SsmiReport::with(['items', 'office'])->orderBy('id', 'asc');

        if ($request->filled('report_id')) {
            $ssmiQuery->where('id', (int) $request->query('report_id'));
        } else {
            if ($request->filled('from_date') && $request->filled('to_date')) {
                if ($fromDate === $toDate) {
                    // Single day: include all reports created or dated that day
                    $ssmiQuery->where(function ($q) use ($fromDate) {
                        $q->whereDate('period_date', $fromDate)
                            ->orWhereDate('created_at', $fromDate)
                            ->orWhere('period_text', 'LIKE', "%{$fromDate}%")
                            ->orWhere('period_text', 'LIKE', '%'.Carbon::parse($fromDate)->format('m/d/Y').'%');
                    });
                } else {
                    $ssmiQuery->where(function ($q) use ($fromDate, $toDate) {
                        $q->where(function ($sub) use ($fromDate, $toDate) {
                            $sub->whereDate('period_date', '>=', $fromDate)
                                ->whereDate('period_date', '<=', $toDate);
                        })->orWhere(function ($sub) use ($fromDate, $toDate) {
                            $sub->whereDate('created_at', '>=', $fromDate)
                                ->whereDate('created_at', '<=', $toDate);
                        });
                    });
                }
            } elseif ($request->filled('from_date')) {
                $ssmiQuery->where(function ($q) use ($fromDate) {
                    $q->whereDate('period_date', '>=', $fromDate)
                        ->orWhereDate('created_at', '>=', $fromDate);
                });
            } elseif ($request->filled('to_date')) {
                $ssmiQuery->where(function ($q) use ($toDate) {
                    $q->whereDate('period_date', '<=', $toDate)
                        ->orWhereDate('created_at', '<=', $toDate);
                });
            } elseif ($request->filled('period_date')) {
                $pDate = $request->query('period_date');
                $ssmiQuery->where(function ($q) use ($pDate) {
                    $q->where('period_text', 'LIKE', "%{$pDate}%")
                        ->orWhereDate('period_date', $pDate);
                });
            }
        }

        $ssmiReports = $ssmiQuery->get();

        // Format period range text if date filter was supplied
        $periodRangeText = '';
        if ($fromDate && $toDate) {
            try {
                if ($fromDate === $toDate) {
                    $periodRangeText = Carbon::parse($fromDate)->format('m/d/Y');
                } else {
                    $periodRangeText = Carbon::parse($fromDate)->format('m/d/Y').' - '.Carbon::parse($toDate)->format('m/d/Y');
                }
            } catch (Throwable $e) {
                $periodRangeText = ($fromDate === $toDate) ? $fromDate : "{$fromDate} - {$toDate}";
            }
        } elseif ($fromDate) {
            try {
                $periodRangeText = 'From '.Carbon::parse($fromDate)->format('m/d/Y');
            } catch (Throwable $e) {
                $periodRangeText = "From {$fromDate}";
            }
        } elseif ($toDate) {
            try {
                $periodRangeText = 'Up to '.Carbon::parse($toDate)->format('m/d/Y');
            } catch (Throwable $e) {
                $periodRangeText = "Up to {$toDate}";
            }
        }

        // Build pages array. GAM Appendix 64 limit is 8 RIS columns per page.
        // If RIS columns exceed 8, chunk into new pages!
        $cleanCell = function ($val): string {
            if ($val === null || $val === 'null' || $val === '') {
                return '';
            }

            return (string) $val;
        };

        $pages = [];

        if ($ssmiReports->isEmpty()) {
            $pages[] = [
                'report_id' => null,
                'report_no' => 'SSMI-'.date('Y-m').'-0001',
                'period_text' => $periodRangeText ?: '',
                'ris_headers' => ['', '', '', '', '', '', '', ''],
                'items' => [],
                'prepared_by_name' => 'Raymond G. Amat',
                'prepared_by_designation' => 'AA-III Warehouse',
                'approved_by_name' => 'Joel Friginal',
                'approved_by_designation' => 'SAO',
            ];
        } else {
            foreach ($ssmiReports as $report) {
                $rawCols = is_array($report->ris_columns) ? $report->ris_columns : [];
                if (empty($rawCols)) {
                    $rawCols = ['', '', '', '', '', '', '', ''];
                }

                // Chunk columns in groups of 8
                $colChunks = array_chunk($rawCols, 8);

                foreach ($colChunks as $chunkIdx => $chunkHeaders) {
                    $paddedHeaders = [];
                    for ($h = 0; $h < 8; $h++) {
                        $hVal = $chunkHeaders[$h] ?? '';
                        $paddedHeaders[] = $cleanCell($hVal);
                    }

                    $colOffset = $chunkIdx * 8;
                    $pageItems = [];
                    $rowNum = 1;

                    foreach ($report->items as $item) {
                        $rawQtys = is_array($item->ris_quantities) ? $item->ris_quantities : [];
                        $sliceQtys = array_slice($rawQtys, $colOffset, 8);

                        $cleanQtys = [];
                        $chunkTotalQty = 0;
                        $hasAnyQtyInChunk = false;

                        for ($c = 0; $c < 8; $c++) {
                            $q = $sliceQtys[$c] ?? '';
                            if ($q !== null && $q !== 'null' && $q !== '' && (float) $q != 0) {
                                $cleanQtys[] = (float) $q;
                                $chunkTotalQty += (float) $q;
                                $hasAnyQtyInChunk = true;
                            } else {
                                $cleanQtys[] = '';
                            }
                        }

                        if (count($colChunks) > 1 && ! $hasAnyQtyInChunk) {
                            continue;
                        }

                        $unitCost = (float) $item->unit_cost;
                        $itemTotalQty = (count($colChunks) > 1) ? $chunkTotalQty : (float) $item->total_quantity;
                        $itemTotalCost = (count($colChunks) > 1) ? ($chunkTotalQty * $unitCost) : (float) $item->total_cost;

                        $pageItems[] = [
                            'no' => $rowNum++,
                            'stock_no' => $cleanCell($item->stock_no),
                            'description' => $cleanCell($item->description),
                            'unit' => $cleanCell($item->unit),
                            'ris_qtys' => $cleanQtys,
                            'total_qty' => $itemTotalQty > 0 ? $itemTotalQty : '',
                            'unit_cost' => $unitCost > 0 ? $unitCost : '',
                            'total_cost' => $itemTotalCost > 0 ? $itemTotalCost : '',
                        ];
                    }

                    $pText = $periodRangeText ?: ($report->period_text ?: ($report->period_date ? $report->period_date->format('m/d/Y') : ''));

                    $pages[] = [
                        'report_id' => $report->id,
                        'report_no' => $report->report_no,
                        'period_text' => $pText,
                        'ris_headers' => $paddedHeaders,
                        'items' => $pageItems,
                        'prepared_by_name' => $report->prepared_by_name ?: 'Raymond G. Amat',
                        'prepared_by_designation' => $report->prepared_by_designation ?: 'AA-III Warehouse',
                        'approved_by_name' => $report->approved_by_name ?: 'Joel Friginal',
                        'approved_by_designation' => $report->approved_by_designation ?: 'SAO',
                    ];
                }
            }
        }

        $totalPages = count($pages);
        $ssmiReport = $ssmiReports->first();

        return view('admin.report.ssmi-report', compact(
            'ssmiReports',
            'ssmiReport',
            'pages',
            'totalPages',
            'fromDate',
            'toDate',
            'periodRangeText'
        ));
    }

    /**
     * Save/finalize SSMI report to database.
     */
    public function saveSsmi(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'report_id' => ['nullable', 'exists:ssmi_reports,id'],
            'report_no' => ['nullable', 'string', 'max:100'],
            'period_text' => ['nullable', 'string', 'max:191'],
            'period_date' => ['nullable', 'date'],
            'office_id' => ['nullable', 'exists:offices,id'],
            'status' => ['nullable', 'string', Rule::in(['draft', 'finalized', 'submitted', 'archived'])],
            'prepared_by_name' => ['nullable', 'string', 'max:191'],
            'prepared_by_designation' => ['nullable', 'string', 'max:191'],
            'approved_by_name' => ['nullable', 'string', 'max:191'],
            'approved_by_designation' => ['nullable', 'string', 'max:191'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'ris_columns' => ['nullable', 'array'],
            'ris_columns.*' => ['nullable', 'string', 'max:50'],
            'items' => ['nullable', 'array'],
            'items.*.row_order' => ['nullable', 'integer'],
            'items.*.stock_no' => ['nullable', 'string', 'max:100'],
            'items.*.description' => ['nullable', 'string', 'max:1000'],
            'items.*.unit' => ['nullable', 'string', 'max:50'],
            'items.*.ris_quantities' => ['nullable', 'array'],
            'items.*.total_quantity' => ['nullable', 'numeric'],
            'items.*.unit_cost' => ['nullable', 'numeric'],
            'items.*.total_cost' => ['nullable', 'numeric'],
        ]);

        $report = DB::transaction(function () use ($validated) {
            $report = null;

            if (! empty($validated['report_id'])) {
                $report = SsmiReport::findOrFail($validated['report_id']);
            }

            $reportData = [
                'period_text' => $validated['period_text'] ?? null,
                'period_date' => ! empty($validated['period_date']) ? rescue(fn () => Carbon::parse($validated['period_date'])->toDateString(), null, false) : null,
                'office_id' => $validated['office_id'] ?? null,
                'user_id' => auth()->id(),
                'ris_columns' => $validated['ris_columns'] ?? array_fill(0, 8, ''),
                'status' => $validated['status'] ?? 'draft',
                'prepared_by_name' => $validated['prepared_by_name'] ?? 'Raymond G. Amat',
                'prepared_by_designation' => $validated['prepared_by_designation'] ?? 'AA-III Warehouse',
                'approved_by_name' => $validated['approved_by_name'] ?? 'Joel Friginal',
                'approved_by_designation' => $validated['approved_by_designation'] ?? 'SAO',
                'remarks' => $validated['remarks'] ?? null,
            ];

            if (! $report) {
                $reportData['report_no'] = ! empty($validated['report_no'])
                    ? trim($validated['report_no'])
                    : SsmiReport::generateReportNo();
                $report = SsmiReport::create($reportData);
            } else {
                $report->update($reportData);
                $report->items()->delete();
            }

            $grandTotal = 0;
            $rowIdx = 1;

            if (! empty($validated['items']) && is_array($validated['items'])) {
                foreach ($validated['items'] as $itemData) {
                    $desc = trim($itemData['description'] ?? '');
                    $stockNo = trim($itemData['stock_no'] ?? '');
                    $totalQty = (float) ($itemData['total_quantity'] ?? 0);
                    $unitCost = (float) ($itemData['unit_cost'] ?? 0);
                    $totalCost = (float) ($itemData['total_cost'] ?? ($totalQty * $unitCost));

                    // Only persist rows that have content or quantity
                    $hasContent = $desc !== '' || $stockNo !== '' || $totalQty > 0 || $totalCost > 0;
                    if (! $hasContent) {
                        $rowIdx++;

                        continue;
                    }

                    $grandTotal += $totalCost;

                    SsmiReportItem::create([
                        'ssmi_report_id' => $report->id,
                        'row_order' => $itemData['row_order'] ?? $rowIdx++,
                        'stock_no' => $stockNo ?: null,
                        'description' => $desc ?: null,
                        'unit' => trim($itemData['unit'] ?? '') ?: null,
                        'ris_quantities' => $itemData['ris_quantities'] ?? array_fill(0, 8, ''),
                        'total_quantity' => $totalQty,
                        'unit_cost' => $unitCost,
                        'total_cost' => $totalCost,
                    ]);
                }
            }

            $report->update(['total_amount' => $grandTotal]);

            return $report;
        });

        return response()->json([
            'success' => true,
            'message' => 'SSMI report saved to database successfully!',
            'report_id' => $report->id,
            'report_no' => $report->report_no,
            'status' => $report->status,
            'total_amount' => $report->total_amount,
        ]);
    }

    /**
     * Delete SSMI report.
     */
    public function destroySsmi(Request $request, SsmiReport $report): JsonResponse|RedirectResponse
    {
        $reportNo = $report->report_no;
        $report->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Report {$reportNo} deleted successfully.",
            ]);
        }

        return redirect()->route('admin.reports.ssmi')->with('status', "Report {$reportNo} deleted.");
    }
}
