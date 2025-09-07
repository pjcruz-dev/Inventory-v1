<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_tag',
        'serial_no',
        'asset_type_id',
        'model',
        'manufacturer',
        'purchase_date',
        'warranty_until',
        'cost',
        'status',
        'location',
        'assigned_to_user_id',
        'created_by',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_until' => 'date',
        'cost' => 'decimal:2',
    ];

    /**
     * Get the asset type that owns the asset.
     */
    public function assetType(): BelongsTo
    {
        return $this->belongsTo(AssetType::class);
    }

    /**
     * Get the user that the asset is assigned to.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    /**
     * Get the user that created the asset.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the peripherals for this asset.
     */
    public function peripherals(): HasMany
    {
        return $this->hasMany(Peripheral::class);
    }

    /**
     * Get the transfers for this asset.
     */
    public function transfers(): HasMany
    {
        return $this->hasMany(AssetTransfer::class);
    }

    /**
     * Get the print logs for this asset.
     */
    public function printLogs(): HasMany
    {
        return $this->hasMany(PrintLog::class);
    }
}