<?php

use App\Models\Category;
use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use App\Models\Office;
use App\Models\RequisitionRequest;
use App\Models\RequisitionRequestItem;
use App\Models\SsmiReport;
use App\Models\SsmiReportItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot access ssmi report page and is redirected to login', function () {
    $response = $this->get(route('admin.reports.ssmi'));

    $response->assertRedirect(route('login'));
});

test('staff user is redirected away from admin ssmi report', function () {
    $staff = User::factory()->staff()->create();

    $response = $this->actingAs($staff)->get(route('admin.reports.ssmi'));

    $response->assertRedirect(route('staff.dashboard'));
});

test('admin can access ssmi report and sees proper structure matching government form', function () {
    $admin = User::factory()->admin()->create();

    $office = Office::create([
        'office_name' => 'Provincial General Services Office',
        'office_code' => 'PGSO',
    ]);

    $req = RequisitionRequest::factory()->create([
        'office_id' => $office->id,
        'request_number' => 'RIS-2026-0534',
        'ris_no' => '2026-08-0534',
        'status' => 'issued',
    ]);

    RequisitionRequestItem::factory()->create([
        'requisition_request_id' => $req->id,
        'item_name' => 'Bond Paper Legal',
        'unit' => 'reams',
        'quantity_issued' => 15,
        'unit_cost' => 250.00,
        'total_cost' => 3750.00,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.reports.ssmi'));

    $response->assertOk();
    $response->assertSeeText('SUMARY OF SUPPLIES AND MATERIALS ISSUED');
    $response->assertSeeText('PROVINCIAL GOVERNMENT OF AURORA');
    $response->assertSeeText('Requisition and Issue Slip Numbers');
    $response->assertSeeText('Total Quantity Issued');
    $response->assertSeeText('Raymond G. Amat');
    $response->assertSeeText('AA-III Warehouse');
    $response->assertSeeText('Joel Friginal');
    $response->assertSeeText('SAO');
    $response->assertSeeText('Accounting');
});

test('admin can filter ssmi report by from and to calendar date range without office selection', function () {
    $admin = User::factory()->admin()->create();

    $reportAugust = SsmiReport::factory()->create([
        'report_no' => 'SSMI-2026-08-0001',
        'period_date' => '2026-08-15',
        'period_text' => '08/15/2026',
    ]);

    $reportSeptember = SsmiReport::factory()->create([
        'report_no' => 'SSMI-2026-09-0001',
        'period_date' => '2026-09-20',
        'period_text' => '09/20/2026',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.reports.ssmi', [
        'from_date' => '2026-08-01',
        'to_date' => '2026-08-31',
    ]));

    $response->assertOk();
    $response->assertSee('2026-08-01');
    $response->assertSee('2026-08-31');
    $response->assertSee('SSMI-2026-08-0001');
    $response->assertDontSee('SSMI-2026-09-0001');

    // Calendar inputs present
    $response->assertSee('name="from_date"', false);
    $response->assertSee('name="to_date"', false);

    // No office selection dropdown rendered
    $response->assertDontSee('name="office_id"', false);
});

test('picking the same from and to date treats it as one day and displays all reports made that day', function () {
    $admin = User::factory()->admin()->create();

    $reportTodayCreated = SsmiReport::factory()->create([
        'report_no' => 'SSMI-TODAY-CREATED-01',
        'period_date' => '2026-08-12',
        'created_at' => '2026-10-22 10:00:00',
    ]);

    $reportTodayPeriod = SsmiReport::factory()->create([
        'report_no' => 'SSMI-TODAY-PERIOD-02',
        'period_date' => '2026-10-22',
        'created_at' => '2026-10-22 11:00:00',
    ]);

    $reportOtherDay = SsmiReport::factory()->create([
        'report_no' => 'SSMI-OTHER-DAY-03',
        'period_date' => '2026-10-20',
        'created_at' => '2026-10-20 10:00:00',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.reports.ssmi', [
        'from_date' => '2026-10-22',
        'to_date' => '2026-10-22',
    ]));

    $response->assertOk();
    $response->assertSee('SSMI-TODAY-CREATED-01');
    $response->assertSee('SSMI-TODAY-PERIOD-02');
    $response->assertDontSee('SSMI-OTHER-DAY-03');
    // Displays single day period (not 10/22/2026 - 10/22/2026)
    $response->assertSee('10/22/2026');
    $response->assertDontSee('10/22/2026 - 10/22/2026');
});

test('ssmi report renders multiple pages when ris columns exceed 8', function () {
    $admin = User::factory()->admin()->create();

    // Create a report with 10 RIS columns (exceeding GAM Appendix 64 limit of 8 per page)
    $tenCols = [
        'RIS-2026-0001', 'RIS-2026-0002', 'RIS-2026-0003', 'RIS-2026-0004',
        'RIS-2026-0005', 'RIS-2026-0006', 'RIS-2026-0007', 'RIS-2026-0008',
        'RIS-2026-0009', 'RIS-2026-0010',
    ];

    $report = SsmiReport::factory()->create([
        'report_no' => 'SSMI-MULTI-PAGE-001',
        'ris_columns' => $tenCols,
    ]);

    SsmiReportItem::factory()->create([
        'ssmi_report_id' => $report->id,
        'description' => 'Item On Page 1 and 2',
        'ris_quantities' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
    ]);

    $response = $this->actingAs($admin)->get(route('admin.reports.ssmi', ['report_id' => $report->id]));

    $response->assertOk();
    $response->assertSee('2 Pages');
    $response->assertSee('Page 1 of 2');
    $response->assertSee('Page 2 of 2');
    $response->assertSee('RIS-2026-0001');
    $response->assertSee('RIS-2026-0009');
    $response->assertSee('RIS-2026-0010');
});

test('admin can save ssmi report to database with items', function () {
    $admin = User::factory()->admin()->create();

    $office = Office::create([
        'office_name' => 'Provincial Agriculture Office',
        'office_code' => 'PAGRO',
    ]);

    $payload = [
        'period_text' => '08/12/2026',
        'period_date' => '2026-08-12',
        'office_id' => $office->id,
        'ris_columns' => ['2026-08-0534', '2026-08-0527', '', '', '', '', '', ''],
        'prepared_by_name' => 'Raymond G. Amat',
        'prepared_by_designation' => 'AA-III Warehouse',
        'approved_by_name' => 'Joel Friginal',
        'approved_by_designation' => 'SAO',
        'items' => [
            [
                'row_order' => 1,
                'stock_no' => 'PAP-001',
                'description' => 'Bond Paper Legal',
                'unit' => 'reams',
                'ris_quantities' => [15, '', '', '', '', '', '', ''],
                'total_quantity' => 15,
                'unit_cost' => 250.00,
                'total_cost' => 3750.00,
            ],
            [
                'row_order' => 2,
                'stock_no' => 'PAP-002',
                'description' => 'Bond Paper A4',
                'unit' => 'reams',
                'ris_quantities' => ['', 10, '', '', '', '', '', ''],
                'total_quantity' => 10,
                'unit_cost' => 250.00,
                'total_cost' => 2500.00,
            ],
        ],
    ];

    $response = $this->actingAs($admin)->postJson(route('admin.reports.ssmi.save'), $payload);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'status' => 'draft',
            'total_amount' => 6250.00,
        ]);

    $this->assertDatabaseHas('ssmi_reports', [
        'period_text' => '08/12/2026',
        'office_id' => $office->id,
        'user_id' => $admin->id,
        'prepared_by_name' => 'Raymond G. Amat',
        'total_amount' => 6250.00,
    ]);

    $this->assertDatabaseHas('ssmi_report_items', [
        'description' => 'Bond Paper Legal',
        'unit' => 'reams',
        'total_quantity' => 15,
        'total_cost' => 3750.00,
    ]);

    $this->assertDatabaseHas('ssmi_report_items', [
        'description' => 'Bond Paper A4',
        'unit' => 'reams',
        'total_quantity' => 10,
        'total_cost' => 2500.00,
    ]);
});

test('admin can update an existing ssmi report in database', function () {
    $admin = User::factory()->admin()->create();

    $report = SsmiReport::factory()->create([
        'period_text' => 'Old Period',
        'total_amount' => 100.00,
    ]);

    $payload = [
        'report_id' => $report->id,
        'period_text' => 'Updated Period 2026',
        'items' => [
            [
                'row_order' => 1,
                'stock_no' => 'NEW-001',
                'description' => 'Updated Item',
                'unit' => 'box',
                'ris_quantities' => [5, '', '', '', '', '', '', ''],
                'total_quantity' => 5,
                'unit_cost' => 200.00,
                'total_cost' => 1000.00,
            ],
        ],
    ];

    $response = $this->actingAs($admin)->postJson(route('admin.reports.ssmi.save'), $payload);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'report_id' => $report->id,
            'total_amount' => 1000.00,
        ]);

    $report->refresh();
    expect($report->period_text)->toBe('Updated Period 2026')
        ->and((float) $report->total_amount)->toBe(1000.00)
        ->and($report->items)->toHaveCount(1)
        ->and($report->items->first()->description)->toBe('Updated Item');
});

test('admin can load saved ssmi report via query param', function () {
    $admin = User::factory()->admin()->create();

    $report = SsmiReport::factory()->create([
        'report_no' => 'SSMI-TEST-9999',
        'period_text' => 'Special Period August',
    ]);

    SsmiReportItem::factory()->create([
        'ssmi_report_id' => $report->id,
        'description' => 'Specific Unique Saved Item',
        'total_quantity' => 42,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.reports.ssmi', ['report_id' => $report->id]));

    $response->assertOk();
    $response->assertSee('SSMI-TEST-9999');
    $response->assertSee('Special Period August');
    $response->assertSee('Specific Unique Saved Item');
});

test('admin can delete ssmi report', function () {
    $admin = User::factory()->admin()->create();

    $report = SsmiReport::factory()->create();

    SsmiReportItem::factory()->create([
        'ssmi_report_id' => $report->id,
    ]);

    $response = $this->actingAs($admin)->deleteJson(route('admin.reports.ssmi.destroy', $report));

    $response->assertOk()->assertJson(['success' => true]);

    $this->assertDatabaseMissing('ssmi_reports', ['id' => $report->id]);
    $this->assertDatabaseMissing('ssmi_report_items', ['ssmi_report_id' => $report->id]);
});

test('approving an ris requisition on admin requisition review automatically inserts it into ssmi database and renders on report', function () {
    $admin = User::factory()->admin()->create();

    $office = Office::create([
        'office_name' => 'Provincial General Services Office',
        'office_code' => 'PGSO',
    ]);

    $category = Category::firstOrCreate(['name' => 'Office Supplies']);
    $item = InventoryItem::create([
        'name' => 'Standard Bond Paper',
        'item_code' => 'PAP-STD-001',
        'category' => $category->name,
        'quantity' => 100,
        'unit' => 'reams',
        'unit_cost' => 250.00,
        'status' => 'In Stock',
        'reorder_level' => 10,
    ]);

    $variant = InventoryItemVariant::create([
        'inventory_item_id' => $item->id,
        'variant_name' => 'Legal 80gsm',
        'sku' => 'PAP-STD-001-LGL',
        'barcode' => 'BAR-PAP-001',
        'quantity' => 100,
        'unit' => 'reams',
        'unit_cost' => 250.00,
        'status' => 'In Stock',
        'reorder_level' => 10,
    ]);

    $requisition = RequisitionRequest::create([
        'request_number' => 'REQ-2026-0899',
        'request_type' => 'RIS',
        'ris_no' => '2026-08-0899',
        'office_id' => $office->id,
        'user_id' => $admin->id,
        'status' => 'pending',
        'purpose' => 'Annual Supplies',
    ]);

    $reqItem = RequisitionRequestItem::create([
        'requisition_request_id' => $requisition->id,
        'inventory_item_variant_id' => $variant->id,
        'item_name' => 'Standard Bond Paper',
        'variant_name' => 'Legal 80gsm',
        'stock_no' => 'PAP-STD-001-LGL',
        'quantity_requested' => 15,
        'quantity_issued' => 0,
        'unit' => 'reams',
        'unit_cost' => 250.00,
        'total_cost' => 3750.00,
        'status' => 'pending',
    ]);

    // Admin reviews and approves the RIS
    $response = $this->actingAs($admin)->post(route('admin.requisitions.review', $requisition), [
        'action' => 'approve',
        'items' => [
            [
                'id' => $reqItem->id,
                'quantity' => 15,
            ],
        ],
    ]);

    $response->assertRedirect(route('admin.requisitions.index'));

    // Verify it automatically got inserted into the SSMI database table!
    $this->assertDatabaseHas('ssmi_reports', [
        'status' => 'draft',
        'total_amount' => 3750.00,
    ]);

    $this->assertDatabaseHas('ssmi_report_items', [
        'stock_no' => 'PAP-STD-001-LGL',
        'description' => 'Standard Bond Paper',
        'unit' => 'reams',
        'total_quantity' => 15,
        'unit_cost' => 250.00,
        'total_cost' => 3750.00,
    ]);

    $ssmiReport = SsmiReport::first();
    expect($ssmiReport->ris_columns[0])->toBe('2026-08-0899');

    // Visit the SSMI report page and verify it renders directly from the SSMI database
    $pageResponse = $this->actingAs($admin)->get(route('admin.reports.ssmi'));
    $pageResponse->assertOk();
    $pageResponse->assertSee('2026-08-0899');
    $pageResponse->assertSee('Standard Bond Paper');
    $pageResponse->assertSee($ssmiReport->report_no);
});
