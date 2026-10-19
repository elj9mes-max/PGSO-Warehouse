<?php

use App\Enums\AccountLevel;
use App\Models\Office;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('admin can view accounts page with metrics and users list', function () {
    $admin = User::factory()->admin()->create([
        'username' => 'sysadmin',
        'first_name' => 'System',
        'last_name' => 'Admin',
    ]);

    $office = Office::create([
        'office_name' => 'Provincial Engineering Office (PEO)',
        'office_code' => 'OFF-ENG-001',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create([
        'username' => 'jdelacruz',
        'first_name' => 'Juan',
        'middle_initial' => null,
        'last_name' => 'Dela Cruz',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.accounts.index'));

    $response->assertOk();
    $response->assertSeeText('Accounts & Staff Directory');
    $response->assertSee('Juan Dela Cruz');
    $response->assertSee('jdelacruz');
    $response->assertSee('OFF-ENG-001');
    $response->assertSee('Provincial Engineering Office (PEO)');
});

test('staff user cannot access admin accounts page and is redirected', function () {
    $staff = User::factory()->staff()->create();

    $response = $this->actingAs($staff)->get(route('admin.accounts.index'));

    $response->assertRedirect(route('staff.dashboard'));
});

test('admin can create a new staff account with office assignment', function () {
    $admin = User::factory()->admin()->create();
    $office = Office::create([
        'office_name' => 'Provincial Health Office (PHO)',
        'office_code' => 'OFF-PHO-001',
    ]);

    $response = $this->actingAs($admin)->post(route('admin.accounts.store'), [
        'first_name' => 'Clara',
        'middle_initial' => 'M.',
        'last_name' => 'Reyes',
        'username' => 'creyes',
        'email' => 'creyes@pgso.gov.ph',
        'account_level' => AccountLevel::Staff->value,
        'office_id' => $office->id,
        'password' => 'Warehouse123!',
        'password_confirmation' => 'Warehouse123!',
    ]);

    $response->assertRedirect(route('admin.accounts.index'));
    $response->assertSessionHas('status');

    $user = User::where('username', 'creyes')->first();
    expect($user)->not->toBeNull()
        ->and($user->first_name)->toBe('Clara')
        ->and($user->last_name)->toBe('Reyes')
        ->and($user->office_id)->toBe($office->id)
        ->and($user->account_level)->toBe(AccountLevel::Staff)
        ->and(Hash::check('Warehouse123!', $user->password))->toBeTrue();
});

test('admin can update staff details', function () {
    $admin = User::factory()->admin()->create();
    $staff = User::factory()->staff()->create([
        'first_name' => 'Pedro',
        'last_name' => 'Penduko',
    ]);

    $response = $this->actingAs($admin)->put(route('admin.accounts.update', $staff), [
        'first_name' => 'Peter',
        'middle_initial' => 'A.',
        'last_name' => 'Parker',
        'account_level' => AccountLevel::Staff->value,
    ]);

    $response->assertRedirect(route('admin.accounts.index'));

    $staff->refresh();
    expect($staff->first_name)->toBe('Peter')
        ->and($staff->last_name)->toBe('Parker')
        ->and($staff->name)->toBe('Peter Parker');
});

test('admin can reset staff username and password', function () {
    $admin = User::factory()->admin()->create();
    $staff = User::factory()->staff()->create([
        'username' => 'old_user',
        'password' => Hash::make('OldPassword123!'),
    ]);

    $response = $this->actingAs($admin)->put(route('admin.accounts.credentials', $staff), [
        'username' => 'new_user',
        'password' => 'NewSecretPassword123!',
        'password_confirmation' => 'NewSecretPassword123!',
    ]);

    $response->assertRedirect(route('admin.accounts.index'));
    $response->assertSessionHas('status');

    $staff->refresh();
    expect($staff->username)->toBe('new_user')
        ->and(Hash::check('NewSecretPassword123!', $staff->password))->toBeTrue();
});

test('admin can change staff office assignment and unassign office', function () {
    $admin = User::factory()->admin()->create();
    $office1 = Office::create(['office_name' => 'Office Alpha', 'office_code' => 'OFF-ALP-001']);
    $office2 = Office::create(['office_name' => 'Office Beta', 'office_code' => 'OFF-BET-001']);

    $staff = User::factory()->staff()->forOffice($office1)->create();
    expect($staff->office_id)->toBe($office1->id);

    // Reassign to Office Beta
    $response = $this->actingAs($admin)->put(route('admin.accounts.office', $staff), [
        'office_id' => $office2->id,
    ]);
    $response->assertRedirect(route('admin.accounts.index'));

    $staff->refresh();
    expect($staff->office_id)->toBe($office2->id);

    // Unassign to Floating staff
    $response = $this->actingAs($admin)->put(route('admin.accounts.office', $staff), [
        'office_id' => '',
    ]);
    $response->assertRedirect(route('admin.accounts.index'));

    $staff->refresh();
    expect($staff->office_id)->toBeNull();
});

test('admin cannot delete own logged-in account', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->delete(route('admin.accounts.destroy', $admin));

    $response->assertRedirect(route('admin.accounts.index'));
    $response->assertSessionHas('error');

    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});

test('admin can delete another staff account', function () {
    $admin = User::factory()->admin()->create();
    $staff = User::factory()->staff()->create();

    $response = $this->actingAs($admin)->delete(route('admin.accounts.destroy', $staff));

    $response->assertRedirect(route('admin.accounts.index'));
    $response->assertSessionHas('status');

    $this->assertDatabaseMissing('users', ['id' => $staff->id]);
});
