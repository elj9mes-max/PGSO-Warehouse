<?php

namespace App\Models;

use Database\Factories\InventoryItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'item_code',
    'name',
    'variant_name',
    'description',
    'variant_description',
    'category',
    'unit',
    'quantity',
    'available_for_transfer',
    'reorder_level',
    'unit_cost',
    'location',
    'status',
    'barcode',
    'sku',
    'is_for_inspection',
    'inventory_item_id',
])]
class InventoryItem extends Model
{
    /** @use HasFactory<InventoryItemFactory> */
    use HasFactory;

    protected $table = 'inventory_item_variants';

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (self $item): void {
            if (empty($item->attributes['variant_name']) && ! empty($item->attributes['name'])) {
                $item->attributes['variant_name'] = $item->attributes['name'];
                unset($item->attributes['name']);
            }
            if (empty($item->attributes['variant_description']) && ! empty($item->attributes['description'])) {
                $item->attributes['variant_description'] = $item->attributes['description'];
                unset($item->attributes['description']);
            }
            if (empty($item->attributes['barcode'])) {
                $item->attributes['barcode'] = 'BC-'.($item->item_code ?? uniqid());
            }
            if (! isset($item->attributes['available_for_transfer'])) {
                $item->attributes['available_for_transfer'] = (int) ($item->quantity ?? 0);
            }
            if (! isset($item->attributes['is_for_inspection'])) {
                $item->attributes['is_for_inspection'] = false;
            }
        });

        static::saving(function (self $item): void {
            if (isset($item->attributes['name'])) {
                $item->attributes['variant_name'] = $item->attributes['name'];
                unset($item->attributes['name']);
            }
            if (isset($item->attributes['description'])) {
                $item->attributes['variant_description'] = $item->attributes['description'];
                unset($item->attributes['description']);
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'available_for_transfer' => 'integer',
            'reorder_level' => 'integer',
            'unit_cost' => 'decimal:2',
            'is_for_inspection' => 'boolean',
        ];
    }

    /**
     * Accessor for name mapped from variant_name.
     */
    public function getNameAttribute(): string
    {
        return $this->attributes['variant_name'] ?? '';
    }

    /**
     * Mutator for name mapped to variant_name.
     */
    public function setNameAttribute($value): void
    {
        $this->attributes['variant_name'] = $value;
        unset($this->attributes['name']);
    }

    /**
     * Accessor for description mapped from variant_description.
     */
    public function getDescriptionAttribute(): ?string
    {
        return $this->attributes['variant_description'] ?? null;
    }

    /**
     * Mutator for description mapped to variant_description.
     */
    public function setDescriptionAttribute($value): void
    {
        $this->attributes['variant_description'] = $value;
        unset($this->attributes['description']);
    }

    /**
     * Get the category record associated with this item.
     */
    public function categoryModel(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category', 'name');
    }

    /**
     * Get the variants associated with this inventory item.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(InventoryItemVariant::class, 'inventory_item_id');
    }

    /**
     * Get the variants that have passed inspection and are ready for inventory.
     */
    public function inspectedVariants(): HasMany
    {
        return $this->variants()->where('is_for_inspection', false);
    }

    /**
     * Get the inventory batches for this item.
     */
    public function batches(): HasMany
    {
        return $this->hasMany(InventoryBatch::class, 'inventory_item_variant_id');
    }

    /**
     * Check if this item has variants.
     */
    public function hasVariants(): bool
    {
        return $this->variants()->exists();
    }

    /**
     * Get total quantity across inspected variants if any exist, otherwise return base quantity.
     */
    public function getTotalStockAttribute(): int
    {
        if ($this->relationLoaded('variants') && $this->variants->isNotEmpty()) {
            $inspected = $this->variants->where('is_for_inspection', false);

            return $inspected->isNotEmpty()
                ? (int) $inspected->sum('quantity')
                : 0;
        }

        if ($this->variants()->exists()) {
            return (int) $this->variants()->where('is_for_inspection', false)->sum('quantity');
        }

        return (int) $this->quantity;
    }

    /**
     * Determine if the item is low on stock.
     */
    public function isLowStock(): bool
    {
        return $this->quantity > 0 && $this->quantity <= $this->reorder_level;
    }

    /**
     * Determine if the item is out of stock.
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity <= 0;
    }

    /**
     * Determine if the item is in stock.
     */
    public function isInStock(): bool
    {
        return $this->quantity > $this->reorder_level;
    }
}
