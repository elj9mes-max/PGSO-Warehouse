<?php

use App\Models\InventoryBatch;
use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use App\Models\Office;
use App\Models\OfficeInventory;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
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
    $response->assertSeeText('RIS and Common terms');
    $response->assertSee("Provincial Assessor's Office");
    $response->assertSeeText('OFF-PAS-001');

    // Asserts both cards are displayed with proper action labels
    $response->assertSeeText('Requisition & Issue Slip (RIS)');
    $response->assertSeeText('Create RIS Request');
    $response->assertSee(route('staff.requisition.ris'));

    $response->assertSeeText('Common Terms for PR');
    $response->assertSeeText('Browse Common Items');
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
    $response->assertDontSeeText('Add to PR');
    $response->assertSeeText('Common Terms & Items Directory');
    $response->assertSeeText('Est. Unit Cost');

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

test('staff user can access printable PR form matching the official government template', function () {
    $office = Office::create([
        'office_name' => 'PGSO',
        'office_code' => 'PGSO-001',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create();

    $response = $this->actingAs($staff)->get(route('staff.requisition.pr-form'));

    $response->assertOk();
    $response->assertSeeText('Republic of the Philippines');
    $response->assertSeeText('PROVINCIAL GOVERNMENT OF AURORA');
    $response->assertSeeText('PURCHASE REQUEST');
    $response->assertSeeText('Item Description');
    $response->assertSee('Stock');
    $response->assertSee('Number');
    $response->assertSee('Unit Cost');
    $response->assertSee('Total Cost');
    $response->assertSee('TOTAL');
    $response->assertSee('Purpose:');
    $response->assertSee('Charging:');
    $response->assertSee('Requested by:');
    $response->assertSee('Approved by:');
    $response->assertSee('HON. ISIDRO P. GALBAN');
    $response->assertSee('GOVERNOR');
});

test('staff user can access printable PR form populated with specific purchase request items', function () {
    $office = Office::create([
        'office_name' => 'Provincial General Services Office',
        'office_code' => 'PGSO',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create([
        'first_name' => 'Ricardo',
        'last_name' => 'Bautista',
    ]);

    $pr = PurchaseRequest::create([
        'pr_number' => 'PR-2026-PGSO-0042',
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'status' => 'pending',
        'purpose' => 'FOR USE OF PGSO',
        'responsibility_center_code' => '1061 1-09-01-020',
        'requested_by_name' => 'RICARDO Q. BAUTISTA',
        'requested_by_designation' => 'PGDH-PGSO',
        'approved_by_name' => 'HON. ISIDRO P. GALBAN',
        'approved_by_designation' => 'GOVERNOR',
        'total_estimated_cost' => 1000000.00,
    ]);

    PurchaseRequestItem::create([
        'purchase_request_id' => $pr->id,
        'item_name' => 'SUPPLY & DELIVERY OF COMPUTER SOFTWARE',
        'description' => 'Upgrading of Property & Supply Management Information Software',
        'unit' => 'lot',
        'quantity_requested' => 1,
        'estimated_unit_cost' => 1000000.00,
        'estimated_total_cost' => 1000000.00,
    ]);

    $response = $this->actingAs($staff)->get(route('staff.requisition.pr-form', ['pr' => $pr->id]));

    $response->assertOk();
    $response->assertSee('PR-2026-PGSO-0042');
    $response->assertSee('FOR USE OF PGSO');
    $response->assertSee('1061 1-09-01-020');
    $response->assertSeeText('SUPPLY & DELIVERY OF COMPUTER SOFTWARE');
    $response->assertSeeText('Upgrading of Property & Supply Management Information Software');
    $response->assertSee('1,000,000.00');
    $response->assertSee('RICARDO Q. BAUTISTA');
    $response->assertSee('HON. ISIDRO P. GALBAN');
});

test('unauthenticated user cannot access printable PR form', function () {
    $response = $this->get(route('staff.requisition.pr-form'));

    $response->assertRedirect(route('login'));
});

test('submitting RIS request correctly resolves variant when office inventory ID collides with another variant ID', function () {
    $office = Office::create([
        'office_name' => 'Provincial General Services Office',
        'office_code' => 'PGSO',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create([
        'first_name' => 'Ricardo',
        'last_name' => 'Bautista',
    ]);

    // Variant 1: Alcohol
    $alcoholItem = InventoryItem::create([
        'item_code' => 'ITM-ALC-01',
        'name' => 'ALCOHOL, Ethyl',
        'category' => 'Medical Supplies',
        'unit' => 'bottles',
        'quantity' => 100,
        'reorder_level' => 5,
        'unit_cost' => 50.00,
    ]);

    $alcoholVariant = InventoryItemVariant::create([
        'inventory_item_id' => $alcoholItem->id,
        'variant_name' => 'ALCOHOL, Ethyl, 500 mL',
        'barcode' => 'BAR-ALC-500',
        'sku' => 'SKU-ALC-500',
        'unit' => 'bottles',
        'quantity' => 100,
        'unit_cost' => 50.00,
    ]);

    // Another variant that will collide if looked up by office inventory ID
    $wrappingItem = InventoryItem::create([
        'item_code' => 'ITM-WRAP-01',
        'name' => 'WRAPPING PAPER',
        'category' => 'Supplies',
        'unit' => 'rolls',
        'quantity' => 50,
        'reorder_level' => 5,
        'unit_cost' => 15.00,
    ]);

    $wrappingVariant = InventoryItemVariant::create([
        'inventory_item_id' => $wrappingItem->id,
        'variant_name' => 'WRAPPING PAPER Heavy Duty',
        'barcode' => 'BAR-WRAP-01',
        'sku' => 'SKU-WRAP-01',
        'unit' => 'rolls',
        'quantity' => 50,
        'unit_cost' => 15.00,
    ]);

    // Create office inventory for Alcohol
    $officeInventory = OfficeInventory::create([
        'office_id' => $office->id,
        'inventory_item_variants_id' => $alcoholVariant->id,
        'quantity' => 25,
    ]);

    // Submit payload with explicit variant_id and office_inventory_id
    $payload = [
        'purpose' => 'Office disinfection protocol',
        'items' => [
            [
                'id' => $alcoholVariant->id,
                'variant_id' => $alcoholVariant->id,
                'office_inventory_id' => $officeInventory->id,
                'name' => 'ALCOHOL, Ethyl',
                'variant' => 'ALCOHOL, Ethyl, 500 mL',
                'sku' => 'SKU-ALC-500',
                'unit' => 'bottles',
                'cost' => 50.00,
                'quantity' => 2,
            ],
        ],
    ];

    $response = $this->actingAs($staff)->postJson(route('staff.requisition.ris.store'), $payload);

    $response->assertOk();
    $response->assertJson([
        'success' => true,
    ]);

    // Assert that ALCOHOL was saved, NOT WRAPPING PAPER
    $this->assertDatabaseHas('requisition_request_items', [
        'inventory_item_variant_id' => $alcoholVariant->id,
        'item_name' => 'ALCOHOL, Ethyl',
        'variant_name' => 'ALCOHOL, Ethyl, 500 mL',
        'quantity_requested' => 2,
    ]);

    $this->assertDatabaseMissing('requisition_request_items', [
        'inventory_item_variant_id' => $wrappingVariant->id,
    ]);
});

test('staff can update RIS details including AIR number, PO number, and Supplier via patch', function () {
    $office = Office::create([
        'office_name' => 'Provincial Engineering Office',
        'office_code' => 'OFF-PEO-001',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create();

    $requisition = RequisitionRequest::create([
        'request_number' => 'RIS-2026-10-9999',
        'request_type' => 'RIS',
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'status' => 'pending',
        'purpose' => 'Office maintenance',
        'responsibility_center_code' => '1061',
        'requested_by_name' => $staff->full_name,
    ]);

    $response = $this->actingAs($staff)->patchJson(route('staff.requisition.ris-details.update', ['requisition' => $requisition->id]), [
        'air_number' => 'AIR-2026-0042',
        'po_number' => 'PO-2026-0918',
        'supplier' => 'Goldmine Office Supplies Inc.',
        'division' => 'Civil Works Division',
        'responsibility_center_code' => '1061',
        'ris_no' => 'RIS-2026-10-9999',
        'sai_no' => 'SAI-2026-015',
        'purpose' => 'Updated official operational supplies',
    ]);

    $response->assertOk();
    $response->assertJson([
        'success' => true,
        'message' => 'RIS details updated successfully.',
    ]);

    $this->assertDatabaseHas('requisition_requests', [
        'id' => $requisition->id,
        'air_number' => 'AIR-2026-0042',
        'po_number' => 'PO-2026-0918',
        'supplier' => 'Goldmine Office Supplies Inc.',
        'division' => 'Civil Works Division',
        'sai_no' => 'SAI-2026-015',
        'purpose' => 'Updated official operational supplies',
    ]);
});

test('RIS form view auto-fills air number, po number, and supplier from batch if empty', function () {
    $office = Office::create([
        'office_name' => 'Provincial General Services Office',
        'office_code' => 'OFF-PGSO-001',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create();

    $variant = InventoryItemVariant::create([
        'variant_name' => 'A4 Bond Paper Ream',
        'barcode' => 'BAR-A4-001',
        'sku' => 'SKU-A4-001',
        'quantity' => 100,
        'available_for_transfer' => 100,
        'unit' => 'reams',
        'unit_cost' => 250.00,
        'status' => 'In Stock',
    ]);

    InventoryBatch::create([
        'batch_number' => 'BATCH-2026-999',
        'air_number' => 'AIR-2026-0042',
        'inventory_item_variant_id' => $variant->id,
        'office_id' => $office->id,
        'po_number' => 'PO-2026-4411',
        'supplier' => 'Aurora Paper Mills Co.',
        'quantity_received' => 100,
        'quantity_remaining' => 100,
        'unit_cost' => 250.00,
        'status' => 'active',
    ]);

    $requisition = RequisitionRequest::create([
        'request_number' => 'RIS-2026-10-8888',
        'request_type' => 'RIS',
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'status' => 'pending',
        'purpose' => 'Regular printing',
        'air_number' => null,
        'po_number' => null,
        'supplier' => null,
    ]);

    RequisitionRequestItem::create([
        'requisition_request_id' => $requisition->id,
        'inventory_item_variant_id' => $variant->id,
        'stock_no' => 'SKU-A4-001',
        'item_name' => 'A4 Bond Paper',
        'variant_name' => 'A4 Bond Paper Ream',
        'unit' => 'reams',
        'quantity_requested' => 10,
        'quantity_issued' => 0,
        'unit_cost' => 250.00,
        'total_cost' => 2500.00,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($staff)->get(route('staff.requisition.ris-form', ['requisition' => $requisition->id]));
    $response->assertOk();

    // Verify requisition was automatically updated with batch's air_number (NOT batch_number)
    $this->assertDatabaseHas('requisition_requests', [
        'id' => $requisition->id,
        'air_number' => 'AIR-2026-0042',
        'po_number' => 'PO-2026-4411',
        'supplier' => 'Aurora Paper Mills Co.',
    ]);

    // Verify AIR number appears on rendered RIS page, not the batch number
    $response->assertSee('AIR-2026-0042');
    $response->assertSee('PO-2026-4411');
    $response->assertSee('Aurora Paper Mills Co.');
});
