<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

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
     * Get the validation rules for the asset.
     */
    public static function validationRules($id = null): array
    {
        return [
            'asset_tag' => [
                'required',
                'string',
                'max:64',
                Rule::unique('assets')->ignore($id),
            ],
            'serial_no' => 'nullable|string|max:128',
            'asset_type_id' => 'required|exists:asset_types,id',
            'model' => 'nullable|string|max:200',
            'manufacturer' => 'nullable|string|max:200',
            'purchase_date' => 'nullable|date|before_or_equal:today',
            'warranty_until' => 'nullable|date|after_or_equal:purchase_date',
            'cost' => 'nullable|numeric|min:0|max:999999.99',
            'status' => 'required|string|in:available,assigned,in_repair,disposed',
            'location' => 'nullable|string|max:200',
            'assigned_to_user_id' => 'nullable|exists:users,id',
        ];
    }

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