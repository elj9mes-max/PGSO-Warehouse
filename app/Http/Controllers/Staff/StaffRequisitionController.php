<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\InventoryItemVariant;
use App\Models\InventoryLog;
use App\Models\Office;
use App\Models\OfficeInventory;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\RequisitionRequest;
use App\Models\RequisitionRequestItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffRequisitionController extends Controller
{
    /**
     * Display requisition type choice (RIS vs PR).
     */
    public function index(): View
    {
        $user = auth()->user()->load('office');

        return view('staff.requisition', compact('user'));
    }

    /**
     * Display Requisition and Issue Slip (RIS) creation form.
     */
    public function ris(): View
    {
        $user = auth()->user()->load('office');
        $activeOffice = $user->office;
        if ($activeOffice) {
            $activeOffice->load([
                'officeInventories.variant.inventoryItem',
                'officeInventories.variant.batches' => fn ($q) => $q->latest('id'),
            ]);
        }

        $officeInventories = $activeOffice ? $activeOffice->officeInventories : collect();
        $totalStockUnits = (int) $officeInventories->sum('quantity');
        $categories = $officeInventories->map(fn ($oi) => $oi->variant?->inventoryItem?->category)->filter()->unique()->values();

        return view('staff.requisition.ris', compact(
            'user',
            'activeOffice',
            'officeInventories',
            'totalStockUnits',
            'categories'
        ));
    }

    /**
     * Store submitted RIS requisition request.
     */
    public function storeRis(Request $request): JsonResponse|RedirectResponse
    {
        $user = auth()->user()->load('office');
        $activeOffice = $user->office;

        $validated = $request->validate([
            'purpose' => ['nullable', 'string', 'max:1000'],
            'division' => ['nullable', 'string', 'max:191'],
            'air_number' => ['nullable', 'string', 'max:64'],
            'po_number' => ['nullable', 'string', 'max:64'],
            'supplier' => ['nullable', 'string', 'max:191'],
            'sai_no' => ['nullable', 'string', 'max:64'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required'],
            'items.*.variant_id' => ['nullable'],
            'items.*.office_inventory_id' => ['nullable'],
            'items.*.name' => ['nullable', 'string'],
            'items.*.variant' => ['nullable', 'string'],
            'items.*.sku' => ['nullable', 'string'],
            'items.*.unit' => ['nullable', 'string'],
            'items.*.cost' => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:100000'],
        ]);

        $officeId = $activeOffice?->id ?? $user->office_id ?? Office::value('id');
        $requestNumber = RequisitionRequest::generateRequestNumber('RIS');

        $requisition = DB::transaction(function () use ($validated, $user, $officeId, $requestNumber) {
            $requisition = RequisitionRequest::create([
                'request_number' => $requestNumber,
                'request_type' => 'RIS',
                'office_id' => $officeId,
                'user_id' => $user->id,
                'status' => 'pending',
                'purpose' => $validated['purpose'] ?: 'Official Office Supply Consumption',
                'division' => $validated['division'] ?? null,
                'air_number' => $validated['air_number'] ?? null,
                'po_number' => $validated['po_number'] ?? null,
                'supplier' => $validated['supplier'] ?? null,
                'sai_no' => $validated['sai_no'] ?? null,
                'responsibility_center_code' => '1061',
                'requested_by_name' => $user->full_name,
                'requested_by_designation' => 'Staff Officer',
            ]);

            $firstBatch = null;

            foreach ($validated['items'] as $itemData) {
                $variant = null;

                // 1. Explicit variant_id
                if (! empty($itemData['variant_id'])) {
                    $variant = InventoryItemVariant::with(['inventoryItem', 'batches' => fn ($q) => $q->latest('id')])->find($itemData['variant_id']);
                }

                // 2. Explicit office_inventory_id
                if (! $variant && ! empty($itemData['office_inventory_id'])) {
                    $oi = OfficeInventory::with(['variant.inventoryItem', 'variant.batches' => fn ($q) => $q->latest('id')])->find($itemData['office_inventory_id']);
                    if ($oi && $oi->variant) {
                        $variant = $oi->variant;
                    }
                }

                // 3. Fallback on 'id': check candidate OfficeInventory vs direct InventoryItemVariant
                if (! $variant && ! empty($itemData['id'])) {
                    $candidateOi = OfficeInventory::with(['variant.inventoryItem', 'variant.batches' => fn ($q) => $q->latest('id')])->find($itemData['id']);
                    $candidateDirectVariant = InventoryItemVariant::with(['inventoryItem', 'batches' => fn ($q) => $q->latest('id')])->find($itemData['id']);

                    if ($candidateOi && $candidateOi->variant && ! empty($itemData['name']) && (
                        str_contains(strtolower($candidateOi->variant->variant_name ?? ''), strtolower($itemData['name'])) ||
                        str_contains(strtolower($candidateOi->variant->inventoryItem?->name ?? ''), strtolower($itemData['name'])) ||
                        strtolower($candidateOi->variant->sku ?? '') === strtolower($itemData['sku'] ?? '')
                    )) {
                        $variant = $candidateOi->variant;
                    } elseif ($candidateDirectVariant) {
                        $variant = $candidateDirectVariant;
                    } elseif ($candidateOi && $candidateOi->variant) {
                        $variant = $candidateOi->variant;
                    }
                }

                if (! $firstBatch && $variant) {
                    $firstBatch = $variant->batches->first(fn ($b) => ! empty($b->air_number)) ?? $variant->batches->first();
                }

                $qty = (int) $itemData['quantity'];
                $cost = (float) ($variant?->unit_cost ?? ($itemData['cost'] ?? 0));
                $itemName = $variant?->inventoryItem?->name ?? ($itemData['name'] ?? ($variant?->variant_name ?? 'Office Item'));
                $variantName = $variant?->variant_name ?? ($itemData['variant'] ?? null);
                $stockNo = $variant?->sku ?: ($variant?->item_code ?: ($variant?->barcode ?: ($itemData['sku'] ?? 'N/A')));
                $unit = $variant?->effective_unit ?? ($variant?->unit ?? ($itemData['unit'] ?? 'pcs'));

                RequisitionRequestItem::create([
                    'requisition_request_id' => $requisition->id,
                    'inventory_item_variant_id' => $variant?->id,
                    'stock_no' => $stockNo,
                    'item_name' => $itemName,
                    'variant_name' => $variantName,
                    'unit' => $unit,
                    'quantity_requested' => $qty,
                    'quantity_issued' => 0,
                    'unit_cost' => $cost,
                    'total_cost' => round($qty * $cost, 2),
                    'status' => 'pending',
                ]);
            }

            // If AIR, PO, or Supplier are empty, automatically fill from the item's batch
            $needsUpdate = false;
            $updateData = [];
            if (empty($requisition->po_number) && ! empty($firstBatch?->po_number)) {
                $updateData['po_number'] = $firstBatch->po_number;
                $needsUpdate = true;
            }
            if (empty($requisition->supplier) && ! empty($firstBatch?->supplier)) {
                $updateData['supplier'] = $firstBatch->supplier;
                $needsUpdate = true;
            }
            if (empty($requisition->air_number) && ! empty($firstBatch?->air_number)) {
                $updateData['air_number'] = $firstBatch->air_number;
                $needsUpdate = true;
            }
            if ($needsUpdate) {
                $requisition->update($updateData);
            }

            return $requisition;
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Requisition {$requestNumber} has been successfully recorded!",
                'request_number' => $requestNumber,
                'request_id' => $requisition->id,
                'redirect_url' => route('staff.requisition.ris-form', ['requisition' => $requisition->id]),
            ]);
        }

        return redirect()->route('staff.requisition.ris-form', ['requisition' => $requisition->id])
            ->with('success', "Requisition {$requestNumber} has been submitted successfully!");
    }

    /**
     * Patch details on an existing RIS requisition.
     */
    public function updateRisDetails(Request $request, RequisitionRequest $requisition): JsonResponse|RedirectResponse
    {
        $user = auth()->user();
        if (! $user->isAdmin() && $requisition->user_id !== $user->id && $requisition->office_id !== $user->office_id) {
            abort(403);
        }

        $validated = $request->validate([
            'air_number' => ['nullable', 'string', 'max:64'],
            'po_number' => ['nullable', 'string', 'max:64'],
            'supplier' => ['nullable', 'string', 'max:191'],
            'division' => ['nullable', 'string', 'max:191'],
            'responsibility_center_code' => ['nullable', 'string', 'max:64'],
            'ris_no' => ['nullable', 'string', 'max:64'],
            'sai_no' => ['nullable', 'string', 'max:64'],
            'purpose' => ['nullable', 'string', 'max:1000'],
        ]);

        $requisition->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'RIS details updated successfully.',
                'requisition' => $requisition,
            ]);
        }

        return redirect()->back()->with('status', 'RIS details updated successfully.');
    }

    /**
     * Display printable RIS form.
     */
    public function risForm(Request $request): View
    {
        $user = auth()->user()->load('office');
        $activeOffice = $user->office;
        $requisitionId = $request->query('requisition') ?? $request->query('id');
        $requisition = null;

        if ($requisitionId) {
            $requisition = RequisitionRequest::with([
                'items.variant.batches' => function ($query) {
                    $query->latest('id');
                },
                'items.variant.inventoryItem',
                'office',
                'user',
            ])
                ->where(function ($query) use ($user) {
                    if (! $user->isAdmin()) {
                        $query->where('office_id', $user->office_id)
                            ->orWhere('user_id', $user->id);
                    }
                })
                ->where(function ($query) use ($requisitionId) {
                    $query->where('id', $requisitionId)
                        ->orWhere('request_number', $requisitionId);
                })
                ->first();

            if ($requisition) {
                if ($requisition->office) {
                    $activeOffice = $requisition->office;
                }

                // Auto-fill AIR, PO, and Supplier from first item's batch if empty
                $firstBatch = null;
                foreach ($requisition->items as $it) {
                    $batches = $it->variant?->batches;
                    $b = $batches?->first(fn ($batch) => ! empty($batch->air_number) || ! empty($batch->po_number) || ! empty($batch->supplier))
                        ?? $batches?->first();
                    if ($b) {
                        $firstBatch = $b;
                        break;
                    }
                }

                $needsSave = false;
                if (empty($requisition->po_number) && ! empty($firstBatch?->po_number)) {
                    $requisition->po_number = $firstBatch->po_number;
                    $needsSave = true;
                }
                if (empty($requisition->supplier) && ! empty($firstBatch?->supplier)) {
                    $requisition->supplier = $firstBatch->supplier;
                    $needsSave = true;
                }
                if (empty($requisition->air_number) && ! empty($firstBatch?->air_number)) {
                    $requisition->air_number = $firstBatch->air_number;
                    $needsSave = true;
                }
                if ($needsSave) {
                    $requisition->save();
                }
            }
        }

        return view('staff.requisition.ris-form', compact('user', 'activeOffice', 'requisition'));
    }

    /**
     * Display printable PR form.
     */
    public function prForm(Request $request): View
    {
        $user = auth()->user()->load('office');
        $activeOffice = $user->office;
        $prId = $request->query('pr') ?? $request->query('id');
        $requisitionId = $request->query('requisition');
        $purchaseRequest = null;
        $requisition = null;

        if ($prId) {
            $purchaseRequest = PurchaseRequest::with(['items.variant.inventoryItem', 'office', 'user', 'approvedBy'])
                ->where(function ($query) use ($user) {
                    if (! $user->isAdmin()) {
                        $query->where('office_id', $user->office_id)
                            ->orWhere('user_id', $user->id);
                    }
                })
                ->find($prId);

            if ($purchaseRequest && $purchaseRequest->office) {
                $activeOffice = $purchaseRequest->office;
            }
        } elseif ($requisitionId) {
            $requisition = RequisitionRequest::with(['items.variant.inventoryItem', 'office', 'user'])
                ->where(function ($query) use ($user) {
                    if (! $user->isAdmin()) {
                        $query->where('office_id', $user->office_id)
                            ->orWhere('user_id', $user->id);
                    }
                })
                ->find($requisitionId);

            if ($requisition) {
                if ($requisition->office) {
                    $activeOffice = $requisition->office;
                }
                $purchaseRequest = PurchaseRequest::with(['items.variant.inventoryItem', 'office', 'user', 'approvedBy'])
                    ->where('pr_number', $requisition->request_number)
                    ->first();
            }
        }

        return view('staff.requisition.pr-form', compact('user', 'activeOffice', 'purchaseRequest', 'requisition'));
    }

    /**
     * Display Purchase Request (PR) creation view.
     */
    public function pr(): View
    {
        $user = auth()->user()->load('office');
        $activeOffice = $user->office;

        // Load variants with their parent inventory item (hiding warehouse stock/location info in view)
        $variants = InventoryItemVariant::with(['inventoryItem.categoryModel'])
            ->orderBy('variant_name')
            ->get();

        // Load categories from the Category model
        $categories = Category::orderBy('name')->get();

        return view('staff.requisition.pr', compact('user', 'activeOffice', 'variants', 'categories'));
    }

    /**
     * Store submitted Purchase Request.
     */
    public function storePr(Request $request): JsonResponse|RedirectResponse
    {
        $user = auth()->user()->load('office');
        $activeOffice = $user->office;

        $validated = $request->validate([
            'purpose' => ['nullable', 'string', 'max:1000'],
            'responsibility_center_code' => ['nullable', 'string', 'max:64'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'exists:inventory_item_variants,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.estimated_unit_cost' => ['nullable', 'numeric', 'min:0'],
            'items.*.remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $officeId = $activeOffice?->id ?? $user->office_id ?? Office::value('id');
        $prNumber = PurchaseRequest::generatePrNumber();
        $requestNumber = RequisitionRequest::generateRequestNumber('PR');

        $purchaseRequest = DB::transaction(function () use ($validated, $user, $officeId, $prNumber, $requestNumber) {
            $pr = PurchaseRequest::create([
                'pr_number' => $prNumber,
                'office_id' => $officeId,
                'user_id' => $user->id,
                'status' => 'pending',
                'purpose' => $validated['purpose'] ?: 'Official Purchase Request for Office Procurement',
                'responsibility_center_code' => $validated['responsibility_center_code'] ?? '1061',
                'requested_by_name' => $user->full_name,
                'requested_by_designation' => 'Staff Officer',
                'total_estimated_cost' => 0.00,
            ]);

            // Create synchronized RequisitionRequest record for central request tracking and monitoring
            $requisition = RequisitionRequest::create([
                'request_number' => $requestNumber,
                'request_type' => 'PR',
                'office_id' => $officeId,
                'user_id' => $user->id,
                'status' => 'pending',
                'purpose' => $validated['purpose'] ?: 'Official Purchase Request for Office Procurement',
                'responsibility_center_code' => $validated['responsibility_center_code'] ?? '1061',
                'requested_by_name' => $user->full_name,
                'requested_by_designation' => 'Staff Officer',
                'remarks' => "Synced with PR {$prNumber}",
            ]);

            $totalCost = 0.00;

            foreach ($validated['items'] as $itemData) {
                $variant = InventoryItemVariant::with('inventoryItem')->find($itemData['id']);
                $qty = (int) $itemData['quantity'];
                $cost = (float) ($itemData['estimated_unit_cost'] ?? $variant?->unit_cost ?? 0);
                $lineTotal = round($qty * $cost, 2);
                $totalCost += $lineTotal;

                PurchaseRequestItem::create([
                    'purchase_request_id' => $pr->id,
                    'inventory_item_variant_id' => $variant?->id,
                    'item_code' => $variant?->sku ?? $variant?->inventoryItem?->item_code ?? 'N/A',
                    'barcode' => $variant?->barcode,
                    'item_name' => $variant?->inventoryItem?->name ?? 'Warehouse Item',
                    'variant_name' => $variant?->variant_name,
                    'description' => $variant?->variant_description ?? $variant?->inventoryItem?->description,
                    'unit' => $variant?->unit ?? $variant?->inventoryItem?->unit ?? 'pcs',
                    'quantity_requested' => $qty,
                    'estimated_unit_cost' => $cost,
                    'estimated_total_cost' => $lineTotal,
                    'remarks' => $itemData['remarks'] ?? null,
                ]);

                RequisitionRequestItem::create([
                    'requisition_request_id' => $requisition->id,
                    'inventory_item_variant_id' => $variant?->id,
                    'stock_no' => $variant?->sku ?? $variant?->inventoryItem?->item_code ?? 'N/A',
                    'item_name' => $variant?->inventoryItem?->name ?? 'Warehouse Item',
                    'variant_name' => $variant?->variant_name,
                    'unit' => $variant?->unit ?? $variant?->inventoryItem?->unit ?? 'pcs',
                    'quantity_requested' => $qty,
                    'quantity_issued' => 0,
                    'unit_cost' => $cost,
                    'total_cost' => $lineTotal,
                    'status' => 'pending',
                ]);
            }

            $pr->update(['total_estimated_cost' => $totalCost]);

            return $pr;
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Purchase Request {$prNumber} has been successfully submitted!",
                'pr_number' => $prNumber,
                'request_number' => $requestNumber,
                'redirect_url' => route('staff.request-stat'),
            ]);
        }

        return redirect()->route('staff.request-stat')->with('success', "Purchase Request {$prNumber} has been submitted successfully!");
    }

    /**
     * Display staff request status and activity logs monitoring.
     */
    public function requestStat(): View
    {
        $user = auth()->user()->load('office');
        $activeOffice = $user->office;

        // Load requests scoped to active office
        $query = RequisitionRequest::with(['items.variant.inventoryItem', 'user', 'office'])
            ->orderByDesc('created_at');

        if ($activeOffice) {
            $query->where('office_id', $activeOffice->id);
        } else {
            $query->where('user_id', $user->id);
        }

        $requests = $query->get();

        // Load audit inventory release logs
        $logsQuery = InventoryLog::with(['user', 'office', 'inventoryItem', 'variant'])
            ->orderByDesc('created_at')
            ->limit(30);

        if ($activeOffice) {
            $logsQuery->where('office_id', $activeOffice->id);
        }

        $inventoryLogs = $logsQuery->get();

        // Calculate metrics
        $totalCount = $requests->count();
        $pendingCount = $requests->where('status', 'pending')->count();
        $approvedCount = $requests->where('status', 'approved')->count();
        $issuedCount = $requests->where('status', 'issued')->count();
        $rejectedCount = $requests->where('status', 'rejected')->count();
        $totalUnitsRequested = (int) $requests->flatMap->items->sum('quantity_requested');

        return view('staff.request-stat', compact(
            'user',
            'activeOffice',
            'requests',
            'inventoryLogs',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'issuedCount',
            'rejectedCount',
            'totalUnitsRequested'
        ));
    }
}
