<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    /**
     * Redirect user to appropriate dashboard based on their role.
     */
    public function index(): RedirectResponse
    {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('staff.inventory');
    }

    /**
     * Redirect general requisitions route based on role.
     */
    public function requisitions(): RedirectResponse
    {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.requisitions.index')
            : redirect()->route('staff.requisition.index');
    }

    /**
     * Redirect general reports route based on role.
     */
    public function reports(): RedirectResponse
    {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.reports.index')
            : redirect()->route('staff.inventory');
    }

    /**
     * Redirect general stock card report route based on role.
     */
    public function reportsStockCard(): RedirectResponse
    {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.reports.stock-card')
            : redirect()->route('staff.inventory');
    }

    /**
     * Redirect general SSMI report route based on role.
     */
    public function reportsSsmi(): RedirectResponse
    {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.reports.ssmi')
            : redirect()->route('staff.inventory');
    }
}
