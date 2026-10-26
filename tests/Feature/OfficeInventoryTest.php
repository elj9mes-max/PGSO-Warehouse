<?php

use App\Enums\AccountLevel;
use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use App\Models\Office;
use App\Models\OfficeInventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('office inventories table has office_id and inventory_item_variants_id columns', function () {
    expect(Schema::hasTable('office_inventories'))->toBeTrue();
    expect(Schema::hasColumns('office_inventories', [
        'id',
        'office_id',
        'inventory_item_variants_id',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

test('office inventory record can be created and links office with variant', function () {
    $office = Office::create([
        'office_name' => 'Provincial Engineering Office (PEO)',
        'office_code' => 'OFF-ENG-001',
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Surveying Total Station',
        'item_code' => 'SRV-001',
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Laser Transit Model TS-500',
        'barcode' => 'BAR-SRV-500',
        'sku' => 'SKU-SRV-500',
        'quantity' => 2,
        'reorder_level' => 1,
        'unit_cost' => 150000.00,
        'unit' => 'units',
        'status' => 'In Stock',
    ]);

    $officeInventory = OfficeInventory::create([
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
    ]);

    $this->assertDatabaseHas('office_inventories', [
        'id' => $officeInventory->id,
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
    ]);

    expect($officeInventory->office->id)->toBe($office->id);
    expect($officeInventory->variant->id)->toBe($variant->id);
    expect($office->variants->first()->id)->toBe($variant->id);
    expect($variant->offices->first()->id)->toBe($office->id);
});

test('deleting an office cascades to office inventory records', function () {
    $office = Office::create([
        'office_name' => 'Provincial Tourism Office (PTO)',
        'office_code' => 'OFF-TRM-002',
    ]);

    $item = InventoryItem::factory()->create();

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'DSLR Camera Body',
        'barcode' => 'BAR-CAM-01',
        'quantity' => 1,
        'reorder_level' => 0,
        'status' => 'In Stock',
    ]);

    $officeInventory = OfficeInventory::create([
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
    ]);

    $office->delete();

    $this->assertDatabaseMissing('office_inventories', [
        'id' => $officeInventory->id,
    ]);
});

test('admin can view stock transfer page with item cards and offices', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $office = Office::create([
        'office_name' => 'Provincial Engineering Office (PEO)',
        'office_code' => 'OFF-ENG-001',
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Pilot G2 Pen',
        'item_code' => 'PEN-G2',
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Black',
        'barcode' => 'BAR-PEN-BLK',
        'quantity' => 50,
        'reorder_level' => 10,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.transfers.index'));

    $response->assertOk();
    $response->assertSee('Transfer Stocks to Offices');
    $response->assertSee('Pilot G2 Pen');
    $response->assertSee('OFF-ENG-001');
    $response->assertSee('BAR-PEN-BLK');
    $response->assertSee('Add to Transfer');
});

test('admin can transfer stock to office and available_for_transfer is deducted', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $office = Office::create([
        'office_name' => 'Provincial Health Office (PHO)',
        'office_code' => 'OFF-PHO-002',
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'First Aid Bandages',
        'item_code' => 'MED-BND-01',
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Standard Pack',
        'barcode' => 'BAR-MED-BND',
        'quantity' => 100,
        'reorder_level' => 20,
    ]);

    expect($variant->available_for_transfer)->toBe(100);

    $response = $this->actingAs($admin)->post(route('admin.transfers.store'), [
        'office_id' => $office->id,
        'transfers' => [
            [
                'variant_id' => $variant->id,
                'quantity' => 25,
            ],
        ],
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('status');

    $variant->refresh();
    expect($variant->available_for_transfer)->toBe(75);
    expect($variant->quantity)->toBe(100); // Main inventory quantity on hand preserved

    $this->assertDatabaseHas('office_inventories', [
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
        'quantity' => 25,
    ]);

    // Subsequent transfer increments quantity for that office
    $this->actingAs($admin)->post(route('admin.transfers.store'), [
        'office_id' => $office->id,
        'transfers' => [
            [
                'variant_id' => $variant->id,
                'quantity' => 15,
            ],
        ],
    ]);

    $variant->refresh();
    expect($variant->available_for_transfer)->toBe(60);

    $this->assertDatabaseHas('office_inventories', [
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
        'quantity' => 40,
    ]);
});

test('admin cannot transfer stock exceeding available_for_transfer', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $office = Office::create([
        'office_name' => 'Provincial Tourism Office (PTO)',
        'office_code' => 'OFF-TRM-003',
    ]);

    $item = InventoryItem::factory()->create();

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Brochures',
        'barcode' => 'BAR-BRO-01',
        'quantity' => 10,
        'reorder_level' => 2,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.transfers.store'), [
        'office_id' => $office->id,
        'transfers' => [
            [
                'variant_id' => $variant->id,
                'quantity' => 15, // exceeds 10 available
            ],
        ],
    ]);

    $response->assertSessionHasErrors('transfers');

    $variant->refresh();
    expect($variant->available_for_transfer)->toBe(10);
    $this->assertDatabaseMissing('office_inventories', [
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
    ]);
});

test('admin can view office inventory monitor page', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $office = Office::create([
        'office_name' => 'Provincial Information Technology Office (PITO)',
        'office_code' => 'OFF-ITC-006',
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Cat6 Ethernet Cable Roll',
        'item_code' => 'ITM-CAB-01',
        'category' => 'IT Equipment',
        'unit' => 'rolls',
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Blue 305m',
        'barcode' => 'BAR-CAB-BLU',
        'sku' => 'SKU-CAB-BLU',
        'quantity' => 10,
        'reorder_level' => 2,
        'unit_cost' => 3500.00,
    ]);

    OfficeInventory::create([
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
        'quantity' => 4,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.offices.inventory', $office));

    $response->assertOk();
    $response->assertSee('Provincial Information Technology Office (PITO)');
    $response->assertSee('OFF-ITC-006');
    $response->assertSee('Blue 305m');
    $response->assertSee('Cat6 Ethernet Cable Roll');
    $response->assertSee('BAR-CAB-BLU');
    $response->assertSee('4');
});

test('admin can view stock release page', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $office = Office::create([
        'office_name' => 'Provincial General Services Office (PGSO)',
        'office_code' => 'OFF-GSO-002',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.releases.index'));

    $response->assertOk();
    $response->assertSee('Stock Release');
    $response->assertSee('OFF-GSO-002');
});

test('admin can release items from office custody and quantities are deducted', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $office = Office::create([
        'office_name' => 'Provincial Engineering Office (PEO)',
        'office_code' => 'OFF-ENG-001',
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Surveyor Tripod',
        'item_code' => 'SRV-TRP-01',
        'quantity' => 20,
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Aluminum Heavy Duty',
        'barcode' => 'BAR-SRV-TRP',
        'quantity' => 20,
        'available_for_transfer' => 12,
        'reorder_level' => 5,
    ]);

    $officeInventory = OfficeInventory::create([
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
        'quantity' => 8,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.releases.store'), [
        'office_id' => $office->id,
        'recipient' => 'Engr. Cruz',
        'purpose' => 'Provincial Road Inspection',
        'releases' => [
            [
                'variant_id' => $variant->id,
                'quantity' => 3,
            ],
        ],
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('status');

    // Office inventory deducted: 8 - 3 = 5
    $officeInventory->refresh();
    expect($officeInventory->quantity)->toBe(5);

    // Variant quantity deducted: 20 - 3 = 17
    $variant->refresh();
    expect($variant->quantity)->toBe(17);

    // Parent item total stock synced: 17
    $item->refresh();
    expect($item->quantity)->toBe(17);
});

test('releasing all office items deletes the office inventory record', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $office = Office::create([
        'office_name' => 'Provincial Health Office (PHO)',
        'office_code' => 'OFF-PHO-003',
    ]);

    $item = InventoryItem::factory()->create();

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Thermometer',
        'barcode' => 'BAR-THM-01',
        'quantity' => 5,
        'reorder_level' => 1,
    ]);

    $officeInventory = OfficeInventory::create([
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
        'quantity' => 2,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.releases.store'), [
        'office_id' => $office->id,
        'recipient' => 'Nurse Joy',
        'releases' => [
            [
                'variant_id' => $variant->id,
                'quantity' => 2, // Releasing all 2 units
            ],
        ],
    ]);

    $response->assertRedirect();
    $this->assertDatabaseMissing('office_inventories', [
        'id' => $officeInventory->id,
    ]);

    $variant->refresh();
    expect($variant->quantity)->toBe(3);
});

test('admin cannot release more than office custody quantity', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $office = Office::create([
        'office_name' => 'Provincial Tourism Office (PTO)',
        'office_code' => 'OFF-TRM-004',
    ]);

    $item = InventoryItem::factory()->create();

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Brochure Pack',
        'barcode' => 'BAR-BRO-99',
        'quantity' => 50,
        'reorder_level' => 5,
    ]);

    OfficeInventory::create([
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
        'quantity' => 5,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.releases.store'), [
        'office_id' => $office->id,
        'recipient' => 'Tourism Staff',
        'releases' => [
            [
                'variant_id' => $variant->id,
                'quantity' => 10, // Office only has 5
            ],
        ],
    ]);

    $response->assertSessionHasErrors('releases');

    $variant->refresh();
    expect($variant->quantity)->toBe(50);
});

test('admin cannot release items without recipient', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $office = Office::create([
        'office_name' => 'Provincial Engineering Office (PEO)',
        'office_code' => 'OFF-ENG-005',
    ]);

    $item = InventoryItem::factory()->create();

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Wrench Set',
        'barcode' => 'BAR-WRN-01',
        'quantity' => 10,
        'reorder_level' => 1,
    ]);

    OfficeInventory::create([
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
        'quantity' => 5,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.releases.store'), [
        'office_id' => $office->id,
        'recipient' => '',
        'releases' => [
            [
                'variant_id' => $variant->id,
                'quantity' => 1,
            ],
        ],
    ]);

    $response->assertSessionHasErrors('recipient');
});

test('transfer page renders item cards with standalone and item variants', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    Office::create([
        'office_name' => 'General Services Office',
        'office_code' => 'OFF-GSO-001',
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Laser Jet Cartridge',
    ]);

    $linkedVariant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Black High Capacity',
        'barcode' => 'BAR-HP-01',
        'quantity' => 40,
        'available_for_transfer' => 40,
    ]);

    $standaloneVariant = InventoryItemVariant::create([
        'inventory_item_id' => null,
        'variant_name' => 'Heavy Duty Stapler Standalone',
        'barcode' => 'BAR-STP-02',
        'quantity' => 15,
        'available_for_transfer' => 15,
        'category' => 'Office Supplies',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.transfers.index'));

    $response->assertOk();
    $response->assertSee('Transfer Stocks to Offices');
    $response->assertSee('Laser Jet Cartridge');
    $response->assertSee('Black High Capacity');
    $response->assertSee('Heavy Duty Stapler Standalone');
    $response->assertSee('BAR-HP-01');
    $response->assertSee('BAR-STP-02');
});

test('admin can see update button on office inventory page', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $office = Office::create([
        'office_name' => 'Provincial Legal Office (PLO)',
        'office_code' => 'OFF-LGL-001',
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Legal Paper Ream',
        'item_code' => 'PPR-LGL-01',
        'category' => 'Paper Supplies',
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => '8.5x14 Sub 20',
        'barcode' => 'BAR-PPR-LGL',
        'quantity' => 100,
        'reorder_level' => 10,
        'unit_cost' => 250.00,
    ]);

    $officeInventory = OfficeInventory::create([
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
        'quantity' => 20,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.offices.inventory', $office));

    $response->assertOk();
    $response->assertSee('btn-table-action-edit');
    $response->assertSee('Update');
    $response->assertSee('updateItemModal');
});

test('admin can update office inventory item details and quantity', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $office = Office::create([
        'office_name' => 'Provincial Legal Office (PLO)',
        'office_code' => 'OFF-LGL-002',
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Legal Folder Set',
        'item_code' => 'FLD-LGL-01',
        'category' => 'Filing Supplies',
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Standard Green',
        'barcode' => 'BAR-FLD-GRN',
        'quantity' => 50,
        'reorder_level' => 5,
        'unit_cost' => 120.00,
    ]);

    $officeInventory = OfficeInventory::create([
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
        'quantity' => 10,
    ]);

    $response = $this->actingAs($admin)->put(route('admin.offices.inventory.update', [$office, $officeInventory]), [
        'variant_name' => 'Heavy Duty Expanding Green',
        'barcode' => 'BAR-FLD-GRN-MOD',
        'category' => 'Executive Filing',
        'unit' => 'packs',
        'unit_cost' => 135.50,
        'quantity' => 15,
    ]);

    $response->assertRedirect(route('admin.offices.inventory', $office));
    $response->assertSessionHas('status');

    $officeInventory->refresh();
    expect($officeInventory->quantity)->toBe(15);

    $variant->refresh();
    expect($variant->variant_name)->toBe('Heavy Duty Expanding Green');
    expect($variant->barcode)->toBe('BAR-FLD-GRN-MOD');
    expect($variant->unit)->toBe('packs');
    expect((float) $variant->unit_cost)->toBe(135.50);

    $item->refresh();
    expect($item->category)->toBe('Executive Filing');
});
