<?php

use App\Enums\AccountLevel;
use App\Enums\InventoryLogAction;
use App\Http\Controllers\AuthController;
use App\Models\Category;
use App\Models\InventoryBatch;
use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use App\Models\InventoryLog;
use App\Models\Office;
use App\Models\OfficeInventory;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\RequisitionRequest;
use App\Models\RequisitionRequestItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

// Guest Routes (Login & Signup)
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signup'])->name('signup.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // General Dashboard Gateway (Redirects based on role)
    Route::get('/dashboard', function () {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('staff.inventory');
    })->name('dashboard');

    // Admin Dashboard (Protected by admin middleware)
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            $releases = InventoryLog::releases()
                ->with(['user', 'office', 'inventoryItem', 'variant'])
                ->orderByDesc('created_at')
                ->get();

            $totalInventoryStock = (int) InventoryItemVariant::where('is_for_inspection', false)->sum('quantity');
            $totalInventoryItems = (int) InventoryItem::count();
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
        })->name('dashboard');

        // Offices Listing & Management
        Route::get('/offices', function () {
            $offices = Office::latest()->get();

            return view('admin.office', compact('offices'));
        })->name('offices.index');

        Route::get('/office', function () {
            return redirect()->route('admin.offices.index');
        })->name('office');

        Route::get('/offices/{office}/inventory', function (Office $office) {
            $office->load([
                'officeInventories.variant.inventoryItem',
            ]);
            $allOffices = Office::orderBy('office_name')->get();
            $categories = Category::orderBy('name')->pluck('name');

            return view('admin.inventory.office_show', compact('office', 'allOffices', 'categories'));
        })->name('offices.inventory');

        Route::get('/offices/{office}', function (Office $office) {
            return redirect()->route('admin.offices.inventory', $office);
        })->name('offices.show');

        Route::post('/offices', function (Request $request) {
            $validated = $request->validate([
                'office_name' => ['required', 'string', 'max:255'],
                'office_code' => ['required', 'string', 'max:255', 'unique:offices,office_code'],
            ]);

            Office::create($validated);

            return redirect()->route('admin.offices.index')->with('status', "Office '{$validated['office_name']}' created successfully!");
        })->name('offices.store');

        Route::put('/offices/{office}', function (Request $request, Office $office) {
            $validated = $request->validate([
                'office_name' => ['required', 'string', 'max:255'],
                'office_code' => ['required', 'string', 'max:255', Rule::unique('offices', 'office_code')->ignore($office->id)],
            ]);

            $office->update($validated);

            return redirect()->route('admin.offices.index')->with('status', "Office '{$office->office_name}' updated successfully!");
        })->name('offices.update');

        Route::delete('/offices/{office}', function (Office $office) {
            $name = $office->office_name;
            $office->delete();

            return redirect()->route('admin.offices.index')->with('status', "Office '{$name}' deleted successfully.");
        })->name('offices.destroy');

        // Accounts & Staff Management
        Route::get('/accounts', function () {
            $users = User::with('office')->orderBy('id', 'desc')->get();
            $offices = Office::orderBy('office_name')->get();

            $totalAccounts = $users->count();
            $staffCount = $users->where('account_level', AccountLevel::Staff)->count();
            $adminCount = $users->where('account_level', AccountLevel::Admin)->count();
            $assignedCount = $users->whereNotNull('office_id')->count();
            $unassignedCount = $users->whereNull('office_id')->count();

            return view('admin.accounts', compact(
                'users',
                'offices',
                'totalAccounts',
                'staffCount',
                'adminCount',
                'assignedCount',
                'unassignedCount'
            ));
        })->name('accounts.index');

        Route::get('/users', function () {
            return redirect()->route('admin.accounts.index');
        })->name('users.index');

        Route::post('/accounts', function (Request $request) {
            $validated = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'middle_initial' => ['nullable', 'string', 'max:10'],
                'last_name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:users,username'],
                'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
                'office_id' => ['nullable', 'exists:offices,id'],
                'account_level' => ['required', Rule::enum(AccountLevel::class)],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $fullName = trim("{$validated['first_name']} {$validated['last_name']}");

            User::create([
                'first_name' => $validated['first_name'],
                'middle_initial' => ! empty($validated['middle_initial']) ? rtrim($validated['middle_initial'], '.').'.' : null,
                'last_name' => $validated['last_name'],
                'name' => $fullName,
                'username' => $validated['username'],
                'email' => $validated['email'] ?? null,
                'office_id' => ! empty($validated['office_id']) ? $validated['office_id'] : null,
                'account_level' => $validated['account_level'],
                'password' => $validated['password'],
            ]);

            return redirect()->route('admin.accounts.index')->with('status', "Account for '{$fullName}' (@{$validated['username']}) created successfully!");
        })->name('accounts.store');

        Route::put('/accounts/{user}', function (Request $request, User $user) {
            $validated = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'middle_initial' => ['nullable', 'string', 'max:10'],
                'last_name' => ['required', 'string', 'max:255'],
                'account_level' => ['required', Rule::enum(AccountLevel::class)],
            ]);

            $fullName = trim("{$validated['first_name']} {$validated['last_name']}");

            $user->update([
                'first_name' => $validated['first_name'],
                'middle_initial' => ! empty($validated['middle_initial']) ? rtrim($validated['middle_initial'], '.').'.' : null,
                'last_name' => $validated['last_name'],
                'name' => $fullName,
                'account_level' => $validated['account_level'],
            ]);

            return redirect()->route('admin.accounts.index')->with('status', "Profile details for '{$user->full_name}' updated successfully!");
        })->name('accounts.update');

        Route::put('/accounts/{user}/credentials', function (Request $request, User $user) {
            $validated = $request->validate([
                'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            ]);

            $data = ['username' => $validated['username']];
            if (! empty($validated['password'])) {
                $data['password'] = $validated['password'];
            }

            $user->update($data);

            $message = ! empty($validated['password'])
                ? "Credentials for '{$user->full_name}' updated (new password set, username: @{$user->username})!"
                : "Username for '{$user->full_name}' updated to '@{$user->username}'!";

            return redirect()->route('admin.accounts.index')->with('status', $message);
        })->name('accounts.credentials');

        Route::put('/accounts/{user}/office', function (Request $request, User $user) {
            $validated = $request->validate([
                'office_id' => ['nullable', 'exists:offices,id'],
            ]);

            $newOfficeId = ! empty($validated['office_id']) ? (int) $validated['office_id'] : null;
            $user->update(['office_id' => $newOfficeId]);

            $user->refresh();
            $officeName = $user->office ? $user->office->office_name : 'No Office (Floating Staff)';

            return redirect()->route('admin.accounts.index')->with('status', "Office assignment for '{$user->full_name}' updated to '{$officeName}'!");
        })->name('accounts.office');

        Route::delete('/accounts/{user}', function (User $user) {
            if ($user->id === auth()->id()) {
                return redirect()->route('admin.accounts.index')->with('error', 'You cannot delete your own logged-in account.');
            }

            $userName = $user->full_name ?: $user->username;
            $user->delete();

            return redirect()->route('admin.accounts.index')->with('status', "Account '{$userName}' deleted successfully.");
        })->name('accounts.destroy');

        // Stock Transfer to Offices
        Route::get('/transfers', function (Request $request) {
            $offices = Office::with(['officeInventories.variant.inventoryItem'])->orderBy('office_name')->get();
            $items = InventoryItem::with(['variants' => function ($query) {
                $query->orderBy('variant_name');
            }])->latest()->get();
            $categories = Category::orderBy('name')->pluck('name');
            $selectedOfficeId = $request->query('office');

            return view('admin.transfer', compact('offices', 'items', 'categories', 'selectedOfficeId'));
        })->name('transfers.index');

        Route::get('/transfer', function () {
            return redirect()->route('admin.transfers.index');
        })->name('transfers.redirect');

        Route::post('/transfers', function (Request $request) {
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
        })->name('transfers.store');

        // Stock Release / Issuance from Offices
        Route::get('/releases', function (Request $request) {
            $offices = Office::with(['officeInventories.variant.inventoryItem'])->orderBy('office_name')->get();
            $selectedOfficeId = $request->query('office');
            $selectedOffice = $selectedOfficeId ? $offices->firstWhere('id', (int) $selectedOfficeId) : null;
            $categories = Category::orderBy('name')->pluck('name');

            return view('admin.release', compact('offices', 'selectedOfficeId', 'selectedOffice', 'categories'));
        })->name('releases.index');

        Route::get('/release', function () {
            return redirect()->route('admin.releases.index');
        })->name('releases.redirect');

        Route::post('/releases', function (Request $request) {
            $validated = $request->validate([
                'office_id' => ['required', 'exists:offices,id'],
                'recipient' => ['nullable', 'string', 'max:255'],
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
                        'inventory_item_id' => $variant->inventory_item_id,
                        'inventory_item_variant_id' => $variant->id,
                        'office_name' => $office->office_name,
                        'item_name' => $variant->inventoryItem?->name ?? 'Standard Item',
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
                }
            });

            return redirect()->route('admin.releases.index', ['office' => $office->id])
                ->with('status', "Successfully released {$totalReleased} units across {$variantCount} variant(s) from {$office->office_name}!");
        })->name('releases.store');

        Route::get('/inventory', function () {
            $variants = InventoryItemVariant::with('inventoryItem')
                ->where('is_for_inspection', false)
                ->latest()
                ->get();
            $items = InventoryItem::with(['variants' => function ($query) {
                $query->where('is_for_inspection', false);
            }])->latest()->get();
            $categories = Category::orderBy('name')->pluck('name');

            return view('admin.inventory', compact('variants', 'items', 'categories'));
        })->name('inventory');

        Route::post('/categories', function (Request $request) {
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
        })->name('categories.store');

        Route::post('/inventory', function (Request $request) {
            $validated = $request->validate([
                'item_code' => ['required', 'string', 'max:255', 'unique:inventory_items,item_code'],
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

            $item = InventoryItem::create(array_merge($validated, [
                'quantity' => $quantity,
                'reorder_level' => $reorderLevel,
                'unit_cost' => $unitCost,
                'status' => $status,
            ]));

            $cleanCode = preg_replace('/[^A-Za-z0-9]/', '', (string) ($item->item_code ?: $item->id));
            $barcodeCandidate = 'BAR-'.$cleanCode;
            if (InventoryItemVariant::where('barcode', $barcodeCandidate)->exists()) {
                $barcodeCandidate = 'BAR-'.$cleanCode.'-'.uniqid();
            }

            $item->variants()->create([
                'variant_name' => $item->name,
                'barcode' => $barcodeCandidate,
                'sku' => $item->item_code,
                'variant_description' => $item->description,
                'quantity' => $quantity,
                'available_for_transfer' => $quantity,
                'reorder_level' => $reorderLevel,
                'unit_cost' => $unitCost,
                'unit' => $item->unit,
                'location' => $item->location,
                'status' => $status,
                'is_for_inspection' => false,
            ]);

            return redirect()->route('admin.inventory')->with('status', 'Inventory item added successfully!');
        })->name('inventory.store');

        // Purchase Order / Supply Receiving (Insert Purchase Request containing inventory supplies)
        Route::get('/inventory/purchase-request', function () {
            $items = InventoryItem::with(['variants' => function ($query) {
                $query->where('is_for_inspection', false)->orderBy('variant_name');
            }])->orderBy('name')->get();

            // Ensure any items without variants have a standard default variant so all inventory items can be received
            foreach ($items as $item) {
                if ($item->variants->isEmpty()) {
                    $cleanCode = preg_replace('/[^A-Za-z0-9]/', '', (string) ($item->item_code ?: $item->id));
                    $defaultVariant = $item->variants()->create([
                        'variant_name' => 'Standard',
                        'barcode' => 'BAR-'.$cleanCode.'-STD',
                        'sku' => $item->item_code ? $item->item_code.'-STD' : null,
                        'quantity' => $item->quantity ?? 0,
                        'available_for_transfer' => $item->quantity ?? 0,
                        'unit_cost' => $item->unit_cost ?? 0.00,
                        'unit' => $item->unit ?? 'pcs',
                        'reorder_level' => $item->reorder_level ?? 10,
                        'status' => $item->status ?? 'In Stock',
                    ]);
                    $item->setRelation('variants', collect([$defaultVariant]));
                }
            }

            // Fetch ALL inventory item variants directly so all variants (both standalone and item-linked) are available
            $variants = InventoryItemVariant::with('inventoryItem')
                ->where('is_for_inspection', false)
                ->orderBy('variant_name')
                ->get();

            $offices = Office::orderBy('office_name')->get();

            $recentBatches = InventoryBatch::with(['inventoryItem', 'variant', 'office'])
                ->latest()
                ->take(15)
                ->get();

            return view('admin.inventory.insert-purchase-request', compact('variants', 'items', 'offices', 'recentBatches'));
        })->name('inventory.purchase-request');

        Route::get('/inventory/insert-purchase-request', function () {
            return redirect()->route('admin.inventory.purchase-request');
        })->name('inventory.insert-purchase-request');

        Route::get('/inventory/iar-form', function (Request $request) {
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
                    $office = $batch->office?->office_name ?? ($batch->office_id ? $office : 'Provincial General Services Office (PGSO)');
                    $poDate = $batch->delivery_date ? $batch->delivery_date->format('m/d/Y') : ($batch->created_at ? $batch->created_at->format('m/d/Y') : '');
                    $items[] = [
                        'stock_no' => $batch->variant?->sku ?? $batch->variant?->barcode ?? 'STK-'.str_pad((string) $batch->id, 4, '0', STR_PAD_LEFT),
                        'description' => ($batch->inventoryItem?->name ?? 'Supply Item').($batch->variant?->variant_name ? ' - '.$batch->variant->variant_name : ''),
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
                    $office = $first->office?->office_name ?? ($first->office_id ? $office : 'Provincial General Services Office (PGSO)');
                    $poDate = $first->delivery_date ? $first->delivery_date->format('m/d/Y') : ($first->created_at ? $first->created_at->format('m/d/Y') : '');
                    foreach ($batches as $b) {
                        $items[] = [
                            'stock_no' => $b->variant?->sku ?? $b->variant?->barcode ?? 'STK-'.str_pad((string) $b->id, 4, '0', STR_PAD_LEFT),
                            'description' => ($b->inventoryItem?->name ?? 'Supply Item').($b->variant?->variant_name ? ' - '.$b->variant->variant_name : ''),
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

            return view('admin.inventory.iar-form', compact('poNumber', 'supplier', 'poDate', 'office', 'items', 'airNumber', 'invoiceNumber', 'autoPrint'));
        })->name('inventory.iar-form');

        Route::post('/inventory/purchase-request', function (Request $request) {
            $validated = $request->validate([
                'po_number' => ['nullable', 'string', 'max:64'],
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
            $prNumber = $validated['pr_number'] ?? null;
            $supplier = $validated['supplier'] ?? null;

            $totalReceivedUnits = 0;
            $batchesCreated = 0;

            DB::transaction(function () use (
                $validated,
                $destinationOfficeId,
                $destinationName,
                $deliveryDate,
                $poNumber,
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
                        'inventory_item_id' => $variant->inventory_item_id,
                        'inventory_item_variant_id' => $variant->id,
                        'office_name' => $destinationName,
                        'item_name' => $inventoryItem?->name ?? 'Inventory Item',
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
                }
            });

            $statusMsg = "Successfully received {$totalReceivedUnits} units across {$batchesCreated} supply variant(s) into {$destinationName} under {$poNumber}!";

            $shouldGenerateIar = $request->boolean('generate_iar', false);
            $autoPrint = $request->boolean('auto_print', false);
            $invoiceNumber = $validated['invoice_number'] ?? null;

            if ($shouldGenerateIar) {
                return redirect()->route('admin.inventory.iar-form', array_filter([
                    'po' => $poNumber,
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
        })->name('inventory.purchase-request.store');

        Route::get('/inventory/{inventoryItem}', function (InventoryItem $inventoryItem) {
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
        })->name('inventory.show');

        Route::post('/inventory/{inventoryItem}/variants', function (Request $request, InventoryItem $inventoryItem) {
            $validated = $request->validate([
                'variant_name' => ['required', 'string', 'max:255'],
                'barcode' => ['required', 'string', 'max:255', 'unique:inventory_item_variants,barcode'],
                'sku' => ['nullable', 'string', 'max:255', 'unique:inventory_item_variants,sku'],
                'variant_description' => ['nullable', 'string'],
                'unit' => ['nullable', 'string', 'max:50'],
                'quantity' => ['required', 'integer', 'min:0'],
                'reorder_level' => ['required', 'integer', 'min:0'],
                'unit_cost' => ['nullable', 'numeric', 'min:0'],
                'location' => ['nullable', 'string', 'max:255'],
                'is_for_inspection' => ['nullable', 'boolean'],
            ]);

            $status = $validated['quantity'] <= 0
                ? 'Out of Stock'
                : ($validated['quantity'] <= $validated['reorder_level'] ? 'Low Stock' : 'In Stock');

            $isForInspection = $request->has('is_for_inspection')
                ? $request->boolean('is_for_inspection')
                : true;

            $variant = $inventoryItem->variants()->create(array_merge($validated, [
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

            return redirect()->route('admin.inventory.show', $inventoryItem)
                ->with('status', "Variant '{$variant->variant_name}' ({$identifier}) added successfully!");
        })->name('inventory.variants.store');

        Route::put('/inventory/{inventoryItem}/variants/{variant}', function (Request $request, InventoryItem $inventoryItem, InventoryItemVariant $variant) {
            abort_unless($variant->inventory_item_id === $inventoryItem->id, 404);

            $validated = $request->validate([
                'variant_name' => ['required', 'string', 'max:255'],
                'barcode' => ['required', 'string', 'max:255', Rule::unique('inventory_item_variants', 'barcode')->ignore($variant->id)],
                'sku' => ['nullable', 'string', 'max:255', Rule::unique('inventory_item_variants', 'sku')->ignore($variant->id)],
                'variant_description' => ['nullable', 'string'],
                'unit' => ['nullable', 'string', 'max:50'],
                'quantity' => ['required', 'integer', 'min:0'],
                'reorder_level' => ['required', 'integer', 'min:0'],
                'unit_cost' => ['nullable', 'numeric', 'min:0'],
                'location' => ['nullable', 'string', 'max:255'],
                'is_for_inspection' => ['nullable', 'boolean'],
            ]);

            $status = $validated['quantity'] <= 0
                ? 'Out of Stock'
                : ($validated['quantity'] <= $validated['reorder_level'] ? 'Low Stock' : 'In Stock');

            $isForInspection = $request->has('is_for_inspection')
                ? $request->boolean('is_for_inspection')
                : $variant->is_for_inspection;

            $variant->update(array_merge($validated, [
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
        })->name('inventory.variants.update');

        Route::patch('/inventory/{inventoryItem}/variants/{variant}/inspection', function (Request $request, InventoryItem $inventoryItem, InventoryItemVariant $variant) {
            abort_unless($variant->inventory_item_id === $inventoryItem->id, 404);

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
        })->name('inventory.variants.inspection');

        Route::delete('/inventory/{inventoryItem}/variants/{variant}', function (InventoryItem $inventoryItem, InventoryItemVariant $variant) {
            abort_unless($variant->inventory_item_id === $inventoryItem->id, 404);

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
        })->name('inventory.variants.destroy');

        // Requisitions Management & Review
        Route::get('/requisitions', function (Request $request) {
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
        })->name('requisitions.index');

        Route::get('/requisition', function () {
            return redirect()->route('admin.requisitions.index');
        })->name('requisition');

        Route::post('/requisitions/{requisition}/review', function (Request $request, RequisitionRequest $requisition) {
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
                                'inventory_item_id' => $variant->inventory_item_id,
                                'inventory_item_variant_id' => $variant->id,
                                'office_name' => $requisition->office?->office_name ?? 'General Office',
                                'item_name' => $item->item_name ?: ($variant->inventoryItem?->name ?? 'Standard Item'),
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
                        } else {
                            // Check for standalone inventory item
                            $standaloneItem = null;
                            if ($item->stock_no) {
                                $standaloneItem = InventoryItem::where('item_code', $item->stock_no)->lockForUpdate()->first();
                            }
                            if (! $standaloneItem && $item->item_name) {
                                $standaloneItem = InventoryItem::where('name', $item->item_name)->lockForUpdate()->first();
                            }

                            if ($standaloneItem) {
                                $standaloneItem->decrement('quantity', min($approvedQty, $standaloneItem->quantity));
                                $standaloneItem->status = $standaloneItem->quantity <= 0
                                    ? 'Out of Stock'
                                    : ($standaloneItem->quantity <= $standaloneItem->reorder_level ? 'Low Stock' : 'In Stock');
                                $standaloneItem->save();

                                InventoryLog::create([
                                    'action_type' => InventoryLogAction::Release,
                                    'batch_id' => $batchId,
                                    'reference_number' => $refNumber,
                                    'user_id' => auth()->id(),
                                    'office_id' => $requisition->office_id,
                                    'inventory_item_id' => $standaloneItem->id,
                                    'inventory_item_variant_id' => null,
                                    'office_name' => $requisition->office?->office_name ?? 'General Office',
                                    'item_name' => $standaloneItem->name,
                                    'variant_name' => null,
                                    'quantity' => $approvedQty,
                                    'quantity_before' => 0,
                                    'quantity_after' => 0,
                                    'recipient' => $requisition->requested_by_name ?? ($requisition->user?->full_name ?? $requisition->user?->name),
                                    'purpose' => $requisition->purpose,
                                    'remarks' => "Released {$approvedQty} units upon approval of Requisition #{$requisition->request_number}.".($itemRemarks ? " Note: {$itemRemarks}" : ''),
                                    'ip_address' => $request->ip(),
                                    'user_agent' => $request->userAgent(),
                                ]);
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
            });

            $msg = $validated['action'] === 'issue'
                ? "Requisition #{$requisition->request_number} has been approved and marked as issued ({$totalApprovedUnits} units)."
                : ($hasQuantityReduction
                    ? "Requisition #{$requisition->request_number} approved with quantity adjustments ({$totalApprovedUnits} units approved)."
                    : "Requisition #{$requisition->request_number} approved in full ({$totalApprovedUnits} units).");

            return redirect()->route('admin.requisitions.index')->with('status', $msg);
        })->name('requisitions.review');
    });

    // Staff Routes
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('staff.inventory');
        });

        Route::get('/dashboard', function (Request $request) {
            return redirect()->route('staff.inventory', $request->query());
        })->name('dashboard');

        Route::get('/inventory', function (Request $request) {
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
        })->name('inventory');

        // Staff Requisitions Selection & Forms
        Route::get('/requisition', function () {
            $user = auth()->user()->load('office');

            return view('staff.requisition', compact('user'));
        })->name('requisition.index');

        Route::get('/requisition/ris', function () {
            $user = auth()->user()->load('office');
            $activeOffice = $user->office;
            if ($activeOffice) {
                $activeOffice->load(['officeInventories.variant.inventoryItem']);
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
        })->name('requisition.ris');

        Route::post('/requisition/ris', function (Request $request) {
            $user = auth()->user()->load('office');
            $activeOffice = $user->office;

            $validated = $request->validate([
                'purpose' => ['nullable', 'string', 'max:1000'],
                'items' => ['required', 'array', 'min:1'],
                'items.*.id' => ['required'],
                'items.*.quantity' => ['required', 'integer', 'min:1'],
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
                    'responsibility_center_code' => '1061',
                    'requested_by_name' => $user->full_name,
                    'requested_by_designation' => 'Staff Officer',
                ]);

                foreach ($validated['items'] as $itemData) {
                    $variant = InventoryItemVariant::with('inventoryItem')->find($itemData['id']);
                    $qty = (int) $itemData['quantity'];
                    $cost = (float) ($variant?->unit_cost ?? $itemData['cost'] ?? 0);

                    RequisitionRequestItem::create([
                        'requisition_request_id' => $requisition->id,
                        'inventory_item_variant_id' => $variant?->id,
                        'stock_no' => $variant?->sku ?? ($itemData['sku'] ?? 'N/A'),
                        'item_name' => $variant?->inventoryItem?->name ?? ($itemData['name'] ?? 'Office Item'),
                        'variant_name' => $variant?->variant_name ?? ($itemData['variant'] ?? null),
                        'unit' => $variant?->effective_unit ?? ($itemData['unit'] ?? 'pcs'),
                        'quantity_requested' => $qty,
                        'quantity_issued' => 0,
                        'unit_cost' => $cost,
                        'total_cost' => round($qty * $cost, 2),
                        'status' => 'pending',
                    ]);
                }

                return $requisition;
            });

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Requisition {$requestNumber} has been successfully recorded!",
                    'request_number' => $requestNumber,
                    'request_id' => $requisition->id,
                    'redirect_url' => route('staff.request-stat'),
                ]);
            }

            return redirect()->route('staff.request-stat')->with('success', "Requisition {$requestNumber} has been submitted successfully!");
        })->name('requisition.ris.store');

        Route::get('/requisition/ris-form', function (Request $request) {
            $user = auth()->user()->load('office');
            $activeOffice = $user->office;
            $requisitionId = $request->query('requisition') ?? $request->query('id');
            $requisition = null;

            if ($requisitionId) {
                $requisition = RequisitionRequest::with(['items.variant.inventoryItem', 'office', 'user'])
                    ->where(function ($query) use ($user) {
                        if (! $user->isAdmin()) {
                            $query->where('office_id', $user->office_id)
                                ->orWhere('user_id', $user->id);
                        }
                    })
                    ->find($requisitionId);

                if ($requisition && $requisition->office) {
                    $activeOffice = $requisition->office;
                }
            }

            return view('staff.requisition.ris-form', compact('user', 'activeOffice', 'requisition'));
        })->name('requisition.ris-form');

        Route::get('/requisition/pr', function () {
            $user = auth()->user()->load('office');
            $activeOffice = $user->office;

            // Load variants with their parent inventory item (hiding warehouse stock/location info in view)
            $variants = InventoryItemVariant::with('inventoryItem')
                ->orderBy('variant_name')
                ->get();

            $categories = $variants->map(fn ($v) => $v->inventoryItem?->category)->filter()->unique()->values();

            return view('staff.requisition.pr', compact('user', 'activeOffice', 'variants', 'categories'));
        })->name('requisition.pr');

        Route::post('/requisition/pr', function (Request $request) {
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
        })->name('requisition.pr.store');

        // Request Status & Logs Monitoring
        Route::get('/request-stat', function () {
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
        })->name('request-stat');
    });

    Route::get('/requisitions', function () {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.requisitions.index')
            : redirect()->route('staff.requisition.index');
    })->name('requisitions.index');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
