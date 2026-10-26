<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Office;
use App\Models\OfficeInventory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OfficeController extends Controller
{
    /**
     * Display a listing of offices.
     */
    public function index(): View
    {
        $offices = Office::latest()->get();

        return view('admin.office', compact('offices'));
    }

    /**
     * Redirect singular /office to offices index.
     */
    public function officeRedirect(): RedirectResponse
    {
        return redirect()->route('admin.offices.index');
    }

    /**
     * Display the custody inventory of a specific office.
     */
    public function inventory(Office $office): View
    {
        $office->load([
            'officeInventories.variant.inventoryItem',
        ]);
        $allOffices = Office::orderBy('office_name')->get();
        $categories = Category::orderBy('name')->pluck('name');

        return view('admin.inventory.office_show', compact('office', 'allOffices', 'categories'));
    }

    /**
     * Update an item in office custody inventory.
     */
    public function updateInventory(Request $request, Office $office, OfficeInventory $officeInventory): RedirectResponse
    {
        abort_unless($officeInventory->office_id === $office->id, 404);

        $validated = $request->validate([
            'variant_name' => ['required', 'string', 'max:255'],
            'barcode' => [
                'required',
                'string',
                'max:255',
                Rule::unique('inventory_item_variants', 'barcode')->ignore($officeInventory->inventory_item_variants_id),
            ],
            'category' => ['nullable', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:50'],
            'unit_cost' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $officeInventory->update([
            'quantity' => $validated['quantity'],
        ]);

        $variant = $officeInventory->variant;
        if ($variant) {
            $categoryName = ! empty($validated['category']) ? trim($validated['category']) : null;
            if ($categoryName) {
                Category::firstOrCreate(['name' => $categoryName]);
            }

            $variant->update([
                'variant_name' => $validated['variant_name'],
                'barcode' => $validated['barcode'],
                'category' => $categoryName ?: $variant->category,
                'unit' => ! empty($validated['unit']) ? $validated['unit'] : $variant->unit,
                'unit_cost' => isset($validated['unit_cost']) && $validated['unit_cost'] !== ''
                    ? $validated['unit_cost']
                    : $variant->unit_cost,
            ]);

            if ($categoryName && $variant->inventoryItem) {
                $variant->inventoryItem->update([
                    'category' => $categoryName,
                ]);
            }
        }

        return redirect()->route('admin.offices.inventory', $office)
            ->with('status', "Item '{$validated['variant_name']}' updated successfully!");
    }

    /**
     * Redirect office show to inventory view.
     */
    public function show(Office $office): RedirectResponse
    {
        return redirect()->route('admin.offices.inventory', $office);
    }

    /**
     * Store a newly created office in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'office_name' => ['required', 'string', 'max:255'],
            'office_code' => ['required', 'string', 'max:255', 'unique:offices,office_code'],
        ]);

        Office::create($validated);

        return redirect()->route('admin.offices.index')->with('status', "Office '{$validated['office_name']}' created successfully!");
    }

    /**
     * Update the specified office in storage.
     */
    public function update(Request $request, Office $office): RedirectResponse
    {
        $validated = $request->validate([
            'office_name' => ['required', 'string', 'max:255'],
            'office_code' => ['required', 'string', 'max:255', Rule::unique('offices', 'office_code')->ignore($office->id)],
        ]);

        $office->update($validated);

        return redirect()->route('admin.offices.index')->with('status', "Office '{$office->office_name}' updated successfully!");
    }

    /**
     * Remove the specified office from storage.
     */
    public function destroy(Office $office): RedirectResponse
    {
        $name = $office->office_name;
        $office->delete();

        return redirect()->route('admin.offices.index')->with('status', "Office '{$name}' deleted successfully.");
    }
}
