<?php

namespace App\Models;

use Database\Factories\OfficeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'office_name',
    'office_code',
])]
class Office extends Model
{
    /** @use HasFactory<OfficeFactory> */
    use HasFactory;

    /**
     * Get the office inventory records for this office.
     */
    public function officeInventories(): HasMany
    {
        return $this->hasMany(OfficeInventory::class, 'office_id');
    }

    /**
     * Get the inventory batches assigned to this office.
     */
    public function batches(): HasMany
    {
        return $this->hasMany(InventoryBatch::class, 'office_id');
    }

    /**
     * Get the inventory item variants assigned to this office.
     */
    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(
            InventoryItemVariant::class,
            'office_inventories',
            'office_id',
            'inventory_item_variants_id'
        )->withPivot('quantity')->withTimestamps();
    }

    /**
     * Get the users assigned to this office.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the requisition requests made by this office.
     */
    public function requisitionRequests(): HasMany
    {
        return $this->hasMany(RequisitionRequest::class);
    }
}
