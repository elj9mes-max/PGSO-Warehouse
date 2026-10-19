<?php

use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use App\Models\Office;
use App\Models\OfficeInventory;
use App\Models\RequisitionRequest;
use App\Models\RequisitionRequestItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unauthenticated user is redirected from staff requisition page', function () {
    $response = $this->get(route('staff.requisition.index'));
    $response->assertRedirect(route('login'));
});

test('staff user sees requisition page with RIS and PR selection cards', function () {
    $office = Office::create([
        'office_name' => "Provincial Assessor's Office",
        'office_code' => 'OFF-PAS-001',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create([
        'first_name' => 'Eduardo',
        'last_name' => 'Reyes',
    ]);

    $response = $this->actingAs($staff)->get(route('staff.requisition.index'));

    $response->assertOk();
    $response->assertSeeText('Create a Requisition');
    $response->assertSee("Provincial Assessor's Office");
    $response->assertSeeText('OFF-PAS-001');

    // Asserts both cards are displayed with proper action labels
    $response->assertSeeText('Requisition & Issue Slip (RIS)');
    $response->assertSeeText('Create RIS Request');
    $response->assertSee(route('staff.requisition.ris'));

    $response->assertSeeText('Purchase Request (PR)');
    $response->assertSeeText('Create PR Request');
    $response->assertSee(route('staff.requisition.pr'));
});

test('staff user can access RIS and PR requisition form pages', function () {
    $staff = User::factory()->staff()->create();

    $responseRis = $this->actingAs($staff)->get(route('staff.requisition.ris'));
    $responseRis->assertOk();
    $responseRis->assertSeeText('Requisition and Issue Slip (RIS)');

    $responsePr = $this->actingAs($staff)->get(route('staff.requisition.pr'));
    $responsePr->assertOk();
    $responsePr->assertSeeText('Purchase Request (PR)');
});

test('staff user sees their office stock inventory on RIS page with add to cart and side cart', function () {
    $office = Office::create([
        'office_name' => 'Provincial Accounting Office',
        'office_code' => 'OFF-PAC-001',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create([
        'first_name' => 'Carla',
        'last_name' => 'Dela Cruz',
    ]);

    $item = InventoryItem::create([
        'item_code' => 'ITM-BALLPEN-01',
        'name' => 'Retractable Ballpoint Pen 0.7mm Black',
        'description' => 'Smooth writing black ink ballpoint pen',
        'category' => 'Office Supplies',
        'unit' => 'boxes',
        'quantity' => 80,
        'reorder_level' => 10,
        'unit_cost' => 180.00,
        'status' => 'In Stock',
        'location' => 'Cabinet B-1',
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Box of 12 Pens',
        'barcode' => 'BAR-PEN-BLK-12',
        'sku' => 'SKU-PEN-BLK-12',
        'quantity' => 40,
        'available_for_transfer' => 40,
        'unit' => 'boxes',
        'reorder_level' => 5,
        'unit_cost' => 180.00,
        'location' => 'Cabinet B-1',
        'status' => 'In Stock',
    ]);

    OfficeInventory::create([
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
        'quantity' => 15,
    ]);

    $response = $this->actingAs($staff)->get(route('staff.requisition.ris'));

    $response->assertOk();
    $response->assertSeeText('Provincial Accounting Office');
    $response->assertSeeText('OFF-PAC-001');
    $response->assertSeeText('Retractable Ballpoint Pen 0.7mm Black');
    $response->assertSeeText('Box of 12 Pens');
    $response->assertSeeText('SKU-PEN-BLK-12');
    $response->assertSeeText('15');
    $response->assertSeeText('Add to Cart');
    $response->assertSeeText('RIS Request Cart');
});

test('staff user can access official printable RIS form page matching template', function () {
    $office = Office::create([
        'office_name' => 'Provincial General Services Office',
        'office_code' => 'PGSO',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create();

    $response = $this->actingAs($staff)->get(route('staff.requisition.ris-form'));

    $response->assertOk();
    $response->assertSeeText('PROVINCIAL GOVERNMENT OF AURORA');
    $response->assertSeeText('REQUISITION AND ISSUE SLIP (RIS)');
    $response->assertSeeText('RESPONSIBILITY CENTER');
    $response->assertSeeText('REQUISITION');
    $response->assertSeeText('ISSUANCE');
    $response->assertSeeText('RICARDO Q. BAUTISTA');
    $response->assertSeeText('RAYMOND G. AMAT');
    $response->assertSeeText('IMEE SALAMANCA');
});

test('staff user can access printable RIS form populated with existing requisition items', function () {
    $office = Office::create([
        'office_name' => 'Provincial Engineering Office',
        'office_code' => 'PEO-001',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create([
        'first_name' => 'Carlos',
        'last_name' => 'Mendoza',
    ]);

    $requisition = RequisitionRequest::factory()->create([
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'request_number' => 'RIS-2026-PEO-0099',
        'ris_no' => 'RIS-2026-PEO-0099',
        'purpose' => 'Urgent road maintenance administrative supplies',
    ]);

    RequisitionRequestItem::factory()->create([
        'requisition_request_id' => $requisition->id,
        'item_name' => 'Bond Paper A4',
        'variant_name' => 'Substance 20',
        'stock_no' => 'STK-BOND-A4',
        'unit' => 'reams',
        'quantity_requested' => 12,
    ]);

    $response = $this->actingAs($staff)->get(route('staff.requisition.ris-form', ['requisition' => $requisition->id]));

    $response->assertOk();
    $response->assertSee('RIS-2026-PEO-0099');
    $response->assertSee('Provincial Engineering Office');
    $response->assertSee('Urgent road maintenance administrative supplies');
    $response->assertSee('STK-BOND-A4');
    $response->assertSee('Bond Paper A4');
});

test('staff user can access request status monitoring page', function () {
    $office = Office::create([
        'office_name' => 'Provincial General Services Office',
        'office_code' => 'PGSO',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create();

    $request = RequisitionRequest::factory()->create([
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'request_number' => 'RIS-202610-9999',
        'purpose' => 'Urgent office procurement supplies',
    ]);

    RequisitionRequestItem::factory()->create([
        'requisition_request_id' => $request->id,
        'item_name' => 'A4 Paper',
        'quantity_requested' => 10,
    ]);

    $response = $this->actingAs($staff)->get(route('staff.request-stat'));

    $response->assertOk();
    $response->assertSeeText('Request Status & Activity Logs');
    $response->assertSeeText('RIS-202610-9999');
    $response->assertSeeText('Urgent office procurement supplies');
    $response->assertSeeText('Provincial General Services Office');
});

test('staff user can confirm and save requisition request to the database', function () {
    $office = Office::create([
        'office_name' => 'Provincial General Services Office',
        'office_code' => 'PGSO',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create([
        'first_name' => 'Ricardo',
        'last_name' => 'Bautista',
    ]);

    $item = InventoryItem::create([
        'item_code' => 'ITM-PEN-01',
        'name' => 'Ballpoint Pen Black',
        'category' => 'Supplies',
        'unit' => 'boxes',
        'quantity' => 50,
        'reorder_level' => 5,
        'unit_cost' => 150.00,
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Box of 12',
        'barcode' => 'BAR-PEN-01',
        'sku' => 'SKU-PEN-BLK',
        'unit' => 'boxes',
        'quantity' => 20,
        'unit_cost' => 150.00,
    ]);

    $payload = [
        'purpose' => 'Quarterly office supply requisition for documentation',
        'items' => [
            [
                'id' => $variant->id,
                'name' => $item->name,
                'variant' => $variant->variant_name,
                'sku' => $variant->sku,
                'unit' => $variant->unit,
                'cost' => $variant->unit_cost,
                'quantity' => 4,
            ],
        ],
    ];

    $response = $this->actingAs($staff)->postJson(route('staff.requisition.ris.store'), $payload);

    $response->assertOk();
    $response->assertJson([
        'success' => true,
    ]);

    $this->assertDatabaseHas('requisition_requests', [
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'request_type' => 'RIS',
        'status' => 'pending',
        'purpose' => 'Quarterly office supply requisition for documentation',
        'requested_by_name' => $staff->full_name,
    ]);

    $this->assertDatabaseHas('requisition_request_items', [
        'inventory_item_variant_id' => $variant->id,
        'stock_no' => 'SKU-PEN-BLK',
        'quantity_requested' => 4,
        'total_cost' => 600.00,
    ]);
});

test('staff user sees inventory item variants on PR page showing only variant name, code, and barcode', function () {
    $office = Office::create([
        'office_name' => 'Provincial Engineering Office',
        'office_code' => 'PEO-01',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create([
        'first_name' => 'Marco',
        'last_name' => 'Santos',
    ]);

    $item = InventoryItem::create([
        'item_code' => 'ITM-PAPER-A4',
        'name' => 'Copy Paper Multipurpose 80gsm',
        'description' => 'White multipurpose A4 copy paper',
        'category' => 'Paper Products',
        'unit' => 'reams',
        'quantity' => 150,
        'reorder_level' => 20,
        'unit_cost' => 240.00,
        'location' => 'RACK-SEC-99-HIDDEN',
        'status' => 'In Stock',
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'A4 Ream (500 Sheets)',
        'barcode' => 'BAR-88001122',
        'sku' => 'SKU-A4-500S',
        'quantity' => 150,
        'available_for_transfer' => 150,
        'unit' => 'reams',
        'unit_cost' => 240.00,
        'location' => 'RACK-SEC-99-HIDDEN',
        'status' => 'In Stock',
    ]);

    $response = $this->actingAs($staff)->get(route('staff.requisition.pr'));

    $response->assertOk();
    $response->assertSeeText('Purchase Request (PR)');
    $response->assertSeeText('Appendix 60 • LGU Standard');
    $response->assertSeeText('A4 Ream (500 Sheets)');
    $response->assertSeeText('Copy Paper Multipurpose 80gsm');
    $response->assertSeeText('SKU-A4-500S');
    $response->assertSeeText('BAR-88001122');
    $response->assertSeeText('Add to PR');

    // Confirm internal warehouse location is hidden from view
    $response->assertDontSeeText('RACK-SEC-99-HIDDEN');
});

test('staff user can submit Purchase Request creating records in purchase_requests and purchase_request_items', function () {
    $office = Office::create([
        'office_name' => 'Provincial Health Office',
        'office_code' => 'PHO-01',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create([
        'first_name' => 'Elena',
        'last_name' => 'Ramos',
    ]);

    $item = InventoryItem::create([
        'item_code' => 'ITM-GLOVES-01',
        'name' => 'Nitrile Examination Gloves',
        'category' => 'Medical Supplies',
        'unit' => 'boxes',
        'quantity' => 300,
        'unit_cost' => 350.00,
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Size Medium - Box of 100',
        'barcode' => 'BAR-GLV-MED-100',
        'sku' => 'SKU-GLV-MED',
        'unit' => 'boxes',
        'quantity' => 100,
        'unit_cost' => 350.00,
    ]);

    $payload = [
        'purpose' => 'Procurement of examination gloves for community medical missions',
        'responsibility_center_code' => '1061',
        'items' => [
            [
                'id' => $variant->id,
                'name' => $item->name,
                'variant_name' => $variant->variant_name,
                'quantity' => 5,
                'estimated_unit_cost' => 350.00,
                'unit' => 'boxes',
            ],
        ],
    ];

    $response = $this->actingAs($staff)->postJson(route('staff.requisition.pr.store'), $payload);

    $response->assertOk();
    $response->assertJson([
        'success' => true,
    ]);

    $this->assertDatabaseHas('purchase_requests', [
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'status' => 'pending',
        'purpose' => 'Procurement of examination gloves for community medical missions',
        'total_estimated_cost' => 1750.00,
    ]);

    $this->assertDatabaseHas('purchase_request_items', [
        'inventory_item_variant_id' => $variant->id,
        'item_code' => 'SKU-GLV-MED',
        'barcode' => 'BAR-GLV-MED-100',
        'variant_name' => 'Size Medium - Box of 100',
        'quantity_requested' => 5,
        'estimated_unit_cost' => 350.00,
        'estimated_total_cost' => 1750.00,
    ]);

    // Also assert synced RequisitionRequest record is created
    $this->assertDatabaseHas('requisition_requests', [
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'request_type' => 'PR',
        'status' => 'pending',
        'purpose' => 'Procurement of examination gloves for community medical missions',
    ]);
});
