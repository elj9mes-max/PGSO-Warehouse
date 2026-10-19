<?php

namespace App\Models;

use Database\Factories\PurchaseRequestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'pr_number',
    'office_id',
    'user_id',
    'status',
    'purpose',
    'responsibility_center_code',
    'requested_by_name',
    'requested_by_designation',
    'approved_by_id',
    'approved_by_name',
    'approved_by_designation',
    'approved_at',
    'total_estimated_cost',
    'remarks',
    'rejection_reason',
])]
class PurchaseRequest extends Model
{
    /** @use HasFactory<PurchaseRequestFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'office_id' => 'integer',
            'user_id' => 'integer',
            'total_estimated_cost' => 'decimal:2',
        ];
    }

    /**
     * Get the office making the purchase request.
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Get the staff user who submitted the purchase request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the line items in this purchase request.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseRequestItem::class, 'purchase_request_id');
    }

    /**
     * Get the user who approved this purchase request.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    /**
     * Scope query to pending requests.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope query to approved requests.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    /**
     * Generate next sequential PR tracking number.
     */
    public static function generatePrNumber(): string
    {
        $prefix = 'PR-'.date('Ym').'-';
        $latest = static::where('pr_number', 'LIKE', $prefix.'%')
            ->orderByDesc('id')
            ->value('pr_number');

        if ($latest) {
            $lastSeq = (int) substr($latest, strlen($prefix));
            $nextSeq = str_pad((string) ($lastSeq + 1), 4, '0', STR_PAD_LEFT);
        } else {
            $nextSeq = '0001';
        }

        return $prefix.$nextSeq;
    }
}
