<?php

use App\Enums\AccountLevel;
use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use App\Models\Office;
use App\Models\RequisitionRequest;
use App\Models\RequisitionRequestItem;
use App\Models\SsmiReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('ssmi report escapes html tags in ris column headers preventing stored xss', function () {
    $admin = User::factory()->admin()->create();

    $xssPayload = '<script>alert("XSS")</script>';
    $report = SsmiReport::create([
        'report_no' => 'SSMI-TEST-001',
        'ris_columns' => [$xssPayload, 'RIS-102', '', '', '', '', '', ''],
        'status' => 'draft',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.reports.ssmi', ['report_id' => $report->id]));

    $response->assertStatus(200);
    // The response should NOT contain unescaped raw script tag
    $response->assertDontSee($xssPayload, false);
    // The response should contain the HTML-escaped entities
    $response->assertSee(e($xssPayload), false);
});

test('administrator cannot demote their own account level to staff', function () {
    $admin = User::factory()->admin()->create([
        'first_name' => 'Root',
        'last_name' => 'Admin',
        'account_level' => AccountLevel::Admin,
    ]);

    $response = $this->actingAs($admin)->put(route('admin.accounts.update', $admin), [
        'first_name' => 'Root',
        'last_name' => 'Admin',
        'account_level' => AccountLevel::Staff->value,
    ]);

    $response->assertRedirect(route('admin.accounts.index'));
    $response->assertSessionHas('error', 'You cannot change your own administrative account level.');

    $admin->refresh();
    expect($admin->account_level)->toBe(AccountLevel::Admin);
});

test('administrator can edit other user account level', function () {
    $admin = User::factory()->admin()->create();
    $staff = User::factory()->staff()->create([
        'first_name' => 'Juan',
        'last_name' => 'Dela Cruz',
    ]);

    $response = $this->actingAs($admin)->put(route('admin.accounts.update', $staff), [
        'first_name' => 'Juan',
        'last_name' => 'Dela Cruz',
        'account_level' => AccountLevel::Admin->value,
    ]);

    $response->assertRedirect(route('admin.accounts.index'));
    $response->assertSessionHas('status');

    $staff->refresh();
    expect($staff->account_level)->toBe(AccountLevel::Admin);
});

test('staff ris requisition rejects negative unit cost and invalid quantities', function () {
    $office = Office::create([
        'office_name' => 'Provincial Engineering Office',
        'office_code' => 'PEO',
    ]);
    $staff = User::factory()->staff()->forOffice($office)->create();

    $response = $this->actingAs($staff)->post(route('staff.requisition.ris.store'), [
        'purpose' => 'Test requisition',
        'items' => [
            [
                'id' => 99999,
                'name' => 'Pen',
                'cost' => -50.00,
                'quantity' => -5,
            ],
        ],
    ]);

    $response->assertSessionHasErrors(['items.0.cost', 'items.0.quantity']);
});

test('admin requisition review rejects approved quantity exceeding requested quantity', function () {
    $admin = User::factory()->admin()->create();
    $office = Office::create([
        'office_name' => 'Provincial Health Office',
        'office_code' => 'PHO',
    ]);
    $staff = User::factory()->staff()->forOffice($office)->create();

    $request = RequisitionRequest::create([
        'request_number' => 'RIS-202610-0099',
        'request_type' => 'RIS',
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'status' => 'pending',
        'purpose' => 'Medical supplies',
    ]);

    $lineItem = RequisitionRequestItem::create([
        'requisition_request_id' => $request->id,
        'item_name' => 'Surgical Gloves',
        'unit' => 'boxes',
        'quantity_requested' => 5,
        'quantity_issued' => 0,
    ]);

    // Admin attempts to approve 50 units when only 5 were requested
    $response = $this->actingAs($admin)->post(route('admin.requisitions.review', $request), [
        'action' => 'approve',
        'items' => [
            [
                'id' => $lineItem->id,
                'quantity' => 50,
            ],
        ],
    ]);

    $response->assertSessionHasErrors(['items']);

    $lineItem->refresh();
    expect($lineItem->quantity_issued)->toBe(0);
});

test('admin requisition review rejects issuing quantity exceeding available variant stock', function () {
    $admin = User::factory()->admin()->create();
    $office = Office::create([
        'office_name' => 'Governor Office',
        'office_code' => 'GO',
    ]);
    $staff = User::factory()->staff()->forOffice($office)->create();

    $item = InventoryItem::create([
        'name' => 'Specialized Battery',
        'item_code' => 'BAT-SPEC',
        'category' => 'Electronics',
        'unit' => 'pcs',
        'quantity' => 2,
        'reorder_level' => 5,
        'unit_cost' => 500.00,
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Lithium 12V',
        'barcode' => 'BAR-BAT-001',
        'sku' => 'BAT-SPEC-01',
        'quantity' => 2, // only 2 units in stock
        'available_for_transfer' => 2,
        'unit_cost' => 500.00,
        'unit' => 'pcs',
        'status' => 'Low Stock',
        'is_for_inspection' => false,
    ]);

    $request = RequisitionRequest::create([
        'request_number' => 'RIS-202610-0100',
        'request_type' => 'RIS',
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'status' => 'pending',
        'purpose' => 'Emergency generator test',
    ]);

    $lineItem = RequisitionRequestItem::create([
        'requisition_request_id' => $request->id,
        'inventory_item_variant_id' => $variant->id,
        'stock_no' => $variant->sku,
        'item_name' => $item->name,
        'variant_name' => $variant->variant_name,
        'unit' => 'pcs',
        'quantity_requested' => 10,
        'quantity_issued' => 0,
    ]);

    // Admin attempts to approve 10 units when only 2 exist in warehouse
    $response = $this->actingAs($admin)->post(route('admin.requisitions.review', $request), [
        'action' => 'issue',
        'items' => [
            [
                'id' => $lineItem->id,
                'quantity' => 10,
            ],
        ],
    ]);

    $response->assertSessionHasErrors(['items']);

    $variant->refresh();
    expect($variant->quantity)->toBe(2); // Stock remains unchanged
});
