<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InventoryLogAction;
use App\Http\Controllers\Controller;
use App\Models\InventoryItemVariant;
use App\Models\InventoryLog;
use App\Models\Office;
use App\Models\OfficeInventory;
use App\Models\PurchaseRequest;
use App\Models\RequisitionRequest;
use App\Models\SsmiReport;
use App\Models\StockCard;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RequisitionController extends Controller
{
    /**
     * Display requisitions management & review listing.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status');
        $typeFilter = $request->query('type');
        $officeFilter = $request->query('office');
        $search = $request->query('search');

        $query = RequisitionRequest::with([
            'office',
            'user',
            'items.variant.inventoryItem',
            'items.variant.officeInventories',
            'approvedBy',
            'issuedBy',
        ]);

        if (! empty($statusFilter) && $statusFilter !== 'all') {
            if ($statusFilter === 'approved') {
                $query->whereIn('status', ['approved', 'approved_with_adjustments']);
            } else {
                $query->where('status', $statusFilter);
            }
        }

        if (! empty($typeFilter) && $typeFilter !== 'all') {
            $query->where('request_type', $typeFilter);
        }

        if (! empty($officeFilter) && $officeFilter !== 'all') {
            $query->where('office_id', $officeFilter);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('request_number', 'LIKE', "%{$search}%")
                    ->orWhere('ris_no', 'LIKE', "%{$search}%")
                    ->orWhere('purpose', 'LIKE', "%{$search}%")
                    ->orWhere('requested_by_name', 'LIKE', "%{$search}%")
                    ->orWhereHas('office', function ($oq) use ($search) {
                        $oq->where('office_name', 'LIKE', "%{$search}%")
                            ->orWhere('office_code', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('items', function ($iq) use ($search) {
                        $iq->where('item_name', 'LIKE', "%{$search}%")
                            ->orWhere('variant_name', 'LIKE', "%{$search}%")
                            ->orWhere('stock_no', 'LIKE', "%{$search}%");
                    });
            });
        }

        $requests = $query->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->get();

        $totalCount = (int) RequisitionRequest::count();
        $pendingCount = (int) RequisitionRequest::where('status', 'pending')->count();
        $approvedCount = (int) RequisitionRequest::whereIn('status', ['approved', 'approved_with_adjustments'])->count();
        $issuedCount = (int) RequisitionRequest::where('status', 'issued')->count();
        $rejectedCount = (int) RequisitionRequest::where('status', 'rejected')->count();

        $offices = Office::orderBy('office_name')->get();

        return view('admin.requisition', compact(
            'requests',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'issuedCount',
            'rejectedCount',
            'offices',
            'statusFilter',
            'typeFilter',
            'officeFilter',
            'search'
        ));
    }

    /**
     * Redirect singular /requisition to plural requisitions index.
     */
    public function redirectRequisition(): RedirectResponse
    {
        return redirect()->route('admin.requisitions.index');
    }

    /**
     * Review, approve, adjust, issue, or reject a requisition request.
     */
    public function review(Request $request, RequisitionRequest $requisition): RedirectResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'string', Rule::in(['approve', 'issue', 'reject'])],
            'items' => ['nullable', 'array'],
            'items.*.id' => ['required_with:items', 'exists:requisition_request_items,id'],
            'items.*.quantity' => ['required_with:items', 'integer', 'min:0'],
            'items.*.remarks' => ['nullable', 'string', 'max:255'],
            'reason' => ['nullable', 'string', 'max:1000'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'approved_by_name' => ['nullable', 'string', 'max:191'],
            'approved_by_designation' => ['nullable', 'string', 'max:191'],
            'issued_by_name' => ['nullable', 'string', 'max:191'],
            'issued_by_designation' => ['nullable', 'string', 'max:191'],
        ]);

        // Prevent double-processing
        if ($requisition->status !== 'pending') {
            return redirect()->route('admin.requisitions.index')
                ->with('error', "Requisition #{$requisition->request_number} has already been reviewed.");
        }

        // Handle Rejection
        if ($validated['action'] === 'reject') {
            if (empty(trim($validated['reason'] ?? ''))) {
                throw ValidationException::withMessages([
                    'reason' => 'Please provide a justification reason why this requisition request is being rejected.',
                ]);
            }

            $reasonText = trim($validated['reason']);

            $requisition->update([
                'status' => 'rejected',
                'rejection_reason' => $reasonText,
                'remarks' => ! empty($validated['remarks']) ? trim($validated['remarks']) : $reasonText,
                'approved_by_id' => auth()->id(),
                'approved_by_name' => $validated['approved_by_name'] ?? (auth()->user()->full_name ?? auth()->user()->name),
                'approved_by_designation' => $validated['approved_by_designation'] ?? 'PGDH - PGSO',
                'approved_at' => now(),
            ]);

            $requisition->items()->update([
                'status' => 'rejected',
            ]);

            return redirect()->route('admin.requisitions.index')
                ->with('status', "Requisition #{$requisition->request_number} has been rejected with recorded reason.");
        }

        // Handle Approval or Issuance
        $requisition->load(['items.variant.inventoryItem', 'office', 'user']);
        $itemsInput = collect($validated['items'] ?? [])->keyBy('id');
        $hasQuantityReduction = false;
        $totalApprovedUnits = 0;

        foreach ($requisition->items as $item) {
            $approvedQty = isset($itemsInput[$item->id])
                ? (int) $itemsInput[$item->id]['quantity']
                : $item->quantity_requested;

            if ($approvedQty < $item->quantity_requested) {
                $hasQuantityReduction = true;
            } elseif ($approvedQty > $item->quantity_requested) {
                throw ValidationException::withMessages([
                    'items' => "Approved quantity for '{$item->item_name}' cannot exceed requested quantity ({$item->quantity_requested}).",
                ]);
            }
        }

        // If any item quantity was reduced, reason is strictly required!
        if ($hasQuantityReduction && empty(trim($validated['reason'] ?? ''))) {
            throw ValidationException::withMessages([
                'reason' => 'A justification reason is required when reducing or cutting requested quantities.',
            ]);
        }

        $reasonText = ! empty($validated['reason']) ? trim($validated['reason']) : null;
        $batchId = 'REL-'.strtoupper(Str::random(6)).'-'.date('Ymd');
        $refNumber = $requisition->ris_no ?: $requisition->request_number;

        DB::transaction(function () use (
            $requisition,
            $validated,
            $itemsInput,
            $hasQuantityReduction,
            $reasonText,
            $batchId,
            $refNumber,
            $request,
            &$totalApprovedUnits
        ) {
            // Update each requested item and reduce stock from office inventory & main inventory
            foreach ($requisition->items as $item) {
                $itemData = $itemsInput->get($item->id);
                $approvedQty = $itemData ? (int) $itemData['quantity'] : $item->quantity_requested;
                $totalApprovedUnits += $approvedQty;

                $itemStatus = $approvedQty === 0
                    ? 'rejected'
                    : ($approvedQty < $item->quantity_requested ? 'adjusted' : ($validated['action'] === 'issue' ? 'issued' : 'approved'));

                $itemRemarks = ! empty($itemData['remarks']) ? trim($itemData['remarks']) : null;
                if ($approvedQty < $item->quantity_requested && empty($itemRemarks)) {
                    $itemRemarks = "Reduced from {$item->quantity_requested} to {$approvedQty}".($reasonText ? ": {$reasonText}" : '');
                }

                $item->update([
                    'quantity_issued' => $approvedQty,
                    'status' => $itemStatus,
                    'remarks' => $itemRemarks,
                ]);

                // When stock is approved (> 0), reduce quantity in office inventory and main inventory (released from inventory)
                if ($approvedQty > 0) {
                    $variant = $item->variant ?: ($item->inventory_item_variant_id ? InventoryItemVariant::with('inventoryItem')->lockForUpdate()->find($item->inventory_item_variant_id) : null);
                    if (! $variant && $item->stock_no) {
                        $variant = InventoryItemVariant::with('inventoryItem')->where('sku', $item->stock_no)->lockForUpdate()->first();
                    }

                    if ($variant) {
                        if ($variant->quantity < $approvedQty) {
                            throw ValidationException::withMessages([
                                'items' => "Cannot issue {$approvedQty} units of '{$variant->variant_name}'. Only {$variant->quantity} units are available in warehouse stock.",
                            ]);
                        }

                        // 1. Deduct from office inventory custody if present
                        $officeInventory = OfficeInventory::where('office_id', $requisition->office_id)
                            ->where('inventory_item_variants_id', $variant->id)
                            ->lockForUpdate()
                            ->first();

                        $prevOfficeQty = $officeInventory?->quantity ?? 0;

                        if ($officeInventory) {
                            if ($officeInventory->quantity - $approvedQty <= 0) {
                                $officeInventory->delete();
                            } else {
                                $officeInventory->decrement('quantity', $approvedQty);
                            }
                        }

                        // 2. Deduct from main inventory (InventoryItemVariant & parent InventoryItem)
                        $variant->decrement('quantity', min($approvedQty, $variant->quantity));

                        // Keep available_for_transfer bounded by remaining total quantity
                        if ($variant->available_for_transfer > $variant->quantity) {
                            $variant->available_for_transfer = max(0, $variant->quantity);
                        }

                        // Update variant stock status
                        $variant->status = $variant->quantity <= 0
                            ? 'Out of Stock'
                            : ($variant->quantity <= $variant->reorder_level ? 'Low Stock' : 'In Stock');
                        $variant->save();

                        // Update parent inventory item total stock & status
                        $parentItem = $variant->inventoryItem;
                        if ($parentItem) {
                            $totalStock = (int) $parentItem->variants()->sum('quantity');
                            $parentStatus = $totalStock <= 0
                                ? 'Out of Stock'
                                : ($totalStock <= $parentItem->reorder_level ? 'Low Stock' : 'In Stock');
                            $parentItem->update([
                                'quantity' => $totalStock,
                                'status' => $parentStatus,
                            ]);
                        }

                        // 3. Record audit log for stock release
                        InventoryLog::create([
                            'action_type' => InventoryLogAction::Release,
                            'batch_id' => $batchId,
                            'reference_number' => $refNumber,
                            'user_id' => auth()->id(),
                            'office_id' => $requisition->office_id,
                            'inventory_item_id' => null,
                            'inventory_item_variant_id' => $variant->id,
                            'office_name' => $requisition->office?->office_name ?? 'General Office',
                            'item_name' => $item->item_name ?: $variant->variant_name,
                            'variant_name' => $item->variant_name ?: $variant->variant_name,
                            'quantity' => $approvedQty,
                            'quantity_before' => $prevOfficeQty,
                            'quantity_after' => max(0, $prevOfficeQty - $approvedQty),
                            'recipient' => $requisition->requested_by_name ?? ($requisition->user?->full_name ?? $requisition->user?->name),
                            'purpose' => $requisition->purpose,
                            'remarks' => "Released {$approvedQty} units upon approval of Requisition #{$requisition->request_number}.".($itemRemarks ? " Note: {$itemRemarks}" : ''),
                            'ip_address' => $request->ip(),
                            'user_agent' => $request->userAgent(),
                        ]);

                        // Record outbound entry into Stock Card table
                        $reqOfficeCode = $requisition->office?->office_code ?: ($requisition->office?->office_name ?: 'Office');
                        StockCard::recordIssuance(
                            variant: $variant,
                            quantity: $approvedQty,
                            issueTo: $requisition->request_number ? "{$requisition->request_number} - {$reqOfficeCode}" : $reqOfficeCode,
                            officeId: $requisition->office_id,
                            officeName: $requisition->office?->office_name,
                            reference: $requisition->request_number,
                            date: now()->toDateString(),
                            remarks: $itemRemarks ?: null,
                            userId: auth()->id()
                        );
                    } else {
                        // Check for fallback variant by SKU, item_code, barcode, or variant name
                        $fallbackVariant = null;
                        if ($item->stock_no) {
                            $fallbackVariant = InventoryItemVariant::where('sku', $item->stock_no)
                                ->orWhere('item_code', $item->stock_no)
                                ->orWhere('barcode', $item->stock_no)
                                ->lockForUpdate()
                                ->first();
                        }
                        if (! $fallbackVariant && $item->item_name) {
                            $fallbackVariant = InventoryItemVariant::where('variant_name', $item->item_name)
                                ->lockForUpdate()
                                ->first();
                        }

                        if ($fallbackVariant) {
                            $fallbackVariant->decrement('quantity', min($approvedQty, $fallbackVariant->quantity));
                            if ($fallbackVariant->available_for_transfer > $fallbackVariant->quantity) {
                                $fallbackVariant->available_for_transfer = max(0, $fallbackVariant->quantity);
                            }
                            $fallbackVariant->status = $fallbackVariant->quantity <= 0
                                ? 'Out of Stock'
                                : ($fallbackVariant->quantity <= $fallbackVariant->reorder_level ? 'Low Stock' : 'In Stock');
                            $fallbackVariant->save();

                            InventoryLog::create([
                                'action_type' => InventoryLogAction::Release,
                                'batch_id' => $batchId,
                                'reference_number' => $refNumber,
                                'user_id' => auth()->id(),
                                'office_id' => $requisition->office_id,
                                'inventory_item_id' => null,
                                'inventory_item_variant_id' => $fallbackVariant->id,
                                'office_name' => $requisition->office?->office_name ?? 'General Office',
                                'item_name' => $fallbackVariant->variant_name,
                                'variant_name' => $fallbackVariant->variant_name,
                                'quantity' => $approvedQty,
                                'quantity_before' => 0,
                                'quantity_after' => 0,
                                'recipient' => $requisition->requested_by_name ?? ($requisition->user?->full_name ?? $requisition->user?->name),
                                'purpose' => $requisition->purpose,
                                'remarks' => "Released {$approvedQty} units upon approval of Requisition #{$requisition->request_number}.".($itemRemarks ? " Note: {$itemRemarks}" : ''),
                                'ip_address' => $request->ip(),
                                'user_agent' => $request->userAgent(),
                            ]);

                            StockCard::recordIssuance(
                                variant: $fallbackVariant,
                                quantity: $approvedQty,
                                issueTo: $requisition->request_number ? "{$requisition->request_number} - {$reqOfficeCode}" : $reqOfficeCode,
                                officeId: $requisition->office_id,
                                officeName: $requisition->office?->office_name,
                                reference: $requisition->request_number,
                                date: now()->toDateString(),
                                remarks: $itemRemarks ?: null,
                                userId: auth()->id()
                            );
                        }
                    }
                }
            }

            $finalStatus = $validated['action'] === 'issue'
                ? 'issued'
                : ($hasQuantityReduction ? 'approved_with_adjustments' : 'approved');

            $requisitionData = [
                'status' => $finalStatus,
                'approved_by_id' => auth()->id(),
                'approved_by_name' => $validated['approved_by_name'] ?? (auth()->user()->full_name ?? auth()->user()->name),
                'approved_by_designation' => $validated['approved_by_designation'] ?? 'PGDH - PGSO',
                'approved_at' => now(),
                'remarks' => ! empty($validated['remarks']) ? trim($validated['remarks']) : ($hasQuantityReduction ? $reasonText : null),
                'rejection_reason' => $hasQuantityReduction ? $reasonText : null,
            ];

            if ($validated['action'] === 'issue') {
                $requisitionData['issued_by_id'] = auth()->id();
                $requisitionData['issued_by_name'] = $validated['issued_by_name'] ?? (auth()->user()->full_name ?? auth()->user()->name);
                $requisitionData['issued_by_designation'] = $validated['issued_by_designation'] ?? 'AA - III / Warehouse Issuing Officer';
                $requisitionData['issued_at'] = now();
            }

            $requisition->update($requisitionData);

            if ($requisition->request_type === 'PR') {
                PurchaseRequest::where('pr_number', $requisition->request_number)
                    ->orWhere('remarks', 'LIKE', "%{$requisition->request_number}%")
                    ->update([
                        'status' => in_array($finalStatus, ['approved', 'approved_with_adjustments', 'issued']) ? 'approved' : $finalStatus,
                        'approved_by_id' => $requisitionData['approved_by_id'] ?? null,
                        'approved_by_name' => $requisitionData['approved_by_name'] ?? null,
                        'approved_by_designation' => $requisitionData['approved_by_designation'] ?? null,
                        'approved_at' => $requisitionData['approved_at'] ?? now(),
                    ]);
            }

            // Automatically insert approved RIS into SSMI database table
            if (in_array($finalStatus, ['approved', 'approved_with_adjustments', 'issued']) && $totalApprovedUnits > 0) {
                if ($requisition->request_type !== 'PR') {
                    SsmiReport::recordRequisition($requisition);
                }
            }
        });

        $msg = $validated['action'] === 'issue'
            ? "Requisition #{$requisition->request_number} has been approved and marked as issued ({$totalApprovedUnits} units)."
            : ($hasQuantityReduction
                ? "Requisition #{$requisition->request_number} approved with quantity adjustments ({$totalApprovedUnits} units approved)."
                : "Requisition #{$requisition->request_number} approved in full ({$totalApprovedUnits} units).");

        return redirect()->route('admin.requisitions.index')->with('status', $msg);
    }
}
