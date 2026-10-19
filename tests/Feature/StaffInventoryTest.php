<?php

use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use App\Models\Office;
use App\Models\OfficeInventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unauthenticated user is redirected from staff inventory page', function () {
    $response = $this->get(route('staff.inventory'));
    $response->assertRedirect(route('login'));
});

test('staff user assigned to an office sees their office stock catalog in e-commerce grid style', function () {
    $office = Office::create([
        'office_name' => "Provincial Assessor's Office",
        'office_code' => 'OFF-PAS-001',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create([
        'first_name' => 'Eduardo',
        'last_name' => 'Reyes',
    ]);

    $item = InventoryItem::create([
        'item_code' => 'ITM-PRINT-01',
        'name' => 'Heavy Duty Laser Printer Toner Cartridge',
        'description' => 'Original black high-yield laser toner cartridge for department printers',
        'category' => 'Office Supplies',
        'unit' => 'cartridges',
        'quantity' => 40,
        'reorder_level' => 5,
        'unit_cost' => 3200.00,
        'status' => 'In Stock',
        'location' => 'Storage Bay 3',
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'High Yield Black 10,000 Pages',
        'barcode' => 'BAR-TONER-BLK-01',
        'sku' => 'SKU-TONER-BLK-01',
        'quantity' => 20,
        'available_for_transfer' => 20,
        'unit' => 'cartridges',
        'reorder_level' => 3,
        'unit_cost' => 3200.00,
        'location' => 'Cabinet A-4',
        'status' => 'In Stock',
    ]);

    OfficeInventory::create([
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
        'quantity' => 12,
    ]);

    $response = $this->actingAs($staff)->get(route('staff.inventory'));

    $response->assertOk();
    $response->assertSeeText("Provincial Assessor's Office");
    $response->assertSeeText('OFF-PAS-001');
    $response->assertSeeText('Your Designated Office');
    $response->assertSeeText('Heavy Duty Laser Printer Toner Cartridge');
    $response->assertSeeText('High Yield Black 10,000 Pages');
    $response->assertSeeText('SKU-TONER-BLK-01');
    $response->assertSeeText('12');
    $response->assertSeeText('cartridges');
    $response->assertSeeText('₱3,200.00');
    $response->assertSeeText('Quick View');
});

test('office staff user cannot view other office stock and is restricted to their assigned office', function () {
    $homeOffice = Office::create([
        'office_name' => 'Provincial Budget Office',
        'office_code' => 'OFF-PBO-001',
    ]);

    $targetOffice = Office::create([
        'office_name' => 'Provincial Health Office',
        'office_code' => 'OFF-PHO-001',
    ]);

    $staff = User::factory()->staff()->forOffice($homeOffice)->create();

    $item = InventoryItem::create([
        'item_code' => 'MED-GLV-01',
        'name' => 'Nitrile Examination Medical Gloves',
        'description' => 'Powder-free medical grade gloves',
        'category' => 'Medical Supplies',
        'unit' => 'boxes',
        'quantity' => 100,
        'reorder_level' => 10,
        'unit_cost' => 450.00,
        'status' => 'In Stock',
        'location' => 'Clinic Bay 1',
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Size Medium Box of 100',
        'barcode' => 'BAR-GLV-MED-100',
        'sku' => 'SKU-GLV-MED-100',
        'quantity' => 50,
        'available_for_transfer' => 50,
        'unit' => 'boxes',
        'reorder_level' => 15,
        'unit_cost' => 450.00,
        'location' => 'Supply Shelf 2',
        'status' => 'In Stock',
    ]);

    OfficeInventory::create([
        'office_id' => $targetOffice->id,
        'inventory_item_variants_id' => $variant->id,
        'quantity' => 30,
    ]);

    // Attempt to view target office inventory via query param
    $response = $this->actingAs($staff)->get(route('staff.inventory', ['office_id' => $targetOffice->id]));

    $response->assertOk();
    // Office staff remains restricted to their designated office
    $response->assertSeeText('Provincial Budget Office');
    $response->assertSeeText('OFF-PBO-001');
    $response->assertSeeText('Designated Office:');
    $response->assertDontSeeText('Provincial Health Office');
    $response->assertDontSeeText('Nitrile Examination Medical Gloves');
    $response->assertDontSeeText('SKU-GLV-MED-100');
});

test('floating staff user without office assignment sees floating staff banner and can switch offices on inventory page', function () {
    $staff = User::factory()->staff()->create([
        'office_id' => null,
    ]);

    $office = Office::create([
        'office_name' => 'Provincial Health Office',
        'office_code' => 'OFF-PHO-001',
    ]);

    $response = $this->actingAs($staff)->get(route('staff.inventory'));

    $response->assertOk();
    $response->assertSeeText('No Office Assigned (Floating Staff)');
    $response->assertSeeText('GENERAL POOL');
    $response->assertSeeText('Switch Office:');

    // Floating staff can select an office to inspect
    $responseOffice = $this->actingAs($staff)->get(route('staff.inventory', ['office_id' => $office->id]));
    $responseOffice->assertOk();
    $responseOffice->assertSeeText('Provincial Health Office');
    $responseOffice->assertSeeText('OFF-PHO-001');
});
