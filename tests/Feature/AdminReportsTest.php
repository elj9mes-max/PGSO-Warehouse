<?php

use App\Enums\InventoryLogAction;
use App\Models\InventoryItemVariant;
use App\Models\InventoryLog;
use App\Models\Office;
use App\Models\OfficeInventory;
use App\Models\StockCard;
use App\Models\StockCardEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can access reports page and sees all 4 report modules', function () {
    $admin = User::factory()->admin()->create();

    $office = Office::create([
        'office_name' => 'Provincial General Services Office',
        'office_code' => 'PGSO',
    ]);

    $variant = InventoryItemVariant::factory()->create([
        'variant_name' => 'Bond Paper A4 80gsm',
        'item_code' => 'BOND-A4-01',
        'quantity' => 100,
        'unit' => 'ream',
        'unit_cost' => 250.00,
        'is_for_inspection' => false,
    ]);

    OfficeInventory::create([
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
        'quantity' => 15,
    ]);

    InventoryLog::factory()->create([
        'action_type' => InventoryLogAction::InventoryAdded,
        'inventory_item_variant_id' => $variant->id,
        'variant_name' => $variant->variant_name,
        'quantity' => 100,
        'user_id' => $admin->id,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.reports.index'));

    $response->assertOk();
    $response->assertSeeText('Warehouse & Custody Reports Center');
    $response->assertSeeText('Stock Card Ledger');
    $response->assertSeeText('Transaction & Audit Logs');
    $response->assertSeeText('Office Stock Report');
    $response->assertSeeText('SSMI (Summary of Supplies Issued)');
    $response->assertSeeText('Appendix 58');
    $response->assertSeeText('Appendix 64');
    $response->assertSeeText('Open Stock Card');
    $response->assertSeeText('View Audit Logs');
    $response->assertSeeText('View Office Stock');
    $response->assertSeeText('Open SSMI Report');
});

test('guest or non-admin cannot access admin reports page', function () {
    $response = $this->get(route('admin.reports.index'));
    $response->assertRedirect(route('login'));

    $staff = User::factory()->staff()->create();
    $staffResponse = $this->actingAs($staff)->get(route('admin.reports.index'));
    $staffResponse->assertRedirect(route('staff.dashboard'));
});

test('top level reports route redirects admin to admin.reports.index', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('reports.index'));
    $response->assertRedirect(route('admin.reports.index'));
});

test('admin can access stock card page and sees authentic ledger format matching photo', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.reports.stock-card'));

    $response->assertOk();
    $response->assertSeeText('STOCK CARD');
    $response->assertSeeText('OFFICE');
    $response->assertSeeText('ITEM:');
    $response->assertSeeText('STOCK NO:');
    $response->assertSeeText('Unit:');
    $response->assertSeeText('DATE');
    $response->assertSeeText('Reference');
    $response->assertSeeText('PO #');
    $response->assertSeeText('RECIEPT');
    $response->assertSeeText('ISSUANCE');
    $response->assertSeeText('RIS # / OFFICE');
    $response->assertSeeText('BALANCE');
    $response->assertSeeText('QTY');
    $response->assertSeeText('Select Item to Populate');
    $response->assertSeeText('All Offices');
    $response->assertSeeText('Undo');
    $response->assertSeeText('Print Stock Card');
});

test('admin can access stock card page with specific inventory variant preloaded', function () {
    $admin = User::factory()->admin()->create();

    $variant = InventoryItemVariant::factory()->create([
        'variant_name' => 'AIRCONDITION WINDOW TYPE 1.5HP',
        'item_code' => 'STK-AC-1005',
        'unit' => 'unit',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.reports.stock-card', ['variant_id' => $variant->id]));

    $response->assertOk();
    $response->assertSee('AIRCONDITION WINDOW TYPE 1.5HP');
    $response->assertSee('STK-AC-1005');
});

test('admin can access stock card page scoped to specific office', function () {
    $admin = User::factory()->admin()->create();

    $office = Office::create([
        'office_name' => 'Provincial Health Office',
        'office_code' => 'PHO',
    ]);

    $variant = InventoryItemVariant::factory()->create([
        'variant_name' => 'Paracetamol 500mg Tablet',
        'item_code' => 'MED-001',
        'unit' => 'box',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.reports.stock-card', [
        'variant_id' => $variant->id,
        'office_id' => $office->id,
    ]));

    $response->assertOk();
    $response->assertSee('Paracetamol 500mg Tablet');
    $response->assertSee('Provincial Health Office');
});

test('guest cannot access admin stock card page', function () {
    $response = $this->get(route('admin.reports.stock-card'));
    $response->assertRedirect(route('login'));
});

test('admin can save stock card and entries to database', function () {
    $admin = User::factory()->admin()->create();

    $variant = InventoryItemVariant::factory()->create([
        'variant_name' => 'Epson L3110 EcoTank Printer',
        'item_code' => 'PRN-001',
        'unit' => 'unit',
    ]);

    $payload = [
        'variant_id' => $variant->id,
        'office' => 'PROVINCIAL GENERAL SERVICES OFFICE',
        'item_name' => 'Epson L3110 EcoTank Printer',
        'unit' => 'unit',
        'stock_no' => 'PRN-001',
        'card_no' => 'Sheet #1',
        'rows' => [
            [
                'date' => '2026-10-12',
                'po' => 'PO #2026-099',
                'receiptQty' => 10,
                'risOffice' => null,
                'issuanceQty' => null,
                'balanceQty' => 10,
                'remarks' => 'Initial batch delivery',
            ],
            [
                'date' => '2026-10-18',
                'po' => null,
                'receiptQty' => null,
                'risOffice' => 'RIS #088 - PGSO Supply',
                'issuanceQty' => 3,
                'balanceQty' => 7,
                'remarks' => 'Issued to supply unit',
            ],
        ],
    ];

    $response = $this->actingAs($admin)->postJson(route('admin.reports.stock-card.save'), $payload);

    $response->assertOk();
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('stock_cards', [
        'inventory_item_variant_id' => $variant->id,
        'item_name' => 'Epson L3110 EcoTank Printer',
        'stock_no' => 'PRN-001',
    ]);

    $card = StockCard::where('inventory_item_variant_id', $variant->id)->first();
    expect($card)->not->toBeNull();
    expect($card->entries()->count())->toBe(2);

    $this->assertDatabaseHas('stock_card_entries', [
        'stock_card_id' => $card->id,
        'reference' => 'PO #2026-099',
        'receipt_qty' => 10,
        'balance_qty' => 10,
    ]);

    $this->assertDatabaseHas('stock_card_entries', [
        'stock_card_id' => $card->id,
        'issue_to' => 'RIS #088 - PGSO Supply',
        'issuance_qty' => 3,
        'balance_qty' => 7,
    ]);
});

test('admin can fetch stock card json data for variant', function () {
    $admin = User::factory()->admin()->create();

    $variant = InventoryItemVariant::factory()->create([
        'variant_name' => 'Canon Pixma G2010',
        'item_code' => 'PRN-002',
        'unit' => 'unit',
    ]);

    $card = StockCard::create([
        'inventory_item_variant_id' => $variant->id,
        'item_name' => 'Canon Pixma G2010',
        'unit' => 'unit',
        'stock_no' => 'PRN-002',
        'office' => 'PROVINCIAL GENERAL SERVICES OFFICE',
    ]);

    StockCardEntry::create([
        'stock_card_id' => $card->id,
        'entry_date' => '2026-10-15',
        'reference' => 'PO #2026-045',
        'receipt_qty' => 5,
        'balance_qty' => 5,
        'row_order' => 1,
    ]);

    $response = $this->actingAs($admin)->getJson(route('admin.reports.stock-card.data', ['variant_id' => $variant->id]));

    $response->assertOk();
    $response->assertJson([
        'success' => true,
        'stock_card' => [
            'id' => $card->id,
            'item_name' => 'Canon Pixma G2010',
            'stock_no' => 'PRN-002',
        ],
    ]);
    expect($response->json('rows'))->toHaveCount(1);
    expect($response->json('rows.0.po'))->toBe('PO #2026-045');
});

test('stock card ignores past inventory logs and only renders data from stock card database tables', function () {
    $admin = User::factory()->admin()->create();

    $variant = InventoryItemVariant::factory()->create([
        'variant_name' => 'Dell Latitude 5420 Laptop',
        'item_code' => 'IT-LAP-001',
        'unit' => 'unit',
    ]);

    // Create an inventory log that represents a past PO delivery
    InventoryLog::create([
        'inventory_item_variant_id' => $variant->id,
        'action_type' => InventoryLogAction::InventoryAdded->value,
        'quantity' => 15,
        'reference_number' => 'PO-2026-999',
        'remarks' => 'Past PO delivery log',
    ]);

    // Fetch stock card data before saving any StockCard model
    $response = $this->actingAs($admin)->getJson(route('admin.reports.stock-card.data', ['variant_id' => $variant->id]));

    $response->assertOk();
    $response->assertJson([
        'success' => true,
        'stock_card' => null,
    ]);
    // The rows must be empty because past transactions in InventoryLog are ignored
    expect($response->json('rows'))->toBeEmpty();

    // Now save a record into stock_cards and stock_card_entries
    $card = StockCard::create([
        'inventory_item_variant_id' => $variant->id,
        'item_name' => 'Dell Latitude 5420 Laptop',
        'unit' => 'unit',
        'stock_no' => 'IT-LAP-001',
        'office' => '',
    ]);

    StockCardEntry::create([
        'stock_card_id' => $card->id,
        'entry_date' => '2026-10-21',
        'reference' => 'PO #2026-NEW-01',
        'receipt_qty' => 5,
        'balance_qty' => 5,
        'row_order' => 1,
    ]);

    $responseAfter = $this->actingAs($admin)->getJson(route('admin.reports.stock-card.data', ['variant_id' => $variant->id]));

    $responseAfter->assertOk();
    expect($responseAfter->json('rows'))->toHaveCount(1);
    expect($responseAfter->json('rows.0.po'))->toBe('PO #2026-NEW-01');
});

test('admin can view all item stock cards simultaneously when selecting all items option', function () {
    $admin = User::factory()->admin()->create();

    $item1 = InventoryItemVariant::factory()->create([
        'variant_name' => 'AIR CONDITIONING UNIT, 1.0 HP, CEILING TYPE',
        'item_code' => '80141505-TS-072',
        'unit' => 'unit',
    ]);

    $item2 = InventoryItemVariant::factory()->create([
        'variant_name' => 'ACETATE FILM CLEAR',
        'item_code' => 'STK-ACT-001',
        'unit' => 'roll',
    ]);

    $item3 = InventoryItemVariant::factory()->create([
        'variant_name' => 'AIR COOLER PORTABLE 20L',
        'item_code' => 'STK-AC-COOL-20L',
        'unit' => 'unit',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.reports.stock-card', ['variant_id' => 'all']));

    $response->assertOk();
    $response->assertSee('AIR CONDITIONING UNIT, 1.0 HP, CEILING TYPE');
    $response->assertSee('80141505-TS-072');
    $response->assertSee('ACETATE FILM CLEAR');
    $response->assertSee('STK-ACT-001');
    $response->assertSee('AIR COOLER PORTABLE 20L');
    $response->assertSee('STK-AC-COOL-20L');
    $response->assertSee('stock-cards-multi-grid');
    $response->assertSee('All Items (Display Each Stock Card)');
});

test('admin can filter stock cards by activity date range and only see cards with activity in that range', function () {
    $admin = User::factory()->admin()->create();

    $itemActive = InventoryItemVariant::factory()->create([
        'variant_name' => 'Active Item During January',
        'item_code' => 'ACT-001',
        'unit' => 'box',
    ]);

    $itemInactive = InventoryItemVariant::factory()->create([
        'variant_name' => 'Inactive Item In January',
        'item_code' => 'INACT-001',
        'unit' => 'unit',
    ]);

    // Create stock card and entry for active item on 2026-01-15
    $activeCard = StockCard::create([
        'inventory_item_variant_id' => $itemActive->id,
        'item_name' => 'Active Item During January',
        'unit' => 'box',
        'stock_no' => 'ACT-001',
        'office' => '',
    ]);

    StockCardEntry::create([
        'stock_card_id' => $activeCard->id,
        'entry_date' => '2026-01-15',
        'reference' => 'PO #2026-JAN-01',
        'receipt_qty' => 50,
        'balance_qty' => 50,
        'row_order' => 1,
    ]);

    // Create stock card and entry for inactive item outside the range (e.g. 2026-03-10)
    $inactiveCard = StockCard::create([
        'inventory_item_variant_id' => $itemInactive->id,
        'item_name' => 'Inactive Item In January',
        'unit' => 'unit',
        'stock_no' => 'INACT-001',
        'office' => '',
    ]);

    StockCardEntry::create([
        'stock_card_id' => $inactiveCard->id,
        'entry_date' => '2026-03-10',
        'reference' => 'PO #2026-MAR-01',
        'receipt_qty' => 10,
        'balance_qty' => 10,
        'row_order' => 1,
    ]);

    // Filter by January 1 to January 30
    $response = $this->actingAs($admin)->get(route('admin.reports.stock-card', [
        'from_date' => '2026-01-01',
        'to_date' => '2026-01-30',
    ]));

    $response->assertOk();
    // Only the active item is shown in bulkCards
    expect($response->viewData('bulkCards'))->toHaveCount(1);
    expect($response->viewData('bulkCards')[0]['item_name'])->toBe('Active Item During January');
    $response->assertSee('Active Item During January');
    $response->assertSee('PO #2026-JAN-01');
    // The inactive item entries are not rendered
    $response->assertDontSee('PO #2026-MAR-01');
});

test('admin sees empty state when no stock cards have activity in the filtered date range', function () {
    $admin = User::factory()->admin()->create();

    $item = InventoryItemVariant::factory()->create([
        'variant_name' => 'Item With No Activity In 2025',
        'item_code' => 'NOACT-001',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.reports.stock-card', [
        'from_date' => '2025-01-01',
        'to_date' => '2025-01-30',
    ]));

    $response->assertOk();
    expect($response->viewData('bulkCards'))->toBeEmpty();
    $response->assertSee('No Stock Card Activity');
    $response->assertSee('stock-cards-empty-state');
    $response->assertDontSee('id="stockCardsMultiGrid"', false);
});
