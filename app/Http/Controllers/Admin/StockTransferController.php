<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\InventoryItemVariant;
use App\Models\Office;
use App\Models\OfficeInventory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockTransferController extends Controller
{
    /**
     * Display stock transfer form and office stock overviews.
     */
    public function index(Request $request): View
    {
        $offices = Office::with(['officeInventories.variant.inventoryItem'])->orderBy('office_name')->get();

        $variants = InventoryItemVariant::with('categoryModel')
            ->orderBy('variant_name')
            ->get();

        $items = $variants;
        $categories = Category::orderBy('name')->pluck('name');
        $selectedOfficeId = $request->query('office');

        return view('admin.transfer', compact('offices', 'variants', 'items', 'categories', 'selectedOfficeId'));
    }

    /**
     * Redirect singular /transfer to plural transfers index.
     */
    public function redirectTransfer(): RedirectResponse
    {
        return redirect()->route('admin.transfers.index');
    }

    /**
     * Store stock transfers to office custody.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'office_id' => ['required', 'exists:offices,id'],
            'transfers' => ['required', 'array', 'min:1'],
            'transfers.*.variant_id' => ['required', 'exists:inventory_item_variants,id'],
            'transfers.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $office = Office::findOrFail($validated['office_id']);
        $totalUnits = 0;
        $totalVariants = 0;

        DB::transaction(function () use ($validated, $office, &$totalUnits, &$totalVariants) {
            foreach ($validated['transfers'] as $transferItem) {
                $variant = InventoryItemVariant::lockForUpdate()->findOrFail($transferItem['variant_id']);
                $transferQty = (int) $transferItem['quantity'];

                if ($transferQty > $variant->available_for_transfer) {
                    throw ValidationException::withMessages([
                        'transfers' => "Cannot transfer {$transferQty} units of '{$variant->variant_name}'. Only {$variant->available_for_transfer} units are available for transfer.",
                    ]);
                }

                // Deduct from available_for_transfer
                $variant->decrement('available_for_transfer', $transferQty);

                // Add or increment in office_inventories
                $officeInventory = OfficeInventory::firstOrNew([
                    'office_id' => $office->id,
                    'inventory_item_variants_id' => $variant->id,
                ]);

                $officeInventory->quantity = ($officeInventory->exists ? (int) $officeInventory->quantity : 0) + $transferQty;
                $officeInventory->save();

                $totalUnits += $transferQty;
                $totalVariants++;
            }
        });

        return redirect()->route('admin.transfers.index', ['office' => $office->id])
            ->with('status', "Transferred {$totalUnits} units across {$totalVariants} variant(s) to {$office->office_name} successfully!");
    }
}
