<?php

use App\Enums\AccountLevel;
use App\Models\Category;
use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use App\Models\Office;
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
    $this->assertDatabaseHas('inventory_item_variants', [
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
    $this->assertDatabaseHas('inventory_item_variants', [
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

test('inventory item has categoryModel relationship and pr catalog loads categories from category table', function () {
    $category = Category::firstOrCreate(['name' => 'Medical Logistics']);

    $item = InventoryItem::factory()->create([
        'category' => 'Medical Logistics',
    ]);

    expect($item->categoryModel)->not->toBeNull()
        ->and($item->categoryModel->id)->toBe($category->id)
        ->and($item->categoryModel->name)->toBe('Medical Logistics');

    $office = Office::create([
        'office_name' => 'PGSO Office',
        'office_code' => 'PGSO-001',
    ]);

    $staff = User::factory()->staff()->forOffice($office)->create();

    $response = $this->actingAs($staff)->get(route('staff.requisition.pr'));

    $response->assertOk();
    $response->assertSee('Medical Logistics');
});

test('variant has categoryModel relationship and category has variants relationship', function () {
    $category = Category::firstOrCreate(['name' => 'IT Equipment']);

    $item = InventoryItem::factory()->create([
        'category' => 'IT Equipment',
    ]);

    $variantWithDirectCategory = InventoryItemVariant::factory()->create([
        'inventory_item_id' => $item->id,
        'category' => 'IT Equipment',
    ]);

    $variantWithInheritedCategory = InventoryItemVariant::factory()->create([
        'inventory_item_id' => $item->id,
        'category' => null,
    ]);

    expect($variantWithDirectCategory->categoryModel)->not->toBeNull()
        ->and($variantWithDirectCategory->categoryModel->id)->toBe($category->id)
        ->and($variantWithDirectCategory->effective_category)->toBe('IT Equipment')
        ->and($variantWithInheritedCategory->effective_category)->toBe('IT Equipment')
        ->and($category->variants->pluck('id'))->toContain($variantWithDirectCategory->id);
});
