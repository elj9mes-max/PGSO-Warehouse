<?php

use App\Enums\AccountLevel;
use App\Enums\InventoryLogAction;
use App\Models\InventoryBatch;
use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use App\Models\InventoryLog;
use App\Models\Office;
use App\Models\OfficeInventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot access purchase order receiving form', function () {
    $response = $this->get(route('admin.inventory.purchase-request'));

    $response->assertRedirect(route('login'));
});

test('staff user cannot access admin purchase order receiving form', function () {
    $staff = User::factory()->create([
        'account_level' => AccountLevel::Staff,
    ]);

    $response = $this->actingAs($staff)->get(route('admin.inventory.purchase-request'));

    $response->assertRedirect(route('staff.dashboard'));
});

test('admin can view purchase order receiving form with items and offices', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $office = Office::factory()->create([
        'office_name' => 'Provincial Information Office',
        'office_code' => 'PIO-001',
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Signpen Gel Pen',
        'item_code' => 'TEST-PEN-GEL',
        'unit' => 'pcs',
    ]);

    $variant = InventoryItemVariant::factory()->create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Black 0.5mm',
        'unit_cost' => 35.00,
        'quantity' => 20,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.inventory.purchase-request'));

    $response->assertOk();
    $response->assertSee('Receive Purchase Order Supplies');
    $response->assertSee('Provincial Information Office');
    $response->assertSee('Signpen Gel Pen');
    $response->assertSee('Black 0.5mm');
});

test('admin can receive supplies into Central Warehouse and creates batches', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Archival Paper A4',
        'item_code' => 'ITM-PAP-A4',
        'quantity' => 10,
    ]);

    $variant = InventoryItemVariant::factory()->create([
        'inventory_item_id' => $item->id,
        'variant_name' => '70gsm 500 sheets',
        'quantity' => 10,
        'available_for_transfer' => 10,
        'unit_cost' => 220.00,
    ]);

    $payload = [
        'po_number' => 'PO-2026-9901',
        'pr_number' => 'PR-2026-8801',
        'supplier' => 'Paper Nation Trading',
        'delivery_date' => '2026-10-13',
        'office_id' => null, // Central Warehouse
        'remarks' => 'Inspected and complete delivery',
        'items' => [
            [
                'variant_id' => $variant->id,
                'quantity' => 50,
                'unit_cost' => 220.00,
                'remarks' => 'First delivery batch',
            ],
        ],
    ];

    $response = $this->actingAs($admin)->post(route('admin.inventory.purchase-request.store'), $payload);

    $response->assertRedirect(route('admin.inventory.purchase-request'));
    $response->assertSessionHas('status');

    $variant->refresh();
    $item->refresh();

    // Verify quantities updated
    expect($variant->quantity)->toBe(60)
        ->and($variant->available_for_transfer)->toBe(60)
        ->and($item->quantity)->toBe(60);

    // Verify new batch exists
    $batch = InventoryBatch::where('po_number', 'PO-2026-9901')->first();
    expect($batch)->not->toBeNull()
        ->and($batch->inventory_item_variant_id)->toBe($variant->id)
        ->and($batch->quantity_received)->toBe(50)
        ->and($batch->quantity_remaining)->toBe(50)
        ->and((float) $batch->unit_cost)->toBe(220.00)
        ->and($batch->office_id)->toBeNull();

    // Verify audit log
    $log = InventoryLog::where('reference_number', 'PO-2026-9901')->first();
    expect($log)->not->toBeNull()
        ->and($log->action_type)->toBe(InventoryLogAction::InventoryAdded)
        ->and($log->quantity)->toBe(50);
});

test('admin can receive supplies directly destined to an Office', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $office = Office::factory()->create([
        'office_name' => 'Provincial Health Office',
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Surgical Face Masks',
        'item_code' => 'MED-MSK-01',
        'quantity' => 0,
    ]);

    $variant = InventoryItemVariant::factory()->create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Blue 3-Ply (Box of 50)',
        'quantity' => 0,
        'available_for_transfer' => 0,
        'unit_cost' => 150.00,
    ]);

    $payload = [
        'po_number' => 'PO-2026-PHO-01',
        'office_id' => $office->id,
        'items' => [
            [
                'variant_id' => $variant->id,
                'quantity' => 25,
                'unit_cost' => 150.00,
            ],
        ],
    ];

    $response = $this->actingAs($admin)->post(route('admin.inventory.purchase-request.store'), $payload);

    $response->assertRedirect(route('admin.inventory.purchase-request'));

    $variant->refresh();

    // Stock on variant is updated
    expect($variant->quantity)->toBe(25);

    // Office inventory custody created
    $officeInventory = OfficeInventory::where('office_id', $office->id)
        ->where('inventory_item_variants_id', $variant->id)
        ->first();

    expect($officeInventory)->not->toBeNull()
        ->and($officeInventory->quantity)->toBe(25);

    // Batch has office destination
    $batch = InventoryBatch::where('po_number', 'PO-2026-PHO-01')->first();
    expect($batch)->not->toBeNull()
        ->and($batch->office_id)->toBe($office->id);
});

test('incoming delivery with different price creates separate batches preserving prior remaining items', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Pilot G2 0.7mm',
        'quantity' => 15,
    ]);

    $variant = InventoryItemVariant::factory()->create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Blue',
        'quantity' => 15,
        'available_for_transfer' => 15,
        'unit_cost' => 45.00,
    ]);

    // Deliver new items with a HIGHER price of 55.00
    $payload = [
        'po_number' => 'PO-2026-DIFF-PRICE',
        'office_id' => null,
        'items' => [
            [
                'variant_id' => $variant->id,
                'quantity' => 30,
                'unit_cost' => 55.00,
            ],
        ],
    ];

    $response = $this->actingAs($admin)->post(route('admin.inventory.purchase-request.store'), $payload);

    $response->assertRedirect(route('admin.inventory.purchase-request'));

    $variant->refresh();
    expect($variant->quantity)->toBe(45)
        ->and((float) $variant->unit_cost)->toBe(55.00);

    // Check that the batch was created for the new PO delivery
    $batch = InventoryBatch::where('inventory_item_variant_id', $variant->id)
        ->where('po_number', 'PO-2026-DIFF-PRICE')
        ->first();

    expect($batch)->not->toBeNull()
        ->and($batch->quantity_received)->toBe(30)
        ->and((float) $batch->unit_cost)->toBe(55.00);
});

test('inventory items without variants get standard variant and are selectable', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Industrial Storage Pallets',
        'item_code' => 'PAL-001',
        'quantity' => 10,
        'unit_cost' => 650.00,
        'unit' => 'pcs',
    ]);

    expect($item->variants()->count())->toBe(0);

    $response = $this->actingAs($admin)->get(route('admin.inventory.purchase-request'));

    $response->assertOk();
    $response->assertSee('Industrial Storage Pallets — Standard');

    expect($item->variants()->count())->toBe(1);
    $variant = $item->variants()->first();
    expect($variant->variant_name)->toBe('Standard')
        ->and((float) $variant->unit_cost)->toBe(650.00);
});

test('incoming delivery with different price updates variant directly without creating new variant entry', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'A4 Copy Paper 80gsm',
        'quantity' => 20,
    ]);

    $variant = InventoryItemVariant::factory()->create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Standard Ream',
        'quantity' => 20,
        'available_for_transfer' => 20,
        'unit_cost' => 220.00,
    ]);

    // Deliver new batch with different price (245.00)
    $payload = [
        'po_number' => 'PO-2026-PRICE-UPDATE',
        'office_id' => null,
        'items' => [
            [
                'variant_id' => $variant->id,
                'quantity' => 50,
                'unit_cost' => 245.00,
            ],
        ],
    ];

    $response = $this->actingAs($admin)->post(route('admin.inventory.purchase-request.store'), $payload);

    $response->assertRedirect(route('admin.inventory.purchase-request'));

    // Check that NO new variant was created (remains exactly 1 variant)
    expect($item->variants()->count())->toBe(1);

    // Existing variant has its quantity and unit cost updated
    $variant->refresh();
    expect($variant->quantity)->toBe(70)
        ->and((float) $variant->unit_cost)->toBe(245.00)
        ->and($variant->variant_name)->toBe('Standard Ream');

    // Parent item total quantity is updated
    $item->refresh();
    expect($item->quantity)->toBe(70);
});

test('guest cannot access iar form', function () {
    $response = $this->get(route('admin.inventory.iar-form'));

    $response->assertRedirect(route('login'));
});

test('staff user cannot access admin iar form', function () {
    $staff = User::factory()->create([
        'account_level' => AccountLevel::Staff,
    ]);

    $response = $this->actingAs($staff)->get(route('admin.inventory.iar-form'));

    $response->assertRedirect(route('staff.dashboard'));
});

test('admin can view iar form with official layout and signatory', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.inventory.iar-form'));

    $response->assertOk();
    $response->assertSee('ACCEPTANCE AND INSPECTION REPORT');
    $response->assertSee('Stock/ Property No.');
    $response->assertSee('DESCRIPTION');
    $response->assertSee('UNIT');
    $response->assertSee('QTY.');
    $response->assertSee('ACCEPTANCE');
    $response->assertSee('INSPECTION');
    $response->assertSee('RICARDO Q. BAUTISTA');
    $response->assertSee('PGDH-PGSO');
    $response->assertSee('Inspection Officer');
    $response->assertSee('LALAWIGAN NG AURORA');
});

test('admin can view iar form preloaded with batch delivery information', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $office = Office::factory()->create([
        'office_name' => 'Provincial General Services Office',
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'A4 Copy Paper',
    ]);

    $variant = InventoryItemVariant::factory()->create([
        'inventory_item_id' => $item->id,
        'variant_name' => '70gsm White',
        'unit' => 'ream',
    ]);

    $batch = InventoryBatch::create([
        'batch_number' => 'BATCH-TEST-IAR-001',
        'inventory_item_id' => $item->id,
        'inventory_item_variant_id' => $variant->id,
        'office_id' => $office->id,
        'po_number' => 'PO-2026-IAR-7788',
        'supplier' => 'Crown Paper Supply Corp',
        'quantity_received' => 80,
        'quantity_remaining' => 80,
        'unit_cost' => 210.00,
        'status' => 'active',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.inventory.iar-form', [
        'po' => 'PO-2026-IAR-7788',
        'batch' => $batch->id,
    ]));

    $response->assertOk();
    $response->assertSee('PO-2026-IAR-7788');
    $response->assertSee('Crown Paper Supply Corp');
    $response->assertSee('Provincial General Services Office');
    $response->assertSee('A4 Copy Paper - 70gsm White');
    $response->assertSee('ream');
    $response->assertSee('80');
});

test('admin can confirm and receive delivery and generate iar form immediately', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'Bond Paper A4',
    ]);

    $variant = InventoryItemVariant::factory()->create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Subs 20 70gsm',
        'unit' => 'ream',
        'quantity' => 10,
        'available_for_transfer' => 10,
        'unit_cost' => 180.00,
    ]);

    $poNumber = 'PO-2026-IAR-RECEIVE-999';

    $payload = [
        'po_number' => $poNumber,
        'supplier' => 'Paper Nation Corp',
        'delivery_date' => '2026-10-15',
        'office_id' => null,
        'invoice_number' => 'SI-88992',
        'generate_iar' => true,
        'auto_print' => true,
        'items' => [
            [
                'variant_id' => $variant->id,
                'quantity' => 40,
                'unit_cost' => 185.00,
                'remarks' => 'Clean reams inspected',
            ],
        ],
    ];

    $response = $this->actingAs($admin)->post(route('admin.inventory.purchase-request.store'), $payload);

    $expectedIarUrl = route('admin.inventory.iar-form', [
        'po' => $poNumber,
        'print' => 1,
        'invoice' => 'SI-88992',
    ]);

    $response->assertRedirect($expectedIarUrl);
    $response->assertSessionHas('status');
    $response->assertSessionHas('received_po', $poNumber);

    $variant->refresh();
    expect($variant->quantity)->toBe(50)
        ->and($variant->available_for_transfer)->toBe(50);

    // Follow the redirect to verify the generated IAR Form displays the received delivery
    $iarPageResponse = $this->actingAs($admin)->get($expectedIarUrl);
    $iarPageResponse->assertOk();
    $iarPageResponse->assertSee($poNumber);
    $iarPageResponse->assertSee('Paper Nation Corp');
    $iarPageResponse->assertSee('Bond Paper A4 - Subs 20 70gsm');
    $iarPageResponse->assertSee('40');
    $iarPageResponse->assertSee('ream');
    $iarPageResponse->assertSee('SI-88992');
});

test('admin can view and receive supplies for standalone variants without inventory items', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $variant = InventoryItemVariant::factory()->create([
        'inventory_item_id' => null,
        'variant_name' => 'ETHYL ALCOHOL 500ml',
        'item_code' => 'ALC-500',
        'sku' => 'SKU-ALC-500',
        'barcode' => 'BAR-ALC-500',
        'category' => 'Medical Supplies',
        'unit' => 'bottle',
        'unit_cost' => 85.00,
        'quantity' => 10,
        'available_for_transfer' => 10,
        'is_for_inspection' => false,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.inventory.purchase-request'));
    $response->assertOk();
    $response->assertSee('ETHYL ALCOHOL 500ml');

    $payload = [
        'po_number' => 'PO-2026-9901',
        'supplier' => 'Pharma Supply Direct',
        'delivery_date' => now()->toDateString(),
        'items' => [
            [
                'variant_id' => $variant->id,
                'quantity' => 15,
                'unit_cost' => 88.00,
                'remarks' => 'Batch delivery',
            ],
        ],
    ];

    $postResponse = $this->actingAs($admin)->post(route('admin.inventory.purchase-request.store'), $payload);
    $postResponse->assertRedirect();

    $variant->refresh();
    expect($variant->quantity)->toBe(25)
        ->and((float) $variant->unit_cost)->toBe(88.00);

    expect(InventoryBatch::where('inventory_item_variant_id', $variant->id)->count())->toBe(1);
    $batch = InventoryBatch::where('inventory_item_variant_id', $variant->id)->first();
    expect($batch->inventory_item_id)->toBeNull()
        ->and($batch->quantity_received)->toBe(15);
});
