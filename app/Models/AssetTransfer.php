<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'from_location',
        'to_location',
        'from_user_id',
        'to_user_id',
        'transfer_reason',
        'transfer_date',
        'processed_by',
        'status',
    ];

    protected $casts = [
        'transfer_date' => 'datetime',
    ];

    /**
     * Get the asset that was transferred.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Get the user that the asset was transferred from.
     */
    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Get the user that the asset was transferred to.
     */
    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    /**
     * Get the user that processed the transfer.
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}