<?php

use App\Enums\AccountLevel;
use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('login screen can be rendered', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('signup screen can be rendered', function () {
    $response = $this->get('/signup');

    $response->assertStatus(200);
});

test('users can register with eloquent and are assigned staff account level and redirected to staff dashboard', function () {
    $response = $this->post('/signup', [
        'first_name' => 'Maria',
        'middle_initial' => 'C.',
        'last_name' => 'Santos',
        'username' => 'msantos',
        'password' => 'Warehouse123!',
        'password_confirmation' => 'Warehouse123!',
    ]);

    $response->assertRedirect('/staff/inventory');
    $this->assertAuthenticated();

    $user = User::where('username', 'msantos')->first();

    expect($user)->not->toBeNull()
        ->and($user->first_name)->toBe('Maria')
        ->and($user->last_name)->toBe('Santos')
        ->and($user->account_level)->toBe(AccountLevel::Staff)
        ->and(Hash::check('Warehouse123!', $user->password))->toBeTrue();
});

test('registration requires confirmed password and unique username', function () {
    User::factory()->create(['username' => 'taken_user']);

    $response = $this->post('/signup', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'username' => 'taken_user',
        'password' => 'secret123',
        'password_confirmation' => 'different_password',
    ]);

    $response->assertSessionHasErrors(['username', 'password']);
    $this->assertGuest();
});

test('admin user is redirected to admin dashboard on login', function () {
    $user = User::factory()->admin()->create([
        'username' => 'warehouse_admin',
        'password' => 'SecretPass123!',
    ]);

    $response = $this->post('/login', [
        'email' => 'warehouse_admin',
        'password' => 'SecretPass123!',
    ]);

    $response->assertRedirect('/admin/dashboard');
    $this->assertAuthenticatedAs($user);
});

test('staff user is redirected to staff dashboard on login', function () {
    $user = User::factory()->staff()->create([
        'email' => 'staff@pgso.gov.ph',
        'password' => 'SecretPass123!',
    ]);

    $response = $this->post('/login', [
        'email' => 'staff@pgso.gov.ph',
        'password' => 'SecretPass123!',
    ]);

    $response->assertRedirect('/staff/inventory');
    $this->assertAuthenticatedAs($user);
});

test('staff user cannot access admin dashboard and is redirected to staff dashboard', function () {
    $staff = User::factory()->staff()->create();

    $response = $this->actingAs($staff)->get('/admin/dashboard');

    $response->assertRedirect('/staff/dashboard');
});

test('admin user can access admin dashboard', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get('/admin/dashboard');

    $response->assertStatus(200);
});

test('staff user accessing staff dashboard redirects to inventory', function () {
    $staff = User::factory()->staff()->create();

    $response = $this->actingAs($staff)->get('/staff/dashboard');

    $response->assertRedirect('/staff/inventory');
});

test('dashboard gateway redirects admin to admin dashboard and staff to staff inventory', function () {
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin)->get('/dashboard')->assertRedirect('/admin/dashboard');

    $staff = User::factory()->staff()->create();
    $this->actingAs($staff)->get('/dashboard')->assertRedirect('/staff/inventory');
});

test('users cannot authenticate with invalid password', function () {
    $user = User::factory()->create([
        'username' => 'officer1',
        'password' => 'CorrectPassword!',
    ]);

    $response = $this->post('/login', [
        'email' => 'officer1',
        'password' => 'WrongPassword',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('users can log out', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect('/');
    $this->assertGuest();
});

test('unauthenticated users are redirected from dashboard to login', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/');
});

test('admin user can access inventory records page', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get('/admin/inventory');

    $response->assertStatus(200);
});

test('staff user cannot access admin inventory records page', function () {
    $staff = User::factory()->staff()->create();

    $response = $this->actingAs($staff)->get('/admin/inventory');

    $response->assertRedirect('/staff/dashboard');
});

test('admin user can create an inventory item with unit column', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post('/admin/inventory', [
        'item_code' => 'ITM-9901',
        'name' => 'Heavy Duty Steel Strapping',
        'description' => '19mm tensile steel packing strap',
        'category' => 'Hardware & Tools',
        'unit' => 'coils',
        'quantity' => 25,
        'reorder_level' => 5,
        'unit_cost' => 3200.00,
        'location' => 'Bay Section D',
    ]);

    $response->assertRedirect('/admin/inventory');
    $response->assertSessionHas('status');

    $item = InventoryItem::where('item_code', 'ITM-9901')->first();
    expect($item)->not->toBeNull()
        ->and($item->unit)->toBe('coils')
        ->and($item->name)->toBe('Heavy Duty Steel Strapping')
        ->and($item->status)->toBe('In Stock');
});
