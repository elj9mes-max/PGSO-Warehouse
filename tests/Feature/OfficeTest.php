<?php

use App\Enums\AccountLevel;
use App\Models\Office;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view offices list page with offices and stats', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    Office::create([
        'office_name' => "Provincial Governor's Office (PGO)",
        'office_code' => 'OFF-PGO-001',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.offices.index'));

    $response->assertOk();
    $response->assertSee("Provincial Governor's Office (PGO)");
    $response->assertSee('OFF-PGO-001');
});

test('admin can create a new office', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.offices.store'), [
        'office_name' => 'Provincial Human Resource Management Office (PHRMO)',
        'office_code' => 'OFF-HR-007',
    ]);

    $response->assertRedirect(route('admin.offices.index'));
    $this->assertDatabaseHas('offices', [
        'office_name' => 'Provincial Human Resource Management Office (PHRMO)',
        'office_code' => 'OFF-HR-007',
    ]);
});

test('office code must be unique', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    Office::create([
        'office_name' => 'First Office',
        'office_code' => 'OFF-DUP-01',
    ]);

    $response = $this->actingAs($admin)->post(route('admin.offices.store'), [
        'office_name' => 'Second Office',
        'office_code' => 'OFF-DUP-01',
    ]);

    $response->assertSessionHasErrors('office_code');
});

test('office name and office code are required', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.offices.store'), [
        'office_name' => '',
        'office_code' => '',
    ]);

    $response->assertSessionHasErrors(['office_name', 'office_code']);
});

test('admin can update an office details', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $office = Office::create([
        'office_name' => 'Old Office Name',
        'office_code' => 'OFF-OLD-01',
    ]);

    $response = $this->actingAs($admin)->put(route('admin.offices.update', $office), [
        'office_name' => 'Updated Office Name',
        'office_code' => 'OFF-NEW-01',
    ]);

    $response->assertRedirect(route('admin.offices.index'));
    $this->assertDatabaseHas('offices', [
        'id' => $office->id,
        'office_name' => 'Updated Office Name',
        'office_code' => 'OFF-NEW-01',
    ]);
});

test('admin can delete an office', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $office = Office::create([
        'office_name' => 'Office to be deleted',
        'office_code' => 'OFF-DEL-01',
    ]);

    $response = $this->actingAs($admin)->delete(route('admin.offices.destroy', $office));

    $response->assertRedirect(route('admin.offices.index'));
    $this->assertDatabaseMissing('offices', [
        'id' => $office->id,
    ]);
});

test('non-admin user cannot access offices route', function () {
    $staff = User::factory()->create([
        'account_level' => AccountLevel::Staff,
    ]);

    $this->actingAs($staff)->get(route('admin.offices.index'))
        ->assertRedirect(route('staff.dashboard'));

    $this->actingAs($staff)->post(route('admin.offices.store'), [
        'office_name' => 'Staff Office',
        'office_code' => 'OFF-STF-01',
    ])->assertRedirect(route('staff.dashboard'));
});
