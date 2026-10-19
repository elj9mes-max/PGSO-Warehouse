<?php

use App\Models\Office;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('users table has office_id column', function () {
    expect(Schema::hasColumn('users', 'office_id'))->toBeTrue();
});

test('user can belong to an office and resolves office relationship', function () {
    $office = Office::create([
        'office_name' => "Governor's Office",
        'office_code' => 'OFF-GOV-001',
    ]);

    $user = User::factory()->forOffice($office)->create();

    expect($user->office_id)->toBe($office->id)
        ->and($user->office)->not->toBeNull()
        ->and($user->office->id)->toBe($office->id)
        ->and($user->office->office_name)->toBe("Governor's Office");
});

test('office has many users relationship', function () {
    $office = Office::create([
        'office_name' => 'Accounting Office',
        'office_code' => 'OFF-ACC-001',
    ]);

    $user1 = User::factory()->forOffice($office)->create(['username' => 'staff1']);
    $user2 = User::factory()->forOffice($office)->create(['username' => 'staff2']);

    expect($office->users)->toHaveCount(2)
        ->and($office->users->pluck('id'))->toContain($user1->id, $user2->id);
});

test('user can exist without an office', function () {
    $user = User::factory()->create([
        'office_id' => null,
    ]);

    expect($user->office_id)->toBeNull()
        ->and($user->office)->toBeNull();
});

test('office deletion sets user office_id to null', function () {
    $office = Office::create([
        'office_name' => 'IT Department',
        'office_code' => 'OFF-IT-001',
    ]);

    $user = User::factory()->forOffice($office)->create();

    expect($user->office_id)->toBe($office->id);

    $office->delete();

    $user->refresh();
    expect($user->office_id)->toBeNull();
});
