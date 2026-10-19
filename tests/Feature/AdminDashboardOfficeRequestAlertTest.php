<?php

use App\Models\Office;
use App\Models\RequisitionRequest;
use App\Models\RequisitionRequestItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin dashboard does not display office request alert when there are no pending requests', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertDontSee('id="pendingOfficeRequestsAlert"', false);
    $response->assertDontSee('Warehouse Reminder &bull; Action Required', false);
});

test('admin dashboard displays full width alert when a pending request is sent by an office', function () {
    $admin = User::factory()->admin()->create();

    $office = Office::create([
        'office_name' => 'Provincial Engineering Office',
        'office_code' => 'PEO-001',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create([
        'first_name' => 'Carlos',
        'last_name' => 'Dela Cruz',
    ]);

    $requisition = RequisitionRequest::factory()->create([
        'office_id' => $office->id,
        'user_id' => $staff->id,
        'request_number' => 'RIS-202610-0012',
        'status' => 'pending',
        'purpose' => 'Infrastructure field survey supplies',
        'requested_by_name' => 'Carlos Dela Cruz',
        'requested_by_designation' => 'Civil Engineer I',
    ]);

    RequisitionRequestItem::create([
        'requisition_request_id' => $requisition->id,
        'item_name' => 'Survey Marker Ribbon',
        'unit' => 'rolls',
        'quantity_requested' => 5,
        'quantity_issued' => 0,
        'unit_cost' => 150.00,
        'total_cost' => 750.00,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertSee('pending-office-request-alert');
    $response->assertSee('Warehouse Reminder &bull; Action Required', false);
    $response->assertSee('Provincial Engineering Office');
    $response->assertSee('RIS-202610-0012');
    $response->assertSee('Carlos Dela Cruz');
    $response->assertSee('Take a Look at Request');
    $response->assertSee(route('admin.requisitions.index'));
});

test('admin dashboard displays alert with count when multiple pending requests are sent by offices', function () {
    $admin = User::factory()->admin()->create();

    $office1 = Office::create([
        'office_name' => 'Provincial Health Office',
        'office_code' => 'PHO-001',
    ]);

    $office2 = Office::create([
        'office_name' => 'Provincial Social Welfare Office',
        'office_code' => 'PSWO-002',
    ]);

    $staff1 = User::factory()->staff()->forOffice($office1)->create();
    $staff2 = User::factory()->staff()->forOffice($office2)->create();

    RequisitionRequest::factory()->create([
        'office_id' => $office1->id,
        'user_id' => $staff1->id,
        'request_number' => 'RIS-202610-0001',
        'status' => 'pending',
        'created_at' => now()->subHours(2),
    ]);

    $req2 = RequisitionRequest::factory()->create([
        'office_id' => $office2->id,
        'user_id' => $staff2->id,
        'request_number' => 'RIS-202610-0002',
        'status' => 'pending',
        'created_at' => now()->subMinutes(15),
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertSee('pending-office-request-alert');
    $response->assertSee('2 requisition requests', false);
    $response->assertSee('Provincial Social Welfare Office');
    $response->assertSee('RIS-202610-0002');
    $response->assertSee('Take a Look at Request');
});
