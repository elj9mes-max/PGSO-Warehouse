<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StaffInventoryController extends Controller
{
    /**
     * Redirect staff root to staff inventory.
     */
    public function home(): RedirectResponse
    {
        return redirect()->route('staff.inventory');
    }

    /**
     * Redirect staff dashboard to staff inventory.
     */
    public function dashboard(Request $request): RedirectResponse
    {
        return redirect()->route('staff.inventory', $request->query());
    }

    /**
     * Display staff office custody inventory.
     */
    public function index(Request $request): View
    {
        $user = auth()->user()->load('office');
        $isOfficeStaff = ! $user->isAdmin() && $user->office_id;

        if ($isOfficeStaff) {
            $activeOffice = $user->office;
            if ($activeOffice) {
                $activeOffice->load(['officeInventories.variant.inventoryItem']);
            }
            $canSwitchOffice = false;
            $allOffices = $activeOffice ? collect([$activeOffice]) : collect();
        } else {
            $canSwitchOffice = true;
            $requestedOfficeId = $request->query('office_id');
            if ($requestedOfficeId) {
                $activeOffice = Office::with(['officeInventories.variant.inventoryItem'])->find($requestedOfficeId);
            } else {
                $activeOffice = $user->office;
                if ($activeOffice) {
                    $activeOffice->load(['officeInventories.variant.inventoryItem']);
                }
            }
            $allOffices = Office::orderBy('office_name')->get();
        }

        $officeInventories = $activeOffice ? $activeOffice->officeInventories : collect();
        $totalStockUnits = (int) $officeInventories->sum('quantity');
        $allocatedItemsCount = $officeInventories->count();
        $lowStockCount = $officeInventories->filter(fn ($oi) => $oi->variant && $oi->quantity <= $oi->variant->reorder_level && $oi->quantity > 0)->count();
        $outOfStockCount = $officeInventories->filter(fn ($oi) => $oi->quantity <= 0)->count();
        $totalStockValue = (float) $officeInventories->sum(fn ($oi) => $oi->quantity * (float) ($oi->variant?->unit_cost ?? $oi->variant?->inventoryItem?->unit_cost ?? 0));

        $categories = $officeInventories->map(fn ($oi) => $oi->variant?->inventoryItem?->category)->filter()->unique()->values();

        return view('staff.inventory', compact(
            'user',
            'activeOffice',
            'allOffices',
            'canSwitchOffice',
            'officeInventories',
            'totalStockUnits',
            'allocatedItemsCount',
            'lowStockCount',
            'outOfStockCount',
            'totalStockValue',
            'categories'
        ));
    }
}
