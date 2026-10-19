<?php

use App\Enums\InventoryLogAction;
use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use App\Models\InventoryLog;
use App\Models\Office;
use App\Models\OfficeInventory;
use App\Models\RequisitionRequest;
use App\Models\RequisitionRequestItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unauthenticated user is redirected to login from admin requisitions', function () {
    $response = $this->get(route('admin.requisitions.index'));
    $response->assertRedirect(route('login'));
});

test('staff user cannot access admin requisitions page and is redirected', function () {
    $staff = User::factory()->staff()->create();

    $response = $this->actingAs($staff)->get(route('admin.requisitions.index'));
    $response->assertRedirect(route('staff.dashboard'));
});

test('admin can view requisitions dashboard with metrics and request list', function () {
    $admin = User::factory()->admin()->create();

    $office = Office::create([
        'office_name' => 'Provincial Planning and Development Office',
        'office_code' => 'PPDO',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create([
        'first_name' => 'Ana',
        'last_name' => 'Santos',
    ]);

    $req = RequisitionRequest::factory()->create([
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'request_number' => 'RIS-202610-0088',
        'status' => 'pending',
        'purpose' => 'Annual project planning materials',
        'requested_by_name' => 'Ana Santos',
        'requested_by_designation' => 'Planning Officer I',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.requisitions.index'));

    $response->assertOk();
    $response->assertSeeText('Requisitions & Stock Requests');
    $response->assertSeeText('RIS-202610-0088');
    $response->assertSeeText('Provincial Planning and Development Office');
    $response->assertSeeText('Ana Santos');
    $response->assertSeeText('Pending Review');
});

test('admin can approve a requisition in full without quantity reduction', function () {
    $admin = User::factory()->admin()->create([
        'first_name' => 'Maria',
        'last_name' => 'Cruz',
    ]);

    $office = Office::create([
        'office_name' => 'Provincial General Services Office',
        'office_code' => 'PGSO',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create();

    $item = InventoryItem::create([
        'name' => 'Ballpoint Pen Black 0.7mm',
        'item_code' => 'PEN-BLK-01',
        'category' => 'Supplies',
        'unit' => 'boxes',
        'quantity' => 100,
        'reorder_level' => 10,
        'unit_cost' => 150.00,
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Box of 12 pcs',
        'barcode' => 'BAR-PEN-BLK',
        'sku' => 'SKU-PEN-BOX',
        'unit' => 'boxes',
        'quantity' => 50,
        'unit_cost' => 150.00,
    ]);

    $request = RequisitionRequest::create([
        'request_number' => 'RIS-202610-0101',
        'request_type' => 'RIS',
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'status' => 'pending',
        'purpose' => 'Office supplies for dispatch',
    ]);

    $lineItem = RequisitionRequestItem::create([
        'requisition_request_id' => $request->id,
        'inventory_item_variant_id' => $variant->id,
        'stock_no' => $variant->sku,
        'item_name' => $item->name,
        'variant_name' => $variant->variant_name,
        'unit' => 'boxes',
        'quantity_requested' => 10,
        'quantity_issued' => 0,
        'unit_cost' => 150.00,
        'total_cost' => 1500.00,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.requisitions.review', $request), [
        'action' => 'approve',
        'items' => [
            [
                'id' => $lineItem->id,
                'quantity' => 10,
                'remarks' => null,
            ],
        ],
        'approved_by_name' => 'Maria Cruz',
        'approved_by_designation' => 'PGDH - PGSO',
    ]);

    $response->assertRedirect(route('admin.requisitions.index'));
    $response->assertSessionHas('status');

    $request->refresh();
    $lineItem->refresh();

    expect($request->status)->toBe('approved')
        ->and($request->approved_by_id)->toBe($admin->id)
        ->and($request->approved_at)->not->toBeNull()
        ->and($lineItem->quantity_issued)->toBe(10)
        ->and($lineItem->status)->toBe('approved');
});

test('admin must provide a reason when reducing requested quantity', function () {
    $admin = User::factory()->admin()->create();
    $office = Office::create([
        'office_name' => 'Provincial Legal Office',
        'office_code' => 'PLO',
    ]);
    $staff = User::factory()->staff()->forOffice($office)->create();

    $request = RequisitionRequest::create([
        'request_number' => 'RIS-202610-0102',
        'request_type' => 'RIS',
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'status' => 'pending',
        'purpose' => 'Quarterly paper request',
    ]);

    $lineItem = RequisitionRequestItem::create([
        'requisition_request_id' => $request->id,
        'item_name' => 'A4 Bond Paper',
        'unit' => 'reams',
        'quantity_requested' => 20,
        'quantity_issued' => 0,
    ]);

    // Attempting reduction from 20 to 5 without providing reason should fail validation
    $response = $this->actingAs($admin)->post(route('admin.requisitions.review', $request), [
        'action' => 'approve',
        'items' => [
            [
                'id' => $lineItem->id,
                'quantity' => 5,
            ],
        ],
        'reason' => '',
    ]);

    $response->assertSessionHasErrors(['reason']);

    // Now providing valid reason for reduction
    $successResponse = $this->actingAs($admin)->post(route('admin.requisitions.review', $request), [
        'action' => 'approve',
        'items' => [
            [
                'id' => $lineItem->id,
                'quantity' => 5,
            ],
        ],
        'reason' => 'Buffer stock quota limited in warehouse',
    ]);

    $successResponse->assertRedirect(route('admin.requisitions.index'));

    $request->refresh();
    $lineItem->refresh();

    expect($request->status)->toBe('approved_with_adjustments')
        ->and($request->rejection_reason)->toBe('Buffer stock quota limited in warehouse')
        ->and($lineItem->quantity_issued)->toBe(5)
        ->and($lineItem->status)->toBe('adjusted')
        ->and($lineItem->remarks)->toContain('Reduced from 20 to 5');
});

test('admin must provide a reason when rejecting a requisition', function () {
    $admin = User::factory()->admin()->create();
    $office = Office::create([
        'office_name' => 'Provincial Engineering Office',
        'office_code' => 'PEO',
    ]);
    $staff = User::factory()->staff()->forOffice($office)->create();

    $request = RequisitionRequest::create([
        'request_number' => 'RIS-202610-0103',
        'request_type' => 'RIS',
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'status' => 'pending',
        'purpose' => 'Heavy duty tools',
    ]);

    $lineItem = RequisitionRequestItem::create([
        'requisition_request_id' => $request->id,
        'item_name' => 'Measurement Tape 50m',
        'unit' => 'pcs',
        'quantity_requested' => 5,
        'quantity_issued' => 0,
    ]);

    // Rejecting without reason should fail validation
    $response = $this->actingAs($admin)->post(route('admin.requisitions.review', $request), [
        'action' => 'reject',
        'reason' => '',
    ]);

    $response->assertSessionHasErrors(['reason']);

    // Rejecting with reason succeeds
    $successResponse = $this->actingAs($admin)->post(route('admin.requisitions.review', $request), [
        'action' => 'reject',
        'reason' => 'Duplicate requisition: Already requisitioned under RIS-202610-0045',
    ]);

    $successResponse->assertRedirect(route('admin.requisitions.index'));

    $request->refresh();
    $lineItem->refresh();

    expect($request->status)->toBe('rejected')
        ->and($request->rejection_reason)->toBe('Duplicate requisition: Already requisitioned under RIS-202610-0045')
        ->and($lineItem->status)->toBe('rejected');
});

test('admin can approve and immediately issue a requisition', function () {
    $admin = User::factory()->admin()->create([
        'first_name' => 'Warehouse',
        'last_name' => 'Officer',
    ]);
    $office = Office::create([
        'office_name' => 'Provincial Health Office',
        'office_code' => 'PHO',
    ]);
    $staff = User::factory()->staff()->forOffice($office)->create();

    $request = RequisitionRequest::create([
        'request_number' => 'RIS-202610-0104',
        'request_type' => 'RIS',
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'status' => 'pending',
        'purpose' => 'Clinic supplies',
    ]);

    $lineItem = RequisitionRequestItem::create([
        'requisition_request_id' => $request->id,
        'item_name' => 'Surgical Face Masks',
        'unit' => 'boxes',
        'quantity_requested' => 15,
        'quantity_issued' => 0,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.requisitions.review', $request), [
        'action' => 'issue',
        'items' => [
            [
                'id' => $lineItem->id,
                'quantity' => 15,
            ],
        ],
        'issued_by_name' => 'Warehouse Officer',
        'issued_by_designation' => 'AA - III',
    ]);

    $response->assertRedirect(route('admin.requisitions.index'));

    $request->refresh();
    $lineItem->refresh();

    expect($request->status)->toBe('issued')
        ->and($request->issued_by_id)->toBe($admin->id)
        ->and($request->issued_at)->not->toBeNull()
        ->and($lineItem->quantity_issued)->toBe(15)
        ->and($lineItem->status)->toBe('issued');
});

test('approving a requisition reduces quantity in office inventory and main inventory, and records release log', function () {
    $admin = User::factory()->admin()->create();
    $office = Office::create([
        'office_name' => 'Provincial Information Office',
        'office_code' => 'PIO',
    ]);
    $staff = User::factory()->staff()->forOffice($office)->create();

    $item = InventoryItem::create([
        'name' => 'USB Flash Drive 64GB',
        'item_code' => 'USB-64-01',
        'category' => 'IT Equipment',
        'unit' => 'pcs',
        'quantity' => 50,
        'reorder_level' => 10,
        'unit_cost' => 350.00,
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => '64GB Kingston 3.0',
        'barcode' => 'BAR-USB-64',
        'sku' => 'SKU-USB-64',
        'unit' => 'pcs',
        'quantity' => 50,
        'available_for_transfer' => 40,
        'reorder_level' => 10,
        'unit_cost' => 350.00,
    ]);

    $officeInventory = OfficeInventory::create([
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
        'quantity' => 15,
    ]);

    $request = RequisitionRequest::create([
        'request_number' => 'RIS-202610-0201',
        'request_type' => 'RIS',
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'status' => 'pending',
        'purpose' => 'Media coverage photo storage',
    ]);

    $lineItem = RequisitionRequestItem::create([
        'requisition_request_id' => $request->id,
        'inventory_item_variant_id' => $variant->id,
        'stock_no' => $variant->sku,
        'item_name' => $item->name,
        'variant_name' => $variant->variant_name,
        'unit' => 'pcs',
        'quantity_requested' => 5,
        'quantity_issued' => 0,
        'unit_cost' => 350.00,
        'total_cost' => 1750.00,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.requisitions.review', $request), [
        'action' => 'approve',
        'items' => [
            [
                'id' => $lineItem->id,
                'quantity' => 5,
            ],
        ],
        'approved_by_name' => 'Admin Approver',
        'approved_by_designation' => 'PGDH - PGSO',
    ]);

    $response->assertRedirect(route('admin.requisitions.index'));

    $request->refresh();
    $lineItem->refresh();
    $officeInventory->refresh();
    $variant->refresh();
    $item->refresh();

    // Requisition status
    expect($request->status)->toBe('approved')
        ->and($lineItem->quantity_issued)->toBe(5);

    // Office Inventory should be reduced from 15 to 10
    expect($officeInventory->quantity)->toBe(10);

    // Main Inventory Variant should be reduced from 50 to 45
    expect($variant->quantity)->toBe(45);

    // Parent Inventory Item total quantity should be 45
    expect($item->quantity)->toBe(45);

    // InventoryLog should have recorded a release
    $this->assertDatabaseHas('inventory_logs', [
        'action_type' => InventoryLogAction::Release->value,
        'office_id' => $office->id,
        'inventory_item_variant_id' => $variant->id,
        'quantity' => 5,
        'quantity_before' => 15,
        'quantity_after' => 10,
    ]);
});

test('approving a requisition that exhausts office inventory removes the office inventory record', function () {
    $admin = User::factory()->admin()->create();
    $office = Office::create([
        'office_name' => 'Provincial Budget Office',
        'office_code' => 'PBO',
    ]);
    $staff = User::factory()->staff()->forOffice($office)->create();

    $item = InventoryItem::create([
        'name' => 'Calculators Scientific',
        'item_code' => 'CALC-01',
        'category' => 'Equipment',
        'unit' => 'pcs',
        'quantity' => 20,
        'reorder_level' => 5,
        'unit_cost' => 500.00,
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Casio FX-991ES',
        'barcode' => 'BAR-CALC-01',
        'sku' => 'SKU-CALC-01',
        'unit' => 'pcs',
        'quantity' => 20,
        'unit_cost' => 500.00,
    ]);

    $officeInventory = OfficeInventory::create([
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
        'quantity' => 4,
    ]);

    $request = RequisitionRequest::create([
        'request_number' => 'RIS-202610-0202',
        'request_type' => 'RIS',
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'status' => 'pending',
        'purpose' => 'Annual budget calculation prep',
    ]);

    $lineItem = RequisitionRequestItem::create([
        'requisition_request_id' => $request->id,
        'inventory_item_variant_id' => $variant->id,
        'stock_no' => $variant->sku,
        'item_name' => $item->name,
        'variant_name' => $variant->variant_name,
        'unit' => 'pcs',
        'quantity_requested' => 4,
        'quantity_issued' => 0,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.requisitions.review', $request), [
        'action' => 'approve',
        'items' => [
            [
                'id' => $lineItem->id,
                'quantity' => 4,
            ],
        ],
    ]);

    $response->assertRedirect(route('admin.requisitions.index'));

    // Office inventory record should be deleted as it hit 0
    $this->assertDatabaseMissing('office_inventories', [
        'id' => $officeInventory->id,
    ]);

    $variant->refresh();
    expect($variant->quantity)->toBe(16);
});

test('approving with adjusted reduced quantity only deducts the approved quantity from office and main inventory', function () {
    $admin = User::factory()->admin()->create();
    $office = Office::create([
        'office_name' => 'Provincial Agriculture Office',
        'office_code' => 'PAO',
    ]);
    $staff = User::factory()->staff()->forOffice($office)->create();

    $item = InventoryItem::create([
        'name' => 'Seed Trays Plastic',
        'item_code' => 'TRAY-01',
        'category' => 'Agriculture',
        'unit' => 'pcs',
        'quantity' => 100,
        'reorder_level' => 10,
        'unit_cost' => 50.00,
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => '104-Hole Tray',
        'barcode' => 'BAR-TRAY-104',
        'sku' => 'SKU-TRAY-104',
        'unit' => 'pcs',
        'quantity' => 100,
        'unit_cost' => 50.00,
    ]);

    $officeInventory = OfficeInventory::create([
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
        'quantity' => 25,
    ]);

    $request = RequisitionRequest::create([
        'request_number' => 'RIS-202610-0203',
        'request_type' => 'RIS',
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'status' => 'pending',
        'purpose' => 'Seedling planting batch',
    ]);

    $lineItem = RequisitionRequestItem::create([
        'requisition_request_id' => $request->id,
        'inventory_item_variant_id' => $variant->id,
        'stock_no' => $variant->sku,
        'item_name' => $item->name,
        'variant_name' => $variant->variant_name,
        'unit' => 'pcs',
        'quantity_requested' => 20,
        'quantity_issued' => 0,
    ]);

    // Admin approves only 8 units instead of 20 with justification reason
    $response = $this->actingAs($admin)->post(route('admin.requisitions.review', $request), [
        'action' => 'approve',
        'items' => [
            [
                'id' => $lineItem->id,
                'quantity' => 8,
            ],
        ],
        'reason' => 'Remaining trays allocated to municipal demo farms',
    ]);

    $response->assertRedirect(route('admin.requisitions.index'));

    $officeInventory->refresh();
    $variant->refresh();

    // Office inventory reduced by 8 from 25 -> 17
    expect($officeInventory->quantity)->toBe(17);

    // Variant reduced by 8 from 100 -> 92
    expect($variant->quantity)->toBe(92);
});

test('rejecting a requisition does not reduce office inventory or main inventory', function () {
    $admin = User::factory()->admin()->create();
    $office = Office::create([
        'office_name' => 'Provincial Social Welfare and Development Office',
        'office_code' => 'PSWDO',
    ]);
    $staff = User::factory()->staff()->forOffice($office)->create();

    $item = InventoryItem::create([
        'name' => 'Relief Pack Blankets',
        'item_code' => 'BLANKET-01',
        'category' => 'Welfare',
        'unit' => 'pcs',
        'quantity' => 200,
        'reorder_level' => 20,
        'unit_cost' => 120.00,
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Thermal Fleece',
        'barcode' => 'BAR-BLANKET-01',
        'sku' => 'SKU-BLANKET-01',
        'unit' => 'pcs',
        'quantity' => 200,
        'unit_cost' => 120.00,
    ]);

    $officeInventory = OfficeInventory::create([
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
        'quantity' => 50,
    ]);

    $request = RequisitionRequest::create([
        'request_number' => 'RIS-202610-0204',
        'request_type' => 'RIS',
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'status' => 'pending',
        'purpose' => 'Emergency disaster prep',
    ]);

    $lineItem = RequisitionRequestItem::create([
        'requisition_request_id' => $request->id,
        'inventory_item_variant_id' => $variant->id,
        'stock_no' => $variant->sku,
        'item_name' => $item->name,
        'variant_name' => $variant->variant_name,
        'unit' => 'pcs',
        'quantity_requested' => 30,
        'quantity_issued' => 0,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.requisitions.review', $request), [
        'action' => 'reject',
        'reason' => 'Separate relief logistics pipeline already activated',
    ]);

    $response->assertRedirect(route('admin.requisitions.index'));

    $officeInventory->refresh();
    $variant->refresh();

    // Neither office inventory nor main inventory should change
    expect($officeInventory->quantity)->toBe(50);
    expect($variant->quantity)->toBe(200);

    // No release log created
    expect(InventoryLog::where('reference_number', 'RIS-202610-0204')->count())->toBe(0);
});
