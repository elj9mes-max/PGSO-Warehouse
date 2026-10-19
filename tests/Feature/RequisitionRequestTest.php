<?php

use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use App\Models\Office;
use App\Models\RequisitionRequest;
use App\Models\RequisitionRequestItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('requisition request and items can be created with relationships', function () {
    $office = Office::create([
        'office_name' => 'Provincial General Services Office',
        'office_code' => 'PGSO',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create([
        'first_name' => 'Ricardo',
        'last_name' => 'Bautista',
    ]);

    $item = InventoryItem::create([
        'item_code' => 'ITM-PAPER-01',
        'name' => 'A4 Copy Paper 80gsm',
        'category' => 'Supplies',
        'unit' => 'reams',
        'quantity' => 100,
        'reorder_level' => 10,
        'unit_cost' => 240.00,
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Box of 5 Reams',
        'barcode' => 'BAR-PAPER-01',
        'sku' => 'SKU-A4-5R',
        'unit' => 'boxes',
        'quantity' => 20,
        'unit_cost' => 1200.00,
    ]);

    $request = RequisitionRequest::create([
        'request_number' => RequisitionRequest::generateRequestNumber('RIS'),
        'request_type' => 'RIS',
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'status' => 'pending',
        'purpose' => 'Official monthly office paperwork',
        'responsibility_center_code' => '1061',
        'requested_by_name' => 'Ricardo Q. Bautista',
        'requested_by_designation' => 'PGDH-PGSO',
    ]);

    $requestItem = RequisitionRequestItem::create([
        'requisition_request_id' => $request->id,
        'inventory_item_variant_id' => $variant->id,
        'stock_no' => $variant->sku,
        'item_name' => $item->name,
        'variant_name' => $variant->variant_name,
        'unit' => 'boxes',
        'quantity_requested' => 3,
        'unit_cost' => 1200.00,
        'total_cost' => 3600.00,
    ]);

    // Assert database contents
    $this->assertDatabaseHas('requisition_requests', [
        'id' => $request->id,
        'request_type' => 'RIS',
        'status' => 'pending',
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'responsibility_center_code' => '1061',
    ]);

    $this->assertDatabaseHas('requisition_request_items', [
        'id' => $requestItem->id,
        'requisition_request_id' => $request->id,
        'stock_no' => 'SKU-A4-5R',
        'quantity_requested' => 3,
        'total_cost' => 3600.00,
    ]);

    // Assert Eloquent relationships
    expect($request->office->id)->toBe($office->id)
        ->and($request->user->id)->toBe($staff->id)
        ->and($request->items)->toHaveCount(1)
        ->and($request->items->first()->variant->id)->toBe($variant->id)
        ->and($staff->requisitionRequests)->toHaveCount(1)
        ->and($office->requisitionRequests)->toHaveCount(1);
});

test('requisition request factories generate valid mock records with states', function () {
    $request = RequisitionRequest::factory()
        ->approved()
        ->create();

    $items = RequisitionRequestItem::factory()
        ->count(3)
        ->for($request, 'request')
        ->create();

    expect($request->status)->toBe('approved')
        ->and($request->approved_at)->not->toBeNull()
        ->and($request->items)->toHaveCount(3);
});

test('generateRequestNumber creates incrementing formatted tracking IDs', function () {
    $firstNumber = RequisitionRequest::generateRequestNumber('RIS');
    expect($firstNumber)->toStartWith('RIS-'.date('Ym').'-');

    $request = RequisitionRequest::factory()->create([
        'request_number' => $firstNumber,
    ]);

    $secondNumber = RequisitionRequest::generateRequestNumber('RIS');
    expect($secondNumber)->not->toBe($firstNumber);
});
