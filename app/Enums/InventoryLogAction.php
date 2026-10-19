<?php

namespace App\Enums;

enum InventoryLogAction: string
{
    case Transfer = 'transfer';
    case Release = 'release';
    case ItemCreated = 'item_created';
    case ItemUpdated = 'item_updated';
    case ItemDeleted = 'item_deleted';
    case VariantCreated = 'variant_created';
    case VariantUpdated = 'variant_updated';
    case VariantDeleted = 'variant_deleted';
    case InventoryAdded = 'inventory_added';
    case StockAdjusted = 'stock_adjusted';
    case StockReturned = 'stock_returned';

    /**
     * Get a human-readable label for the log action.
     */
    public function label(): string
    {
        return match ($this) {
            self::Transfer => 'Stock Transfer',
            self::Release => 'Stock Release',
            self::ItemCreated => 'Item Created',
            self::ItemUpdated => 'Item Updated',
            self::ItemDeleted => 'Item Deleted',
            self::VariantCreated => 'Variant Created',
            self::VariantUpdated => 'Variant Updated',
            self::VariantDeleted => 'Variant Deleted',
            self::InventoryAdded => 'Inventory Added',
            self::StockAdjusted => 'Stock Adjusted',
            self::StockReturned => 'Stock Returned',
        };
    }

    /**
     * Get badge color styling class.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::Transfer => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
            self::Release => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            self::ItemCreated, self::VariantCreated, self::InventoryAdded => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
            self::ItemUpdated, self::VariantUpdated => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
            self::ItemDeleted, self::VariantDeleted => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
            self::StockAdjusted => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
            self::StockReturned => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
        };
    }
}
