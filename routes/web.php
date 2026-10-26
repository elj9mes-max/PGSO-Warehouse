<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\OfficeController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RequisitionController as AdminRequisitionController;
use App\Http\Controllers\Admin\StockReleaseController;
use App\Http\Controllers\Admin\StockTransferController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Staff\StaffInventoryController;
use App\Http\Controllers\Staff\StaffRequisitionController;
use Illuminate\Support\Facades\Route;

// Guest Routes (Login & Signup)
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.post');

    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signup'])->middleware('throttle:5,1')->name('signup.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // General Dashboard Gateway (Redirects based on role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes (Protected by admin middleware)
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Offices Listing & Management
        Route::get('/offices', [OfficeController::class, 'index'])->name('offices.index');
        Route::get('/office', [OfficeController::class, 'officeRedirect'])->name('office');
        Route::get('/offices/{office}/inventory', [OfficeController::class, 'inventory'])->name('offices.inventory');
        Route::put('/offices/{office}/inventory/{officeInventory}', [OfficeController::class, 'updateInventory'])->name('offices.inventory.update');
        Route::get('/offices/{office}', [OfficeController::class, 'show'])->name('offices.show');
        Route::post('/offices', [OfficeController::class, 'store'])->name('offices.store');
        Route::put('/offices/{office}', [OfficeController::class, 'update'])->name('offices.update');
        Route::delete('/offices/{office}', [OfficeController::class, 'destroy'])->name('offices.destroy');

        // Accounts & Staff Management
        Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
        Route::get('/users', [AccountController::class, 'usersRedirect'])->name('users.index');
        Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
        Route::put('/accounts/{user}', [AccountController::class, 'update'])->name('accounts.update');
        Route::put('/accounts/{user}/credentials', [AccountController::class, 'updateCredentials'])->name('accounts.credentials');
        Route::put('/accounts/{user}/office', [AccountController::class, 'updateOffice'])->name('accounts.office');
        Route::delete('/accounts/{user}', [AccountController::class, 'destroy'])->name('accounts.destroy');

        // Stock Transfer to Offices
        Route::get('/transfers', [StockTransferController::class, 'index'])->name('transfers.index');
        Route::get('/transfer', [StockTransferController::class, 'redirectTransfer'])->name('transfers.redirect');
        Route::post('/transfers', [StockTransferController::class, 'store'])->name('transfers.store');

        // Stock Release / Issuance from Offices
        Route::get('/releases', [StockReleaseController::class, 'index'])->name('releases.index');
        Route::get('/release', [StockReleaseController::class, 'redirectRelease'])->name('releases.redirect');
        Route::post('/releases', [StockReleaseController::class, 'store'])->name('releases.store');

        // Audit & Reports Center (Stock Card, Logs, Office Stock, SSMI)
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/report', [ReportController::class, 'redirectReport'])->name('reports.redirect');
        Route::get('/reports/stock-card', [ReportController::class, 'stockCard'])->name('reports.stock-card');
        Route::get('/reports/stock-card/data', [ReportController::class, 'stockCardData'])->name('reports.stock-card.data');
        Route::post('/reports/stock-card/save', [ReportController::class, 'saveStockCard'])->name('reports.stock-card.save');
        Route::get('/reports/ssmi', [ReportController::class, 'ssmi'])->name('reports.ssmi');
        Route::post('/reports/ssmi/save', [ReportController::class, 'saveSsmi'])->name('reports.ssmi.save');
        Route::delete('/reports/ssmi/{report}', [ReportController::class, 'destroySsmi'])->name('reports.ssmi.destroy');

        // Inventory Management
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
        Route::post('/categories', [InventoryController::class, 'storeCategory'])->name('categories.store');
        Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
        Route::get('/inventory/purchase-request', [InventoryController::class, 'purchaseRequest'])->name('inventory.purchase-request');
        Route::get('/inventory/insert-purchase-request', [InventoryController::class, 'redirectInsertPurchaseRequest'])->name('inventory.insert-purchase-request');
        Route::get('/inventory/iar-form', [InventoryController::class, 'iarForm'])->name('inventory.iar-form');
        Route::post('/inventory/iar/save-air', [InventoryController::class, 'saveAir'])->name('inventory.iar.save-air');
        Route::post('/inventory/purchase-request', [InventoryController::class, 'storePurchaseRequest'])->name('inventory.purchase-request.store');
        Route::get('/inventory/{inventoryItem}', [InventoryController::class, 'show'])->name('inventory.show');
        Route::post('/inventory/{inventoryItem}/variants', [InventoryController::class, 'storeVariant'])->name('inventory.variants.store');
        Route::put('/inventory/{inventoryItem}/variants/{variant}', [InventoryController::class, 'updateVariant'])->name('inventory.variants.update');
        Route::patch('/inventory/{inventoryItem}/variants/{variant}/inspection', [InventoryController::class, 'updateVariantInspection'])->name('inventory.variants.inspection');
        Route::delete('/inventory/{inventoryItem}/variants/{variant}', [InventoryController::class, 'destroyVariant'])->name('inventory.variants.destroy');

        // Requisitions Management & Review
        Route::get('/requisitions', [AdminRequisitionController::class, 'index'])->name('requisitions.index');
        Route::get('/requisition', [AdminRequisitionController::class, 'redirectRequisition'])->name('requisition');
        Route::post('/requisitions/{requisition}/review', [AdminRequisitionController::class, 'review'])->name('requisitions.review');
    });

    // Staff Routes
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/', [StaffInventoryController::class, 'home']);
        Route::get('/dashboard', [StaffInventoryController::class, 'dashboard'])->name('dashboard');
        Route::get('/inventory', [StaffInventoryController::class, 'index'])->name('inventory');

        // Staff Requisitions Selection & Forms
        Route::get('/requisition', [StaffRequisitionController::class, 'index'])->name('requisition.index');
        Route::get('/requisition/ris', [StaffRequisitionController::class, 'ris'])->name('requisition.ris');
        Route::post('/requisition/ris', [StaffRequisitionController::class, 'storeRis'])->name('requisition.ris.store');
        Route::patch('/requisition/ris-details/{requisition}', [StaffRequisitionController::class, 'updateRisDetails'])->name('requisition.ris-details.update');
        Route::get('/requisition/ris-form', [StaffRequisitionController::class, 'risForm'])->name('requisition.ris-form');
        Route::get('/requisition/pr-form', [StaffRequisitionController::class, 'prForm'])->name('requisition.pr-form');
        Route::get('/requisition/pr', [StaffRequisitionController::class, 'pr'])->name('requisition.pr');
        Route::post('/requisition/pr', [StaffRequisitionController::class, 'storePr'])->name('requisition.pr.store');

        // Request Status & Logs Monitoring
        Route::get('/request-stat', [StaffRequisitionController::class, 'requestStat'])->name('request-stat');
    });

    // Cross-role redirect routes
    Route::get('/requisitions', [DashboardController::class, 'requisitions'])->name('requisitions.index');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports.index');
    Route::get('/reports/stock-card', [DashboardController::class, 'reportsStockCard']);
    Route::get('/reports/ssmi', [DashboardController::class, 'reportsSsmi']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
