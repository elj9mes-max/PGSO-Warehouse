<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InventoryLogAction;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\InventoryBatch;
use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use App\Models\InventoryLog;
use App\Models\Office;
use App\Models\OfficeInventory;
use App\Models\StockCard;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    /**
     * Display inventory items and variants list.
     */
    public function index(): View
    {
        $variants = InventoryItemVariant::with('categoryModel')
            ->where('is_for_inspection', false)
            ->latest()
            ->get();
        $items = $variants;
        $categories = Category::orderBy('name')->pluck('name');

        return view('admin.inventory', compact('variants', 'items', 'categories'));
    }

    /**
     * Store a new category.
     */
    public function storeCategory(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        $category = Category::create([
            'name' => trim($validated['name']),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Category created successfully.',
                'category' => $category,
            ], 201);
        }

        return redirect()->back()->with('status', "Category '{$category->name}' created successfully.");
    }

    /**
     * Store a newly created inventory supply item.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_code' => ['required', 'string', 'max:255', 'unique:inventory_item_variants,item_code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'reorder_level' => ['nullable', 'integer', 'min:0'],
            'unit_cost' => ['nullable', 'numeric', 'min:0'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        Category::firstOrCreate(['name' => trim($validated['category'])]);

        $quantity = (int) ($validated['quantity'] ?? 0);
        $reorderLevel = (int) ($validated['reorder_level'] ?? 10);
        $unitCost = (float) ($validated['unit_cost'] ?? 0.00);

        $status = $quantity <= 0
            ? 'Out of Stock'
            : ($quantity <= $reorderLevel ? 'Low Stock' : 'In Stock');

        $cleanCode = preg_replace('/[^A-Za-z0-9]/', '', (string) ($validated['item_code']));
        $barcodeCandidate = 'BAR-'.$cleanCode;
        if (InventoryItemVariant::where('barcode', $barcodeCandidate)->exists()) {
            $barcodeCandidate = 'BAR-'.$cleanCode.'-'.uniqid();
        }

        $variant = InventoryItemVariant::create([
            'item_code' => $validated['item_code'],
            'variant_name' => $validated['name'],
            'category' => $validated['category'],
            'barcode' => $barcodeCandidate,
            'sku' => $validated['item_code'],
            'variant_description' => $validated['description'] ?? null,
            'quantity' => $quantity,
            'available_for_transfer' => $quantity,
            'reorder_level' => $reorderLevel,
            'unit_cost' => $unitCost,
            'unit' => $validated['unit'],
            'location' => $validated['location'] ?? null,
            'status' => $status,
            'is_for_inspection' => false,
        ]);

        $variant->update(['inventory_item_id' => $variant->id]);

        StockCard::initializeForVariant($variant);

        return redirect()->route('admin.inventory')->with('status', 'Inventory item added successfully!');
    }

    /**
     * Display purchase order receiving form.
     */
    public function purchaseRequest(): View
    {
        $variants = InventoryItemVariant::with('inventoryItem')
            ->where('is_for_inspection', false)
            ->orderBy('variant_name')
            ->get();

        $items = $variants;
        $offices = Office::orderBy('office_name')->get();

        $recentBatches = InventoryBatch::with(['variant', 'office'])
            ->latest()
            ->take(15)
            ->get();

        return view('admin.inventory.insert-purchase-request', compact('variants', 'items', 'offices', 'recentBatches'));
    }

    /**
     * Redirect insert purchase request to purchase request form.
     */
    public function redirectInsertPurchaseRequest(): RedirectResponse
    {
        return redirect()->route('admin.inventory.purchase-request');
    }

    /**
     * Display Inspection and Acceptance Report (IAR) form.
     */
    public function iarForm(Request $request): View
    {
        $poNumber = $request->query('po');
        $batchId = $request->query('batch');
        $supplier = $request->query('supplier', '');
        $poDate = $request->query('po_date', '');
        $office = $request->query('office', '');
        $airNumber = $request->query('air');
        $invoiceNumber = $request->query('invoice');
        $autoPrint = $request->boolean('print') || $request->boolean('autoprint');
        $items = [];

        // If neither po nor batch is explicitly supplied, fall back to the most recent PO or batch
        if (! $batchId && ! $poNumber) {
            $latestBatch = InventoryBatch::with(['inventoryItem', 'variant', 'office'])
                ->latest('id')
                ->first();
            if ($latestBatch) {
                if ($latestBatch->po_number) {
                    $poNumber = $latestBatch->po_number;
                } else {
                    $batchId = $latestBatch->id;
                }
            }
        }

        if ($batchId) {
            $batch = InventoryBatch::with(['inventoryItem', 'variant', 'office'])->find($batchId);
            if ($batch) {
                $supplier = $batch->supplier ?? $supplier;
                $poNumber = $batch->po_number ?? $poNumber;
                $airNumber = $airNumber ?: $batch->air_number;
                $office = $batch->office?->office_name ?? ($batch->office_id ? $office : 'Provincial General Services Office (PGSO)');
                $poDate = $batch->delivery_date ? $batch->delivery_date->format('m/d/Y') : ($batch->created_at ? $batch->created_at->format('m/d/Y') : '');
                $batchItemName = $batch->inventoryItem?->name ?: ($batch->variant?->inventoryItem?->name ?: null);
                $batchDesc = $batchItemName
                    ? ($batchItemName.($batch->variant?->variant_name ? ' - '.$batch->variant->variant_name : ''))
                    : ($batch->variant?->variant_name ?? 'Supply Item');
                $items[] = [
                    'stock_no' => $batch->variant?->sku ?? $batch->variant?->barcode ?? 'STK-'.str_pad((string) $batch->id, 4, '0', STR_PAD_LEFT),
                    'description' => $batchDesc,
                    'unit' => $batch->variant?->effective_unit ?? 'pcs',
                    'quantity' => $batch->quantity_received ?? 1,
                ];
            }
        } elseif ($poNumber) {
            $batches = InventoryBatch::with(['inventoryItem', 'variant', 'office'])
                ->where('po_number', $poNumber)
                ->get();
            if ($batches->isNotEmpty()) {
                $first = $batches->first();
                $supplier = $first->supplier ?? $supplier;
                $airNumber = $airNumber ?: $batches->firstWhere(fn ($b) => ! empty($b->air_number))?->air_number;
                $office = $first->office?->office_name ?? ($first->office_id ? $office : 'Provincial General Services Office (PGSO)');
                $poDate = $first->delivery_date ? $first->delivery_date->format('m/d/Y') : ($first->created_at ? $first->created_at->format('m/d/Y') : '');
                foreach ($batches as $b) {
                    $bItemName = $b->inventoryItem?->name ?: ($b->variant?->inventoryItem?->name ?: null);
                    $bDesc = $bItemName
                        ? ($bItemName.($b->variant?->variant_name ? ' - '.$b->variant->variant_name : ''))
                        : ($b->variant?->variant_name ?? 'Supply Item');
                    $items[] = [
                        'stock_no' => $b->variant?->sku ?? $b->variant?->barcode ?? 'STK-'.str_pad((string) $b->id, 4, '0', STR_PAD_LEFT),
                        'description' => $bDesc,
                        'unit' => $b->variant?->effective_unit ?? 'pcs',
                        'quantity' => $b->quantity_received ?? 1,
                    ];
                }
            }
        }

        if (! $airNumber && $poNumber) {
            $cleanPoNum = preg_replace('/[^0-9]/', '', (string) $poNumber);
            $airNumber = 'AIR-'.now()->format('Y').'-'.($cleanPoNum ? substr($cleanPoNum, -4) : '0001');
        }

        // Persist the generated AIR number into the database for all matching batches
        if ($airNumber) {
            if ($batchId) {
                InventoryBatch::where('id', $batchId)
                    ->where(function ($q) {
                        $q->whereNull('air_number')->orWhere('air_number', '');
                    })
                    ->update(['air_number' => $airNumber]);
            }
            if ($poNumber) {
                InventoryBatch::where('po_number', $poNumber)
                    ->where(function ($q) {
                        $q->whereNull('air_number')->orWhere('air_number', '');
                    })
                    ->update(['air_number' => $airNumber]);
            }
        }

        return view('admin.inventory.iar-form', compact('poNumber', 'batchId', 'supplier', 'poDate', 'office', 'items', 'airNumber', 'invoiceNumber', 'autoPrint'));
    }

    /**
     * Save an AIR number to batches.
     */
    public function saveAir(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'air_number' => ['required', 'string', 'max:64'],
            'po_number' => ['nullable', 'string', 'max:64'],
            'batch_id' => ['nullable', 'integer'],
        ]);

        $airNumber = $validated['air_number'];
        $updated = 0;

        if (! empty($validated['batch_id'])) {
            $updated += InventoryBatch::where('id', $validated['batch_id'])->update(['air_number' => $airNumber]);
        }
        if (! empty($validated['po_number'])) {
            $updated += InventoryBatch::where('po_number', $validated['po_number'])->update(['air_number' => $airNumber]);
        }

        return response()->json([
            'success' => true,
            'message' => 'AIR number saved to database successfully.',
            'air_number' => $airNumber,
            'records_updated' => $updated,
        ]);
    }

    /**
     * Store received purchase request / supply delivery.
     */
    public function storePurchaseRequest(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'po_number' => ['nullable', 'string', 'max:64'],
            'air_number' => ['nullable', 'string', 'max:64'],
            'pr_number' => ['nullable', 'string', 'max:64'],
            'supplier' => ['nullable', 'string', 'max:191'],
            'delivery_date' => ['nullable', 'date'],
            'office_id' => ['nullable', 'exists:offices,id'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'invoice_number' => ['nullable', 'string', 'max:64'],
            'generate_iar' => ['nullable', 'boolean'],
            'auto_print' => ['nullable', 'boolean'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.variant_id' => ['required', 'exists:inventory_item_variants,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
            'items.*.remarks' => ['nullable', 'string', 'max:255'],
        ]);

        $destinationOfficeId = ! empty($validated['office_id']) ? (int) $validated['office_id'] : null;
        $destinationOffice = $destinationOfficeId ? Office::findOrFail($destinationOfficeId) : null;
        $destinationName = $destinationOffice ? $destinationOffice->office_name : 'PGSO Central Warehouse';

        $deliveryDate = $validated['delivery_date'] ?? now()->toDateString();
        $poNumber = ! empty($validated['po_number']) ? $validated['po_number'] : ('PO-'.now()->format('Y').'-'.rand(1000, 9999));
        $cleanPoNum = preg_replace('/[^0-9]/', '', (string) $poNumber);
        $airNumber = ! empty($validated['air_number'])
            ? $validated['air_number']
            : ('AIR-'.now()->format('Y').'-'.($cleanPoNum ? substr($cleanPoNum, -4) : rand(1000, 9999)));
        $prNumber = $validated['pr_number'] ?? null;
        $supplier = $validated['supplier'] ?? null;

        $totalReceivedUnits = 0;
        $batchesCreated = 0;

        DB::transaction(function () use (
            $validated,
            $destinationOfficeId,
            $destinationOffice,
            $destinationName,
            $deliveryDate,
            $poNumber,
            $airNumber,
            $prNumber,
            $supplier,
            $request,
            &$totalReceivedUnits,
            &$batchesCreated
        ) {
            foreach ($validated['items'] as $itemData) {
                $variant = InventoryItemVariant::lockForUpdate()->findOrFail($itemData['variant_id']);
                $inventoryItem = $variant->inventoryItem;
                $receivedQty = (int) $itemData['quantity'];
                $newUnitCost = (float) $itemData['unit_cost'];
                $oldUnitCost = (float) ($variant->unit_cost ?? 0.00);
                $isDifferentPrice = abs($newUnitCost - $oldUnitCost) > 0.009;
                $quantityBefore = $variant->quantity;

                // Update variant quantity and unit cost directly
                $variant->quantity = $quantityBefore + $receivedQty;
                $variant->unit_cost = $newUnitCost;

                if ($destinationOfficeId === null) {
                    $variant->available_for_transfer = (int) $variant->available_for_transfer + $receivedQty;
                }

                $variant->status = $variant->quantity <= 0
                    ? 'Out of Stock'
                    : ($variant->quantity <= $variant->reorder_level ? 'Low Stock' : 'In Stock');
                $variant->save();

                if ($destinationOfficeId !== null) {
                    $officeInventory = OfficeInventory::firstOrNew([
                        'office_id' => $destinationOfficeId,
                        'inventory_item_variants_id' => $variant->id,
                    ]);
                    $officeInventory->quantity = ($officeInventory->exists ? (int) $officeInventory->quantity : 0) + $receivedQty;
                    $officeInventory->save();
                }

                // Generate batch record
                $batchNumber = 'BATCH-'.now()->format('Ymd').'-'.str_pad((string) ($variant->id), 3, '0', STR_PAD_LEFT).'-'.strtoupper(Str::random(4));
                InventoryBatch::create([
                    'batch_number' => $batchNumber,
                    'inventory_item_id' => $variant->inventory_item_id,
                    'inventory_item_variant_id' => $variant->id,
                    'office_id' => $destinationOfficeId,
                    'po_number' => $poNumber,
                    'air_number' => $airNumber,
                    'pr_number' => $prNumber,
                    'supplier' => $supplier,
                    'delivery_date' => $deliveryDate,
                    'unit_cost' => $newUnitCost,
                    'quantity_received' => $receivedQty,
                    'quantity_remaining' => $receivedQty,
                    'status' => 'active',
                    'remarks' => $itemData['remarks'] ?? ($validated['remarks'] ?? null),
                ]);

                // Recalculate parent item total stock and status
                if ($inventoryItem) {
                    $totalStock = (int) $inventoryItem->variants()->sum('quantity');
                    $parentStatus = $totalStock <= 0
                        ? 'Out of Stock'
                        : ($totalStock <= $inventoryItem->reorder_level ? 'Low Stock' : 'In Stock');
                    $inventoryItem->update([
                        'quantity' => $totalStock,
                        'status' => $parentStatus,
                    ]);
                }

                // Audit Log
                $remarksNote = $isDifferentPrice
                    ? 'Price updated to ₱'.number_format($newUnitCost, 2).' (prior ₱'.number_format($oldUnitCost, 2).'). '
                    : "Received via PO {$poNumber}. ";
                if (! empty($itemData['remarks'])) {
                    $remarksNote .= $itemData['remarks'];
                }

                InventoryLog::create([
                    'action_type' => InventoryLogAction::InventoryAdded,
                    'batch_id' => $batchNumber,
                    'reference_number' => $poNumber,
                    'user_id' => auth()->id(),
                    'office_id' => $destinationOfficeId,
                    'inventory_item_id' => null,
                    'inventory_item_variant_id' => $variant->id,
                    'office_name' => $destinationName,
                    'item_name' => $variant->variant_name,
                    'variant_name' => $variant->variant_name,
                    'quantity' => $receivedQty,
                    'quantity_before' => $quantityBefore,
                    'quantity_after' => $variant->quantity,
                    'recipient' => $destinationName,
                    'purpose' => 'Purchase Order Delivery',
                    'old_values' => [
                        'unit_cost' => $oldUnitCost,
                        'quantity' => $quantityBefore,
                    ],
                    'new_values' => [
                        'unit_cost' => $newUnitCost,
                        'batch_number' => $batchNumber,
                        'po_number' => $poNumber,
                        'air_number' => $airNumber,
                        'pr_number' => $prNumber,
                        'supplier' => $supplier,
                        'price_differentiated' => $isDifferentPrice,
                        'destination' => $destinationName,
                    ],
                    'remarks' => trim($remarksNote),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                $totalReceivedUnits += $receivedQty;
                $batchesCreated++;

                // Record inbound entry into Stock Card table
                StockCard::recordReceipt(
                    variant: $variant,
                    quantity: $receivedQty,
                    poNumber: $poNumber,
                    officeId: $destinationOfficeId,
                    officeName: $destinationOffice?->office_name,
                    deliveryDate: $deliveryDate,
                    remarks: null,
                    userId: auth()->id()
                );
            }
        });

        $statusMsg = "Successfully received {$totalReceivedUnits} units across {$batchesCreated} supply variant(s) into {$destinationName} under {$poNumber}!";

        $shouldGenerateIar = $request->boolean('generate_iar', false);
        $autoPrint = $request->boolean('auto_print', false);
        $invoiceNumber = $validated['invoice_number'] ?? null;

        if ($shouldGenerateIar) {
            return redirect()->route('admin.inventory.iar-form', array_filter([
                'po' => $poNumber,
                'air' => ! empty($validated['air_number']) ? $validated['air_number'] : null,
                'print' => $autoPrint ? 1 : null,
                'invoice' => $invoiceNumber,
            ]))
                ->with('status', $statusMsg)
                ->with('received_po', $poNumber)
                ->with('received_destination', $destinationName)
                ->with('received_supplier', $supplier)
                ->with('received_units', $totalReceivedUnits)
                ->with('received_batches', $batchesCreated);
        }

        return redirect()->route('admin.inventory.purchase-request')
            ->with('status', $statusMsg)
            ->with('received_po', $poNumber)
            ->with('received_destination', $destinationName)
            ->with('received_supplier', $supplier)
            ->with('received_units', $totalReceivedUnits)
            ->with('received_batches', $batchesCreated);
    }

    /**
     * Display the specified inventory item and its variants.
     */
    public function show(InventoryItem $inventoryItem): View
    {
        $inventoryItem->load([
            'variants.batches' => function ($query) {
                $query->latest();
            },
            'variants.batches.office',
        ]);
        $otherItems = InventoryItem::where('id', '!=', $inventoryItem->id)
            ->with('variants')
            ->latest()
            ->take(6)
            ->get();

        return view('admin.inventory.show', compact('inventoryItem', 'otherItems'));
    }

    /**
     * Store a new variant for an inventory item.
     */
    public function storeVariant(Request $request, InventoryItem $inventoryItem): RedirectResponse
    {
        $validated = $request->validate([
            'variant_name' => ['required', 'string', 'max:255'],
            'barcode' => ['required', 'string', 'max:255', 'unique:inventory_item_variants,barcode'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:inventory_item_variants,sku'],
            'variant_description' => ['nullable', 'string'],
            'unit' => ['nullable', 'string', 'max:50'],
            'quantity' => ['required', 'integer', 'min:0'],
            'reorder_level' => ['nullable', 'integer', 'min:0'],
            'unit_cost' => ['nullable', 'numeric', 'min:0'],
            'location' => ['nullable', 'string', 'max:255'],
            'is_for_inspection' => ['nullable', 'boolean'],
        ]);

        $reorderLevel = (int) ($validated['reorder_level'] ?? ($inventoryItem->reorder_level ?? 10));

        $status = $validated['quantity'] <= 0
            ? 'Out of Stock'
            : ($validated['quantity'] <= $reorderLevel ? 'Low Stock' : 'In Stock');

        $isForInspection = $request->has('is_for_inspection')
            ? $request->boolean('is_for_inspection')
            : true;

        $variant = $inventoryItem->variants()->create(array_merge($validated, [
            'reorder_level' => $reorderLevel,
            'available_for_transfer' => $validated['quantity'],
            'unit' => ! empty($validated['unit']) ? $validated['unit'] : $inventoryItem->unit,
            'unit_cost' => isset($validated['unit_cost']) && $validated['unit_cost'] !== ''
                ? $validated['unit_cost']
                : ($inventoryItem->unit_cost ?? 0.00),
            'location' => $validated['location'] ?? null,
            'status' => $status,
            'is_for_inspection' => $isForInspection,
        ]));

        // Update parent item stock and status
        $totalStock = (int) $inventoryItem->variants()->sum('quantity');
        $parentStatus = $totalStock <= 0
            ? 'Out of Stock'
            : ($totalStock <= $inventoryItem->reorder_level ? 'Low Stock' : 'In Stock');

        $inventoryItem->update([
            'quantity' => $totalStock,
            'status' => $parentStatus,
        ]);

        $identifier = $variant->sku ?: $variant->barcode;

        StockCard::initializeForVariant($variant);

        return redirect()->route('admin.inventory.show', $inventoryItem)
            ->with('status', "Variant '{$variant->variant_name}' ({$identifier}) added successfully!");
    }

    /**
     * Update an existing inventory item variant.
     */
    public function updateVariant(Request $request, InventoryItem $inventoryItem, InventoryItemVariant $variant): RedirectResponse
    {
        abort_unless($variant->inventory_item_id === $inventoryItem->id || $variant->id === $inventoryItem->id, 404);

        $validated = $request->validate([
            'variant_name' => ['required', 'string', 'max:255'],
            'barcode' => ['required', 'string', 'max:255', Rule::unique('inventory_item_variants', 'barcode')->ignore($variant->id)],
            'sku' => ['nullable', 'string', 'max:255', Rule::unique('inventory_item_variants', 'sku')->ignore($variant->id)],
            'variant_description' => ['nullable', 'string'],
            'unit' => ['nullable', 'string', 'max:50'],
            'quantity' => ['required', 'integer', 'min:0'],
            'reorder_level' => ['nullable', 'integer', 'min:0'],
            'unit_cost' => ['nullable', 'numeric', 'min:0'],
            'location' => ['nullable', 'string', 'max:255'],
            'is_for_inspection' => ['nullable', 'boolean'],
        ]);

        $reorderLevel = isset($validated['reorder_level']) && $validated['reorder_level'] !== null
            ? (int) $validated['reorder_level']
            : (int) ($variant->reorder_level ?? 10);

        $status = $validated['quantity'] <= 0
            ? 'Out of Stock'
            : ($validated['quantity'] <= $reorderLevel ? 'Low Stock' : 'In Stock');

        $isForInspection = $request->has('is_for_inspection')
            ? $request->boolean('is_for_inspection')
            : $variant->is_for_inspection;

        $variant->update(array_merge($validated, [
            'reorder_level' => $reorderLevel,
            'unit' => ! empty($validated['unit']) ? $validated['unit'] : ($variant->unit ?: $inventoryItem->unit),
            'unit_cost' => isset($validated['unit_cost']) && $validated['unit_cost'] !== ''
                ? $validated['unit_cost']
                : ($variant->unit_cost ?? $inventoryItem->unit_cost ?? 0.00),
            'location' => $validated['location'] ?? null,
            'status' => $status,
            'is_for_inspection' => $isForInspection,
        ]));

        // Update parent item stock and status
        $totalStock = (int) $inventoryItem->variants()->sum('quantity');
        $parentStatus = $totalStock <= 0
            ? 'Out of Stock'
            : ($totalStock <= $inventoryItem->reorder_level ? 'Low Stock' : 'In Stock');

        $inventoryItem->update([
            'quantity' => $totalStock,
            'status' => $parentStatus,
        ]);

        $identifier = $variant->sku ?: $variant->barcode;

        return redirect()->route('admin.inventory.show', $inventoryItem)
            ->with('status', "Variant '{$variant->variant_name}' ({$identifier}) updated successfully!");
    }

    /**
     * Update inspection status for a variant.
     */
    public function updateVariantInspection(Request $request, InventoryItem $inventoryItem, InventoryItemVariant $variant): RedirectResponse
    {
        abort_unless($variant->inventory_item_id === $inventoryItem->id || $variant->id === $inventoryItem->id, 404);

        $isForInspection = $request->has('is_for_inspection')
            ? $request->boolean('is_for_inspection')
            : false;

        $variant->update([
            'is_for_inspection' => $isForInspection,
        ]);

        // Update parent item stock and status
        $totalStock = (int) $inventoryItem->variants()->where('is_for_inspection', false)->sum('quantity');
        $parentStatus = $totalStock <= 0
            ? 'Out of Stock'
            : ($totalStock <= $inventoryItem->reorder_level ? 'Low Stock' : 'In Stock');

        $inventoryItem->update([
            'quantity' => $totalStock,
            'status' => $parentStatus,
        ]);

        $statusText = $isForInspection ? 'marked as pending inspection' : 'inspected and released to active inventory';

        return redirect()->route('admin.inventory.show', $inventoryItem)
            ->with('status', "Variant '{$variant->variant_name}' {$statusText} successfully!");
    }

    /**
     * Delete an inventory variant.
     */
    public function destroyVariant(InventoryItem $inventoryItem, InventoryItemVariant $variant): RedirectResponse
    {
        abort_unless($variant->inventory_item_id === $inventoryItem->id || $variant->id === $inventoryItem->id, 404);

        $name = $variant->variant_name;
        $variant->delete();

        // Recalculate parent item total stock and status
        $totalStock = (int) $inventoryItem->variants()->sum('quantity');
        $parentStatus = $totalStock <= 0
            ? 'Out of Stock'
            : ($totalStock <= $inventoryItem->reorder_level ? 'Low Stock' : 'In Stock');

        $inventoryItem->update([
            'quantity' => $totalStock,
            'status' => $parentStatus,
        ]);

        return redirect()->route('admin.inventory.show', $inventoryItem)
            ->with('status', "Variant '{$name}' deleted successfully.");
    }
}
