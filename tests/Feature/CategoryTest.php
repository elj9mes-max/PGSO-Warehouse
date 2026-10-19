<?php

use App\Enums\AccountLevel;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can see category section and plus icon to add category on inventory page', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    Category::create(['name' => 'Packaging Materials']);

    $response = $this->actingAs($admin)->get(route('admin.inventory'));

    $response->assertOk();
    $response->assertSee('Packaging Materials');
    $response->assertSee('id="openAddCategoryBtn"', false);
    $response->assertSee('id="addCategoryModal"', false);
    $response->assertSee('id="newItemCategory"', false);
});

test('admin can create a category via ajax request', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $response = $this->actingAs($admin)->postJson(route('admin.categories.store'), [
        'name' => 'Heavy Machinery',
    ]);

    $response->assertCreated();
    $response->assertJson([
        'message' => 'Category created successfully.',
        'category' => [
            'name' => 'Heavy Machinery',
        ],
    ]);

    $this->assertDatabaseHas('categories', [
        'name' => 'Heavy Machinery',
    ]);
});

test('admin cannot create a category with a duplicate name', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    // 'Hardware & Tools' is already seeded by migration
    $response = $this->actingAs($admin)->postJson(route('admin.categories.store'), [
        'name' => 'Hardware & Tools',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('name');
});

test('category name is required and trimmed', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $response = $this->actingAs($admin)->postJson(route('admin.categories.store'), [
        'name' => '',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('name');
});

test('guest or staff user cannot create a category', function () {
    $staff = User::factory()->create([
        'account_level' => AccountLevel::Staff,
    ]);

    $this->post(route('admin.categories.store'), [
        'name' => 'Disallowed Category 1',
    ])->assertRedirect(route('login'));

    $this->actingAs($staff)->post(route('admin.categories.store'), [
        'name' => 'Disallowed Category 2',
    ])->assertRedirect(route('staff.dashboard'));

    $this->assertDatabaseMissing('categories', [
        'name' => 'Disallowed Category 1',
    ]);
    $this->assertDatabaseMissing('categories', [
        'name' => 'Disallowed Category 2',
    ]);
});

test('admin can create inventory item with newly added category', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $category = Category::create(['name' => 'Safety & Surveillance']);

    $response = $this->actingAs($admin)->post(route('admin.inventory.store'), [
        'item_code' => 'CAM-001',
        'name' => 'IP Security Camera 4K',
        'description' => 'Outdoor waterproof camera',
        'category' => $category->name,
        'unit' => 'units',
        'quantity' => 15,
        'reorder_level' => 3,
        'unit_cost' => 3200.00,
        'location' => 'Bay E',
    ]);

    $response->assertRedirect(route('admin.inventory'));
    $this->assertDatabaseHas('inventory_items', [
        'item_code' => 'CAM-001',
        'category' => 'Safety & Surveillance',
    ]);
});

test('admin can create inventory item with optional location and without initial quantity or cost', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.inventory.store'), [
        'item_code' => 'TEST-OPT-01',
        'name' => 'Multi-Angle Bracket',
        'description' => 'Industrial steel bracket',
        'category' => 'Hardware & Tools',
        'unit' => 'pieces',
    ]);

    $response->assertRedirect(route('admin.inventory'));
    $this->assertDatabaseHas('inventory_items', [
        'item_code' => 'TEST-OPT-01',
        'quantity' => 0,
        'unit_cost' => 0.00,
        'location' => null,
        'status' => 'Out of Stock',
    ]);
});

test('add item modal category field uses searchable dropdown component with search input', function () {
    $admin = User::factory()->create([
        'account_level' => AccountLevel::Admin,
    ]);

    Category::create(['name' => 'IT Peripherals']);

    $response = $this->actingAs($admin)->get(route('admin.inventory'));

    $response->assertOk();
    $response->assertSee('pgso-dropdown-search-wrapper', false);
    $response->assertSee('pgso-ds-search-input', false);
    $response->assertSee('id="ds_container_newItemCategory_search"', false);
    $response->assertSee('Search category...', false);
    $response->assertSee('IT Peripherals');
});
