<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InventoryLogAction;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\InventoryItemVariant;
use App\Models\InventoryLog;
use App\Models\Office;
use App\Models\OfficeInventory;
use App\Models\StockCard;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class StockReleaseController extends Controller
{
    /**
     * Display stock release / issuance form.
     */
    public function index(Request $request): View
    {
        $offices = Office::with(['officeInventories.variant.inventoryItem'])->orderBy('office_name')->get();
        $selectedOfficeId = $request->query('office');
        $selectedOffice = $selectedOfficeId ? $offices->firstWhere('id', (int) $selectedOfficeId) : null;
        $categories = Category::orderBy('name')->pluck('name');

        return view('admin.release', compact('offices', 'selectedOfficeId', 'selectedOffice', 'categories'));
    }

    /**
     * Redirect singular /release to plural releases index.
     */
    public function redirectRelease(): RedirectResponse
    {
        return redirect()->route('admin.releases.index');
    }

    /**
     * Process stock release / issuance from office custody.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'office_id' => ['required', 'exists:offices,id'],
            'recipient' => ['required', 'string', 'max:255'],
            'purpose' => ['nullable', 'string', 'max:255'],
            'releases' => ['required', 'array', 'min:1'],
            'releases.*.variant_id' => ['required', 'exists:inventory_item_variants,id'],
            'releases.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $office = Office::findOrFail($validated['office_id']);
        $totalReleased = 0;
        $variantCount = 0;
        $batchId = 'REL-'.strtoupper(Str::random(6)).'-'.date('Ymd');
        $refNumber = 'RIS-'.date('Y').'-'.str_pad((string) (InventoryLog::releases()->count() + 1), 4, '0', STR_PAD_LEFT);

        DB::transaction(function () use ($validated, $office, $batchId, $refNumber, $request, &$totalReleased, &$variantCount) {
            foreach ($validated['releases'] as $releaseData) {
                $variant = InventoryItemVariant::lockForUpdate()->findOrFail($releaseData['variant_id']);
                $releaseQty = (int) $releaseData['quantity'];

                // Find office inventory record
                $officeInventory = OfficeInventory::where('office_id', $office->id)
                    ->where('inventory_item_variants_id', $variant->id)
                    ->lockForUpdate()
                    ->first();

                if (! $officeInventory || $officeInventory->quantity < $releaseQty) {
                    $availableInOffice = $officeInventory?->quantity ?? 0;
                    throw ValidationException::withMessages([
                        'releases' => "Cannot release {$releaseQty} units of '{$variant->variant_name}' because only {$availableInOffice} units are in {$office->office_name} custody.",
                    ]);
                }

                if ($variant->quantity < $releaseQty) {
                    throw ValidationException::withMessages([
                        'releases' => "Cannot release {$releaseQty} units of '{$variant->variant_name}' because only {$variant->quantity} units exist in system inventory.",
                    ]);
                }

                $prevOfficeQty = $officeInventory->quantity;

                // Deduct from office inventory
                if ($officeInventory->quantity - $releaseQty <= 0) {
                    $officeInventory->delete();
                } else {
                    $officeInventory->decrement('quantity', $releaseQty);
                }

                // Deduct from inventory_item_variants quantity
                $variant->decrement('quantity', $releaseQty);

                // Keep available_for_transfer bounded by remaining total quantity
                if ($variant->available_for_transfer > $variant->quantity) {
                    $variant->available_for_transfer = max(0, $variant->quantity);
                }

                // Update variant status
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

                // Create audit log for release
                InventoryLog::create([
                    'action_type' => InventoryLogAction::Release,
                    'batch_id' => $batchId,
                    'reference_number' => $refNumber,
                    'user_id' => auth()->id(),
                    'office_id' => $office->id,
                    'inventory_item_id' => null,
                    'inventory_item_variant_id' => $variant->id,
                    'office_name' => $office->office_name,
                    'item_name' => $variant->variant_name,
                    'variant_name' => $variant->variant_name,
                    'quantity' => $releaseQty,
                    'quantity_before' => $prevOfficeQty,
                    'quantity_after' => max(0, $prevOfficeQty - $releaseQty),
                    'recipient' => $validated['recipient'] ?? null,
                    'purpose' => $validated['purpose'] ?? null,
                    'remarks' => "Released {$releaseQty} units of {$variant->variant_name} from {$office->office_name}.".(! empty($validated['recipient']) ? " Recipient: {$validated['recipient']}." : ''),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                $totalReleased += $releaseQty;
                $variantCount++;

                // Record outbound entry into Stock Card table
                $relOfficeCode = $office->office_code ?: ($office->office_name ?: 'Office');
                StockCard::recordIssuance(
                    variant: $variant,
                    quantity: $releaseQty,
                    issueTo: $refNumber ? "{$refNumber} - {$relOfficeCode}" : $relOfficeCode,
                    officeId: $office->id,
                    officeName: $office->office_name,
                    reference: $refNumber,
                    date: now()->toDateString(),
                    remarks: $validated['remarks'] ?? null,
                    userId: auth()->id()
                );
            }
        });

        return redirect()->route('admin.releases.index', ['office' => $office->id])
            ->with('status', "Successfully released {$totalReleased} units across {$variantCount} variant(s) from {$office->office_name}!");
    }
}
