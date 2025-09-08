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
     * Get the validation rules for the asset transfer.
     */
    public static function validationRules(): array
    {
        return [
            'asset_id' => 'required|exists:assets,id',
            'from_location' => 'nullable|string|max:200',
            'to_location' => 'nullable|string|max:200',
            'from_user_id' => 'nullable|exists:users,id',
            'to_user_id' => 'nullable|exists:users,id',
            'transfer_reason' => 'required|string|max:500',
            'transfer_date' => 'required|date',
            'status' => 'required|string|in:pending,completed,cancelled',
        ];
    }

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