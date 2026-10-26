<?php

use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use App\Models\Office;
use App\Models\OfficeInventory;
use App\Models\RequisitionRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('staff user accessing dashboard is redirected straight to inventory as dashboard', function () {
    $office = Office::create([
        'office_name' => "Provincial Governor's Office",
        'office_code' => 'OFF-PGO-001',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create([
        'first_name' => 'Maria',
        'last_name' => 'Santos',
    ]);

    $item = InventoryItem::create([
        'item_code' => 'ITM-9001',
        'name' => 'A4 Multipurpose Copy Paper',
        'description' => '80gsm high opacity copy paper reams',
        'category' => 'Office Supplies',
        'unit' => 'reams',
        'quantity' => 100,
        'reorder_level' => 10,
        'unit_cost' => 250.00,
        'status' => 'In Stock',
        'location' => 'Bay 1',
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'A4 80gsm Box of 5 Reams',
        'barcode' => 'BAR-A4-80GSM',
        'sku' => 'SKU-A4-80GSM',
        'quantity' => 50,
        'available_for_transfer' => 50,
        'unit' => 'boxes',
        'reorder_level' => 5,
        'unit_cost' => 1250.00,
        'location' => 'Cabinet 2',
        'status' => 'In Stock',
    ]);

    OfficeInventory::create([
        'office_id' => $office->id,
        'inventory_item_variants_id' => $variant->id,
        'quantity' => 25,
    ]);

    // Accessing dashboard redirects straight to inventory
    $response = $this->actingAs($staff)->get(route('staff.dashboard'));
    $response->assertRedirect(route('staff.inventory'));

    // Following redirect displays the office stock inventory
    $followResponse = $this->actingAs($staff)->followingRedirects()->get(route('staff.dashboard'));
    $followResponse->assertOk();
    $followResponse->assertSeeText("Provincial Governor's Office");
    $followResponse->assertSeeText('OFF-PGO-001');
    $followResponse->assertSeeText('A4 Multipurpose Copy Paper');
    $followResponse->assertSeeText('A4 80gsm Box of 5 Reams');
    $followResponse->assertSeeText('SKU-A4-80GSM');
    $followResponse->assertSee('25');
    $followResponse->assertSeeText('boxes');
});

test('staff user accessing dashboard with query params forwards them to inventory', function () {
    $staff = User::factory()->staff()->create([
        'office_id' => null,
    ]);

    $office = Office::create([
        'office_name' => 'Provincial Engineering Office',
        'office_code' => 'OFF-PEO-001',
    ]);

    // Switch office via query param redirects with param preserved
    $response = $this->actingAs($staff)->get(route('staff.dashboard', ['office_id' => $office->id]));
    $response->assertRedirect(route('staff.inventory', ['office_id' => $office->id]));

    $followResponse = $this->actingAs($staff)->followingRedirects()->get(route('staff.dashboard', ['office_id' => $office->id]));
    $followResponse->assertOk();
    $followResponse->assertSeeText('Provincial Engineering Office');
    $followResponse->assertSeeText('OFF-PEO-001');
});

test('staff bottom nav displays unapproved requests badge on request stat and removes badge from requisitions', function () {
    $office = Office::create([
        'office_name' => 'Provincial Health Office',
        'office_code' => 'OFF-PHO-001',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create();

    // Create 3 pending (unapproved) requisition requests
    RequisitionRequest::factory()->count(3)->create([
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'status' => 'pending',
    ]);

    // Create 1 approved request (should not be in unapproved count)
    RequisitionRequest::factory()->create([
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'status' => 'approved',
    ]);

    $response = $this->actingAs($staff)->get(route('staff.inventory'));
    $response->assertOk();

    // Requisition item should not have hardcoded "6" badge
    $response->assertDontSee('<span class="staff-nav-badge">6</span>', false);

    // Request stat should have the unapproved count (3) badge
    $response->assertSee('<span class="staff-nav-badge staff-nav-badge-pending"', false);
    $response->assertSee('3');
});
