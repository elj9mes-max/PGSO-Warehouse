<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AccountLevel;
use App\Http\Controllers\Controller;
use App\Models\InventoryItemVariant;
use App\Models\InventoryLog;
use App\Models\Office;
use App\Models\RequisitionRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function index(): View
    {
        $releases = InventoryLog::releases()
            ->with(['user', 'office', 'inventoryItem', 'variant'])
            ->orderByDesc('created_at')
            ->get();

        $totalInventoryStock = (int) InventoryItemVariant::where('is_for_inspection', false)->sum('quantity');
        $totalInventoryItems = (int) InventoryItemVariant::count();
        $totalReleasesCount = (int) InventoryLog::releases()->count();
        $monthReleasesCount = (int) InventoryLog::releases()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $monthUnitsReleased = (int) InventoryLog::releases()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('quantity');
        $activeOfficesCount = (int) Office::count();
        $staffCount = (int) User::where('account_level', AccountLevel::Staff)->count();
        $offices = Office::orderBy('office_name')->get();
        $recentReleases = InventoryLog::releases()
            ->with(['office', 'variant', 'user'])
            ->latest()
            ->take(6)
            ->get();

        // Prepare calendar events formatted as array
        $calendarEvents = $releases->map(function ($log) {
            return [
                'id' => $log->id,
                'batchId' => $log->batch_id ?? ('REL-'.str_pad((string) $log->id, 5, '0', STR_PAD_LEFT)),
                'referenceNumber' => $log->reference_number ?? ('RIS-'.$log->created_at->format('Y').'-'.str_pad((string) $log->id, 4, '0', STR_PAD_LEFT)),
                'date' => $log->created_at->format('Y-m-d'),
                'time' => $log->created_at->format('h:i A'),
                'datetime' => $log->created_at->toIso8601String(),
                'itemName' => $log->item_name ?? 'Standard Item',
                'variantName' => $log->variant_name ?? '',
                'quantity' => (int) ($log->quantity ?? 1),
                'unit' => $log->variant?->effective_unit ?? 'units',
                'officeName' => $log->office_name ?? ($log->office?->office_name ?? 'Custody Office'),
                'officeCode' => $log->office?->office_code ?? '',
                'officeId' => $log->office_id,
                'recipient' => $log->recipient ?: 'Not specified',
                'purpose' => $log->purpose ?: 'General stock issuance',
                'remarks' => $log->remarks ?: '',
                'releasedBy' => $log->user?->full_name ?? ($log->user?->name ?? 'Administrator'),
            ];
        })->values();

        // Fetch pending requisition requests sent by offices
        $pendingOfficeRequests = RequisitionRequest::with(['office', 'user', 'items'])
            ->where('status', 'pending')
            ->latest()
            ->get();
        $pendingOfficeRequestsCount = $pendingOfficeRequests->count();

        return view('admin.dashboard', compact(
            'releases',
            'calendarEvents',
            'totalInventoryStock',
            'totalInventoryItems',
            'totalReleasesCount',
            'monthReleasesCount',
            'monthUnitsReleased',
            'activeOfficesCount',
            'staffCount',
            'offices',
            'recentReleases',
            'pendingOfficeRequests',
            'pendingOfficeRequestsCount'
        ));
    }
}
