<?php

namespace App\Models;

use Database\Factories\RequisitionRequestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'request_number',
    'request_type',
    'office_id',
    'user_id',
    'status',
    'purpose',
    'division',
    'responsibility_center_code',
    'ris_no',
    'sai_no',
    'air_number',
    'po_number',
    'supplier',
    'requested_by_name',
    'requested_by_designation',
    'approved_by_id',
    'approved_by_name',
    'approved_by_designation',
    'approved_at',
    'issued_by_id',
    'issued_by_name',
    'issued_by_designation',
    'issued_at',
    'received_by_id',
    'received_by_name',
    'received_by_designation',
    'received_at',
    'remarks',
    'rejection_reason',
])]
class RequisitionRequest extends Model
{
    /** @use HasFactory<RequisitionRequestFactory> */
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
            'issued_at' => 'datetime',
            'received_at' => 'datetime',
            'office_id' => 'integer',
            'user_id' => 'integer',
        ];
    }

    /**
     * Get the office making the requisition.
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Get the staff user who submitted the requisition.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the line items requested.
     */
    public function items(): HasMany
    {
        return $this->hasMany(RequisitionRequestItem::class, 'requisition_request_id');
    }

    /**
     * Get the user who approved this request.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    /**
     * Get the user who issued the requested items.
     */
    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by_id');
    }

    /**
     * Get the user who acknowledged receipt.
     */
    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by_id');
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
     * Scope query to issued requests.
     */
    public function scopeIssued(Builder $query): Builder
    {
        return $query->where('status', 'issued');
    }

    /**
     * Scope query to RIS requests.
     */
    public function scopeRis(Builder $query): Builder
    {
        return $query->where('request_type', 'RIS');
    }

    /**
     * Scope query to PR requests.
     */
    public function scopePr(Builder $query): Builder
    {
        return $query->where('request_type', 'PR');
    }

    /**
     * Generate next sequential request tracking number.
     */
    public static function generateRequestNumber(string $type = 'RIS'): string
    {
        $prefix = strtoupper($type).'-'.date('Ym').'-';
        $latest = static::where('request_number', 'LIKE', $prefix.'%')
            ->orderByDesc('id')
            ->value('request_number');

        if ($latest) {
            $lastSeq = (int) substr($latest, strlen($prefix));
            $nextSeq = str_pad((string) ($lastSeq + 1), 4, '0', STR_PAD_LEFT);
        } else {
            $nextSeq = '0001';
        }

        return $prefix.$nextSeq;
    }
}
