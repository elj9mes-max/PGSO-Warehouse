<?php

use App\Enums\InventoryLogAction;
use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use App\Models\InventoryLog;
use App\Models\Office;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('inventory_logs table has all expected columns and indexes', function () {
    expect(Schema::hasTable('inventory_logs'))->toBeTrue();

    $expectedColumns = [
        'id',
        'action_type',
        'batch_id',
        'reference_number',
        'user_id',
        'office_id',
        'inventory_item_id',
        'inventory_item_variant_id',
        'office_name',
        'item_name',
        'variant_name',
        'quantity',
        'quantity_before',
        'quantity_after',
        'recipient',
        'purpose',
        'old_values',
        'new_values',
        'remarks',
        'ip_address',
        'user_agent',
        'created_at',
        'updated_at',
    ];

    foreach ($expectedColumns as $column) {
        expect(Schema::hasColumn('inventory_logs', $column))->toBeTrue();
    }
});

test('inventory log records stock release logs correctly', function () {
    $log = InventoryLog::factory()->release()->create([
        'reference_number' => 'RIS-2026-0099',
        'recipient' => 'Juan Dela Cruz',
        'purpose' => 'Urgent road maintenance',
        'quantity' => 4,
        'quantity_before' => 10,
        'quantity_after' => 6,
    ]);

    expect($log->action_type)->toBe(InventoryLogAction::Release)
        ->and($log->action_type->label())->toBe('Stock Release')
        ->and($log->recipient)->toBe('Juan Dela Cruz')
        ->and($log->quantity)->toBe(4)
        ->and($log->quantity_before)->toBe(10)
        ->and($log->quantity_after)->toBe(6);

    expect(InventoryLog::releases()->count())->toBe(1);
});

test('inventory log records stock transfer logs correctly', function () {
    $log = InventoryLog::factory()->transfer()->create([
        'reference_number' => 'PTR-2026-0045',
        'quantity' => 15,
        'quantity_before' => 50,
        'quantity_after' => 35,
    ]);

    expect($log->action_type)->toBe(InventoryLogAction::Transfer)
        ->and($log->action_type->label())->toBe('Stock Transfer')
        ->and($log->quantity)->toBe(15);

    expect(InventoryLog::transfers()->count())->toBe(1);
});

test('inventory log records item and variant edit logs with attribute diffs', function () {
    $log = InventoryLog::factory()->itemEdit()->create([
        'old_values' => ['unit_cost' => 120, 'reorder_level' => 10],
        'new_values' => ['unit_cost' => 150, 'reorder_level' => 15],
    ]);

    expect($log->action_type)->toBe(InventoryLogAction::ItemUpdated)
        ->and($log->old_values)->toBe(['unit_cost' => 120, 'reorder_level' => 10])
        ->and($log->new_values)->toBe(['unit_cost' => 150, 'reorder_level' => 15]);

    expect(InventoryLog::edits()->count())->toBe(1);
});

test('inventory log records add inventory logs correctly', function () {
    $log = InventoryLog::factory()->inventoryAdded()->create([
        'reference_number' => 'PO-2026-0012',
        'quantity' => 100,
        'quantity_before' => 0,
        'quantity_after' => 100,
    ]);

    expect($log->action_type)->toBe(InventoryLogAction::InventoryAdded)
        ->and($log->action_type->label())->toBe('Inventory Added')
        ->and($log->quantity)->toBe(100);

    expect(InventoryLog::inventoryAdditions()->count())->toBe(1);
});

test('inventory log relationships work with users, offices, items, and variants', function () {
    $user = User::factory()->create();
    $office = Office::factory()->create();
    $item = InventoryItem::factory()->create();
    $variant = InventoryItemVariant::factory()->create(['inventory_item_id' => $item->id]);

    $log = InventoryLog::create([
        'action_type' => InventoryLogAction::Transfer,
        'user_id' => $user->id,
        'office_id' => $office->id,
        'inventory_item_id' => $item->id,
        'inventory_item_variant_id' => $variant->id,
        'office_name' => $office->office_name,
        'item_name' => $item->name,
        'variant_name' => $variant->variant_name,
        'quantity' => 10,
    ]);

    expect($log->user->id)->toBe($user->id)
        ->and($log->office->id)->toBe($office->id)
        ->and($log->inventoryItem->id)->toBe($item->id)
        ->and($log->variant->id)->toBe($variant->id);
});
