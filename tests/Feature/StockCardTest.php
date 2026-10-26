<?php

use App\Models\InventoryItemVariant;
use App\Models\Office;
use App\Models\StockCard;
use App\Models\StockCardEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can create a stock card with metadata snapshot', function () {
    $variant = InventoryItemVariant::factory()->create([
        'variant_name' => 'Dell Latitude 5420 Laptop',
        'item_code' => 'LAP-001',
        'unit' => 'unit',
    ]);

    $user = User::factory()->create();

    $card = StockCard::create([
        'inventory_item_variant_id' => $variant->id,
        'user_id' => $user->id,
        'office' => 'PROVINCIAL GENERAL SERVICES OFFICE',
        'item_name' => $variant->variant_name,
        'unit' => $variant->unit,
        'stock_no' => $variant->item_code,
        'card_no' => 'Sheet #1',
    ]);

    expect($card->id)->not->toBeNull()
        ->and($card->item_name)->toBe('Dell Latitude 5420 Laptop')
        ->and($card->office)->toBe('PROVINCIAL GENERAL SERVICES OFFICE')
        ->and($card->variant->id)->toBe($variant->id)
        ->and($card->creator->id)->toBe($user->id);
});

test('it can record inbound receipts and outbound issuances on a stock card', function () {
    $card = StockCard::factory()->create([
        'item_name' => 'Dell Latitude 5420 Laptop',
        'unit' => 'unit',
    ]);

    // 1. Initial Inbound Delivery (PO)
    $entry1 = StockCardEntry::create([
        'stock_card_id' => $card->id,
        'entry_date' => '2026-10-10',
        'reference' => 'PO #2026-081',
        'receipt_qty' => 10,
        'issue_to' => null,
        'issuance_qty' => null,
        'balance_qty' => 10,
        'remarks' => 'Initial delivery',
        'row_order' => 1,
    ]);

    // 2. Outbound Request (RIS)
    $entry2 = StockCardEntry::create([
        'stock_card_id' => $card->id,
        'entry_date' => '2026-10-15',
        'reference' => null,
        'receipt_qty' => null,
        'issue_to' => 'RIS #042 - PGSO Admin',
        'issuance_qty' => 2,
        'balance_qty' => 8,
        'remarks' => 'Issued to PGSO Admin',
        'row_order' => 2,
    ]);

    // 3. Subsequent Delivery (New PO)
    $entry3 = StockCardEntry::create([
        'stock_card_id' => $card->id,
        'entry_date' => '2026-10-21',
        'reference' => 'PO #2026-115',
        'receipt_qty' => 5,
        'issue_to' => null,
        'issuance_qty' => null,
        'balance_qty' => 13,
        'remarks' => 'Additional replenishment',
        'row_order' => 3,
    ]);

    $card->refresh();

    expect($card->entries)->toHaveCount(3)
        ->and($card->entries->first()->reference)->toBe('PO #2026-081')
        ->and((float) $card->entries->first()->receipt_qty)->toBe(10.0)
        ->and((float) $card->entries->get(1)->issuance_qty)->toBe(2.0)
        ->and((float) $card->entries->last()->balance_qty)->toBe(13.0);
});

test('deleting a stock card cascades to its entries', function () {
    $card = StockCard::factory()->create();

    StockCardEntry::factory()->count(3)->create([
        'stock_card_id' => $card->id,
    ]);

    expect(StockCardEntry::where('stock_card_id', $card->id)->count())->toBe(3);

    $card->delete();

    expect(StockCardEntry::where('stock_card_id', $card->id)->count())->toBe(0);
});

test('it correctly isolates stock per office and does not add stocks of different offices together', function () {
    $officePgso = Office::factory()->create([
        'office_name' => 'Provincial General Services Office',
        'office_code' => 'PGSO',
    ]);
    $officeAmh = Office::factory()->create([
        'office_name' => 'Aurora Memorial Hospital',
        'office_code' => 'AMH',
    ]);

    $variant = InventoryItemVariant::factory()->create([
        'variant_name' => 'Ceiling Fan',
        'quantity' => 0,
    ]);

    $user = User::factory()->create();

    // 1. PO delivery for PGSO: 4 units
    StockCard::recordReceipt(
        variant: $variant,
        quantity: 4,
        poNumber: 'PO-2026-PGSO-1',
        officeId: $officePgso->id,
        officeName: $officePgso->office_name,
        deliveryDate: '2026-10-21',
        userId: $user->id
    );

    // 2. RIS issuance for PGSO: 1 unit
    StockCard::recordIssuance(
        variant: $variant,
        quantity: 1,
        officeId: $officePgso->id,
        officeName: $officePgso->office_name,
        reference: 'RIS-202610-0001',
        date: '2026-10-21',
        userId: $user->id
    );

    // 3. PO delivery for AMH: 7 units
    StockCard::recordReceipt(
        variant: $variant,
        quantity: 7,
        poNumber: 'PO-2026-AMH-1',
        officeId: $officeAmh->id,
        officeName: $officeAmh->office_name,
        deliveryDate: '2026-10-21',
        userId: $user->id
    );

    // 4. RIS issuance for PGSO: 2 units
    StockCard::recordIssuance(
        variant: $variant,
        quantity: 2,
        officeId: $officePgso->id,
        officeName: $officePgso->office_name,
        reference: 'RIS-202610-0002',
        date: '2026-10-21',
        userId: $user->id
    );

    // Check PGSO Card: 4 - 1 - 2 = 1.0 (Only PGSO receipts and issuances)
    $pgsoCard = StockCard::with('entries')->where('inventory_item_variant_id', $variant->id)->where('office_id', $officePgso->id)->first();
    expect($pgsoCard)->not->toBeNull();
    $pgsoEntries = $pgsoCard->entries;
    expect($pgsoEntries)->toHaveCount(3)
        ->and((float) $pgsoEntries[0]->balance_qty)->toBe(4.0)
        ->and((float) $pgsoEntries[1]->balance_qty)->toBe(3.0)
        ->and((float) $pgsoEntries[2]->balance_qty)->toBe(1.0);

    // Check AMH Card: 7.0 (Strictly isolated, untouched by PGSO issuances)
    $amhCard = StockCard::with('entries')->where('inventory_item_variant_id', $variant->id)->where('office_id', $officeAmh->id)->first();
    expect($amhCard)->not->toBeNull();
    $amhEntries = $amhCard->entries;
    expect($amhEntries)->toHaveCount(1)
        ->and((float) $amhEntries[0]->balance_qty)->toBe(7.0);

    // Check Consolidated Card (All Offices): balance is maintained across all offices
    $allOfficesCard = StockCard::with('entries')->where('inventory_item_variant_id', $variant->id)->whereNull('office_id')->first();
    expect($allOfficesCard)->not->toBeNull();
    $allEntries = $allOfficesCard->entries;
    expect($allEntries)->toHaveCount(4)
        ->and((float) $allEntries[0]->balance_qty)->toBe(4.0)
        ->and($allEntries[0]->remarks)->toBeNull()
        ->and((float) $allEntries[1]->balance_qty)->toBe(3.0)
        ->and($allEntries[1]->issue_to)->toBe('RIS-202610-0001 - PGSO')
        ->and($allEntries[1]->remarks)->toBeNull()
        ->and((float) $allEntries[2]->balance_qty)->toBe(10.0) // 3.0 + 7.0 AMH delivery
        ->and((float) $allEntries[3]->balance_qty)->toBe(8.0)  // 10.0 - 2.0 PGSO issuance
        ->and($allEntries[3]->issue_to)->toBe('RIS-202610-0002 - PGSO')
        ->and($allEntries[3]->remarks)->toBeNull();
});
