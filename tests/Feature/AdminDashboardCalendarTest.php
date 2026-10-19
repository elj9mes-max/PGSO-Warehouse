<?php

use App\Enums\AccountLevel;
use App\Enums\InventoryLogAction;
use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use App\Models\InventoryLog;
use App\Models\Office;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin dashboard renders big calendar with release information and metrics', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $office = Office::create([
        'office_name' => 'Provincial Disaster Risk Reduction Office',
        'office_code' => 'OFF-DRR-001',
    ]);

    $item = InventoryItem::factory()->create([
        'name' => 'High-Visibility Emergency Vest',
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Neon Orange - Large',
        'barcode' => 'BAR-VEST-01',
        'quantity' => 50,
        'available_for_transfer' => 20,
        'reorder_level' => 10,
    ]);

    $log = InventoryLog::create([
        'action_type' => InventoryLogAction::Release,
        'batch_id' => 'REL-00042',
        'reference_number' => 'RIS-2026-0042',
        'user_id' => $admin->id,
        'office_id' => $office->id,
        'inventory_item_id' => $item->id,
        'inventory_item_variant_id' => $variant->id,
        'office_name' => $office->office_name,
        'item_name' => $item->name,
        'variant_name' => $variant->variant_name,
        'quantity' => 12,
        'quantity_before' => 20,
        'quantity_after' => 8,
        'recipient' => 'Rescue Unit Alpha',
        'purpose' => 'Typhoon readiness operation',
        'remarks' => 'Urgent deployment release',
        'created_at' => now(),
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertSee('Warehouse Administration');
    $response->assertSee('releasesCalendarCard');
    $response->assertSee('calendarDaysGrid');
    $response->assertSee('calendarInspectorPanel');
    $response->assertSee('High-Visibility Emergency Vest');
    $response->assertSee('RIS-2026-0042');
    $response->assertSee('Rescue Unit Alpha');
    $response->assertSee('Provincial Disaster Risk Reduction Office');
});
