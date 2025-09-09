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
        'asset_category_id',
        'asset_type_id',
        'vendor_id',
        'department_id',
        'project_id',
        'qr_code',
        'operator',
        'model',
        'manufacturer',
        'serial_number',
        'asset_owner',
        'warranty_vendor',
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
     * Get the asset's display name.
     */
    public function getNameAttribute()
    {
        return $this->asset_tag . ($this->model ? ' - ' . $this->model : '');
    }

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
                'regex:/^[A-Za-z0-9\-_]+$/',
                Rule::unique('assets')->ignore($id),
            ],
            'serial_no' => [
                'nullable',
                'string',
                'max:128',
                'regex:/^[A-Za-z0-9\-_]+$/'
            ],
            'asset_category_id' => 'required|exists:asset_categories,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'department_id' => 'nullable|exists:departments,id',
            'project_id' => 'nullable|exists:projects,id',
            'qr_code' => 'required|string|max:255',
            'operator' => 'nullable|string|max:200',
            'model' => 'required|string|max:200',

            'serial_number' => [
                'nullable',
                'string',
                'max:128',
                'regex:/^[A-Za-z0-9\-_]+$/'
            ],
            'asset_owner' => 'required|string|max:200',
            'warranty_vendor' => 'nullable|string|max:200',
            'purchase_date' => [
                'nullable',
                'date',
                'before_or_equal:today'
            ],
            'warranty_until' => [
                'nullable',
                'date',
                'after_or_equal:purchase_date'
            ],
            'cost' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999.99'
            ],
            'status' => 'required|string|in:available,assigned,in_repair,disposed',
            'location' => 'nullable|string|max:200',
            'assigned_to_user_id' => [
                'nullable',
                'exists:users,id',
                'required_if:status,assigned'
            ],
        ];
    }

    /**
     * Get validation messages.
     */
    public static function validationMessages(): array
    {
        return [
            'asset_tag.regex' => 'Asset tag can only contain letters, numbers, hyphens, and underscores.',
            'serial_no.regex' => 'Serial number can only contain letters, numbers, hyphens, and underscores.',
            'serial_number.regex' => 'Serial number can only contain letters, numbers, hyphens, and underscores.',
            'purchase_date.before_or_equal' => 'Purchase date cannot be in the future.',
            'warranty_until.after_or_equal' => 'Warranty expiry must be after or equal to the purchase date.',
            'assigned_to_user_id.required_if' => 'Assigned to field is required when status is assigned.',
            'cost.max' => 'Cost cannot exceed 999,999.99.'
        ];
    }

    /**
     * Custom validation for business rules.
     */
    public function validateBusinessRules(): array
    {
        $errors = [];

        // Check if asset can be assigned
        if ($this->status === 'assigned' && !$this->assigned_to_user_id) {
            $errors['assigned_to_user_id'] = 'Asset cannot be assigned without specifying a user.';
        }

        // Check if asset can be unassigned
        if ($this->status !== 'assigned' && $this->assigned_to_user_id) {
            $errors['status'] = 'Asset cannot have an assigned user unless status is "assigned".';
        }

        // Check warranty expiry logic
        if ($this->warranty_until && $this->purchase_date && $this->warranty_until <= $this->purchase_date) {
            $errors['warranty_until'] = 'Warranty expiry must be after purchase date.';
        }

        // Check if disposed asset can be modified
        if ($this->status === 'disposed' && $this->isDirty() && !$this->isDirty('notes')) {
            $errors['status'] = 'Disposed assets can only have their notes updated.';
        }

        return $errors;
    }

    /**
     * Check if asset is available for assignment.
     */
    public function isAvailableForAssignment(): bool
    {
        return in_array($this->status, ['available']);
    }

    /**
     * Check if asset can be disposed.
     */
    public function canBeDisposed(): bool
    {
        return !in_array($this->status, ['disposed']) && $this->peripherals()->count() === 0;
    }

    /**
     * Check if warranty is still valid.
     */
    public function isUnderWarranty(): bool
    {
        return $this->warranty_until && $this->warranty_until > now();
    }

    /**
     * Get the asset category that owns the asset.
     */
    public function assetCategory(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class);
    }

    /**
     * Get the asset type that owns the asset.
     */
    public function assetType(): BelongsTo
    {
        return $this->belongsTo(AssetType::class);
    }

    /**
     * Get the vendor that owns the asset.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the department that owns the asset.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the project that owns the asset.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
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
     * Get the logs for this asset.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }
}