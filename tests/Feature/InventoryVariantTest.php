<?php

use App\Enums\AccountLevel;
use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view inventory records page with variant indicators', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Signpen Pilot G2',
        'item_code' => 'TEST-PEN-01',
        'unit' => 'pieces',
    ]);

    InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Blue',
        'barcode' => 'BAR-001',
        'sku' => 'TEST-PEN-BLU',
        'quantity' => 25,
        'reorder_level' => 5,
        'unit_cost' => 45.00,
        'unit' => 'pieces',
        'status' => 'In Stock',
        'is_for_inspection' => false,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.inventory'));

    $response->assertOk();
    $response->assertSee('Signpen Pilot G2');
    $response->assertSee('TEST-PEN-01');
    $response->assertSee('1 Variants');
});

test('admin can view specific inventory item with all its variants', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'MyGel Signpen 0.5mm',
        'item_code' => 'TEST-MYGEL-01',
        'unit' => 'pieces',
    ]);

    InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Blue',
        'barcode' => '480088910011',
        'sku' => 'TEST-GEL-BLU',
        'quantity' => 100,
        'reorder_level' => 15,
        'unit_cost' => 38.50,
        'unit' => 'pieces',
        'status' => 'In Stock',
    ]);

    InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Red',
        'barcode' => '480088910012',
        'sku' => 'TEST-GEL-RED',
        'quantity' => 40,
        'reorder_level' => 15,
        'unit_cost' => 38.50,
        'unit' => 'pieces',
        'status' => 'In Stock',
    ]);

    InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Black',
        'barcode' => '480088910013',
        'sku' => 'TEST-GEL-BLK',
        'quantity' => 80,
        'reorder_level' => 15,
        'unit_cost' => 38.50,
        'unit' => 'pieces',
        'status' => 'In Stock',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.inventory.show', $item));

    $response->assertOk();
    $response->assertSee('MyGel Signpen 0.5mm');
    $response->assertSee('Blue');
    $response->assertSee('Red');
    $response->assertSee('Black');
    $response->assertSee('480088910011');
    $response->assertSee('TEST-GEL-BLU');
    $response->assertSee('3 variants');
});

test('admin can add a variant with barcode while sku, unit cost, and location are optional', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'MyGel Signpen 0.5mm',
        'item_code' => 'TEST-SIGNPEN-02',
        'unit' => 'pieces',
        'reorder_level' => 10,
        'unit_cost' => 38.50,
        'location' => 'Aisle 1 • Shelf C-2',
    ]);

    // Omit sku, unit_cost, and location completely
    $response = $this->actingAs($admin)->post(route('admin.inventory.variants.store', $item), [
        'variant_name' => 'Green',
        'barcode' => '480088910015',
        'variant_description' => 'Forest Green archival gel ink',
        'unit' => 'pieces',
        'quantity' => 60,
        'reorder_level' => 10,
    ]);

    $response->assertRedirect(route('admin.inventory.show', $item));
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('inventory_item_variants', [
        'inventory_item_id' => $item->id,
        'variant_name' => 'Green',
        'barcode' => '480088910015',
        'sku' => null,
        'quantity' => 60,
        'unit_cost' => 38.50, // defaulted to parent unit_cost
        'location' => null,
    ]);

    $item->refresh();
    expect($item->quantity)->toBe(60);
});

test('barcode is required and must be unique', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'MyGel Signpen',
        'item_code' => 'TEST-PEN-03',
        'unit' => 'pieces',
    ]);

    InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Blue',
        'barcode' => 'DUP-BARCODE-01',
        'quantity' => 10,
        'reorder_level' => 5,
        'unit_cost' => 20.00,
        'unit' => 'pieces',
        'status' => 'In Stock',
    ]);

    // Test missing barcode fails
    $resMissing = $this->actingAs($admin)->post(route('admin.inventory.variants.store', $item), [
        'variant_name' => 'Red',
        'quantity' => 10,
        'reorder_level' => 5,
    ]);
    $resMissing->assertSessionHasErrors(['barcode']);

    // Test duplicate barcode fails
    $resDuplicate = $this->actingAs($admin)->post(route('admin.inventory.variants.store', $item), [
        'variant_name' => 'Navy Blue',
        'barcode' => 'DUP-BARCODE-01',
        'quantity' => 15,
        'reorder_level' => 5,
    ]);
    $resDuplicate->assertSessionHasErrors(['barcode']);
});

test('variant sku must be unique if provided', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'MyGel Signpen',
        'item_code' => 'TEST-PEN-04',
        'unit' => 'pieces',
    ]);

    InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Blue',
        'barcode' => 'BAR-002',
        'sku' => 'DUP-SKU-01',
        'quantity' => 10,
        'reorder_level' => 5,
        'unit_cost' => 20.00,
        'unit' => 'pieces',
        'status' => 'In Stock',
    ]);

    $response = $this->actingAs($admin)->post(route('admin.inventory.variants.store', $item), [
        'variant_name' => 'Navy Blue',
        'barcode' => 'BAR-003',
        'sku' => 'DUP-SKU-01',
        'quantity' => 15,
        'reorder_level' => 5,
    ]);

    $response->assertSessionHasErrors(['sku']);
});

test('unauthenticated users cannot view or add variants', function () {
    $item = InventoryItem::factory()->create();

    $this->get(route('admin.inventory.show', $item))
        ->assertRedirect(route('login'));

    $this->post(route('admin.inventory.variants.store', $item), [
        'variant_name' => 'Blue',
        'barcode' => 'BAR-UNAUTH',
        'quantity' => 10,
        'reorder_level' => 5,
    ])->assertRedirect(route('login'));
});

test('admin can edit variant quantity and details', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'MyGel Signpen',
        'item_code' => 'EDIT-TEST-01',
        'unit' => 'pieces',
        'quantity' => 30,
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Blue',
        'barcode' => 'BAR-EDIT-01',
        'quantity' => 30,
        'reorder_level' => 10,
        'unit_cost' => 35.00,
        'unit' => 'pieces',
        'status' => 'In Stock',
    ]);

    $response = $this->actingAs($admin)->put(route('admin.inventory.variants.update', [$item, $variant]), [
        'variant_name' => 'Royal Blue Gel',
        'barcode' => 'BAR-EDIT-01',
        'sku' => 'SKU-ROYAL-BLU',
        'quantity' => 85, // New quantity
        'reorder_level' => 15,
        'unit_cost' => 38.00,
        'location' => 'Aisle 2 • Shelf D',
    ]);

    $response->assertRedirect(route('admin.inventory.show', $item));
    $response->assertSessionHas('status');

    $variant->refresh();
    expect($variant->variant_name)->toBe('Royal Blue Gel')
        ->and($variant->quantity)->toBe(85)
        ->and($variant->sku)->toBe('SKU-ROYAL-BLU')
        ->and($variant->location)->toBe('Aisle 2 • Shelf D');

    $item->refresh();
    expect($item->quantity)->toBe(85);
});

test('admin can delete a variant and parent stock updates', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Signpen Pen Set',
        'item_code' => 'DEL-TEST-01',
        'unit' => 'pieces',
        'quantity' => 50,
    ]);

    $var1 = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Red',
        'barcode' => 'BAR-DEL-01',
        'quantity' => 20,
        'reorder_level' => 5,
        'unit_cost' => 30.00,
        'status' => 'In Stock',
    ]);

    $var2 = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Black',
        'barcode' => 'BAR-DEL-02',
        'quantity' => 30,
        'reorder_level' => 5,
        'unit_cost' => 30.00,
        'status' => 'In Stock',
    ]);

    // Delete variant 1 (Red)
    $response = $this->actingAs($admin)->delete(route('admin.inventory.variants.destroy', [$item, $var1]));

    $response->assertRedirect(route('admin.inventory.show', $item));
    $response->assertSessionHas('status');

    $this->assertDatabaseMissing('inventory_item_variants', [
        'id' => $var1->id,
    ]);

    // Parent item should now have only 30 units (from var2)
    $item->refresh();
    expect($item->quantity)->toBe(30);
});

test('new variant defaults available_for_transfer to the added quantity', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Marker Test',
        'item_code' => 'MTR-001',
    ]);

    $response = $this->actingAs($admin)->post(route('admin.inventory.variants.store', $item), [
        'variant_name' => 'Green',
        'barcode' => 'BAR-MTR-GRN',
        'quantity' => 75,
        'reorder_level' => 10,
    ]);

    $response->assertRedirect(route('admin.inventory.show', $item));

    $variant = InventoryItemVariant::where('barcode', 'BAR-MTR-GRN')->first();
    expect($variant)->not->toBeNull();
    expect($variant->quantity)->toBe(75);
    expect($variant->available_for_transfer)->toBe(75);
});

test('inventory page includes variant details in search dataset for live variant searching', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Industrial Extension Cord',
        'item_code' => 'IEC-2026',
    ]);

    InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Heavy Duty 50 Meters',
        'barcode' => 'BAR-CORD-50M',
        'sku' => 'SKU-CORD-HD50',
        'quantity' => 10,
        'reorder_level' => 2,
        'is_for_inspection' => false,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.inventory'));

    $response->assertOk();
    $response->assertSee('data-variants="heavy duty 50 meters bar-cord-50m', false);
    $response->assertSee('Heavy Duty 50 Meters');
});

test('inventory page renders view mode toggle and exact variant rows for variant search results', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Highlighter Set',
        'item_code' => 'HL-100',
    ]);

    InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Neon Yellow',
        'barcode' => 'BAR-HL-YEL',
        'sku' => 'SKU-HL-YEL',
        'quantity' => 45,
        'reorder_level' => 5,
        'unit_cost' => 22.50,
        'is_for_inspection' => false,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.inventory'));

    $response->assertOk();
    $response->assertSee('📦 By Item');
    $response->assertSee('🏷️ Exact Variants');
    $response->assertSee('class="variant-row"', false);
    $response->assertSee('Neon Yellow');
    $response->assertSee('BAR-HL-YEL');
    $response->assertSee('SKU-HL-YEL');
    $response->assertSee('searchActiveNotice');
});

test('variant with is_for_inspection true is not displayed in the inventory catalog', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Safety Hardhats',
        'item_code' => 'SAFE-HAT-01',
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'High-Vis Neon Orange',
        'barcode' => 'BAR-HAT-NEON-ORG',
        'sku' => 'SKU-HAT-NEON-ORG',
        'quantity' => 50,
        'reorder_level' => 5,
        'unit_cost' => 350.00,
        'is_for_inspection' => true,
    ]);

    expect($variant->is_for_inspection)->toBeTrue();

    $response = $this->actingAs($admin)->get(route('admin.inventory'));

    $response->assertOk();
    // Variant under inspection must not be displayed in inventory
    $response->assertDontSee('High-Vis Neon Orange');
    $response->assertDontSee('BAR-HAT-NEON-ORG');
    $response->assertDontSee('SKU-HAT-NEON-ORG');
});

test('admin can approve variant inspection so it becomes visible in inventory', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'First Aid Kits',
        'item_code' => 'FAK-2026',
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Trauma Bag Deluxe',
        'barcode' => 'BAR-FAK-TRM',
        'sku' => 'SKU-FAK-TRM',
        'quantity' => 20,
        'reorder_level' => 3,
        'unit_cost' => 1200.00,
        'is_for_inspection' => true,
    ]);

    // Initially hidden from inventory
    $this->actingAs($admin)
        ->get(route('admin.inventory'))
        ->assertDontSee('Trauma Bag Deluxe');

    // Admin approves inspection
    $patchResponse = $this->actingAs($admin)
        ->patch(route('admin.inventory.variants.inspection', [$item, $variant]), [
            'is_for_inspection' => false,
        ]);

    $patchResponse->assertRedirect();
    $variant->refresh();
    expect($variant->is_for_inspection)->toBeFalse();

    // Now variant is visible in inventory catalog
    $this->actingAs($admin)
        ->get(route('admin.inventory'))
        ->assertSee('Trauma Bag Deluxe')
        ->assertSee('BAR-FAK-TRM');
});

test('inventory catalog shows variants by default and hides item-level rows', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Steel Ruler 30cm',
        'item_code' => 'RUL-30',
        'unit' => 'pcs',
    ]);

    InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Stainless 30cm Metric',
        'barcode' => 'BAR-RUL-30M',
        'sku' => 'SKU-RUL-30M',
        'quantity' => 120,
        'reorder_level' => 10,
        'unit_cost' => 55.00,
        'unit' => 'pcs',
        'status' => 'In Stock',
        'is_for_inspection' => false,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.inventory'));

    $response->assertOk();
    $response->assertSee('Total Catalogued Variants');
    $response->assertSee('Stainless 30cm Metric');
    $response->assertSee('BAR-RUL-30M');
    $response->assertSee('SKU-RUL-30M');
    // Ensure inventory catalog only displays variant rows and does not render item rows
    $response->assertSee('class="variant-row"', false);
    $response->assertDontSee('class="item-row"', false);
});

test('creating an item via inventory store automatically provisions an active variant', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.inventory.store'), [
        'item_code' => 'STP-900',
        'name' => 'Heavy Duty Stapler',
        'description' => 'Up to 100 sheets binding',
        'category' => 'Office Supplies',
        'unit' => 'units',
        'quantity' => 15,
        'reorder_level' => 3,
        'unit_cost' => 450.00,
        'location' => 'Bay B',
    ]);

    $response->assertRedirect(route('admin.inventory'));

    $item = InventoryItem::where('item_code', 'STP-900')->first();
    expect($item)->not->toBeNull();

    $variant = InventoryItemVariant::where('inventory_item_id', $item->id)->first();
    expect($variant)->not->toBeNull()
        ->and($variant->variant_name)->toBe('Heavy Duty Stapler')
        ->and($variant->quantity)->toBe(15)
        ->and($variant->available_for_transfer)->toBe(15)
        ->and($variant->is_for_inspection)->toBeFalse();
});
