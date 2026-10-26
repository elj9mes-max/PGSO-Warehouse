<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
])]
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    /**
     * Get the inventory items/variants under this category.
     */
    public function inventoryItems(): HasMany
    {
        return $this->variants();
    }

    /**
     * Get the variants under this category.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(InventoryItemVariant::class, 'category', 'name');
    }
}
