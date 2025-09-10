<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'employee_no',
        'first_name',
        'last_name',
        'department',
        'position',
        'email',
        'password',
        'role_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the validation rules for the user.
     */
    public static function validationRules($id = null): array
    {
        $rules = [
            'employee_no' => [
                'required',
                'string',
                'max:50',
                \Illuminate\Validation\Rule::unique('users')->ignore($id),
            ],
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique('users')->ignore($id),
            ],
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:Active,Inactive,Resigned',
        ];

        // Only add password validation if this is a new user
        if (!$id) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $rules;
    }

    /**
     * Get the assets assigned to this user.
     */
    public function assignedAssets()
    {
        return $this->hasMany(Asset::class, 'assigned_to_user_id');
    }

    /**
     * Get the assets created by this user.
     */
    public function createdAssets()
    {
        return $this->hasMany(Asset::class, 'created_by');
    }

    /**
     * Get the asset transfers from this user.
     */
    public function transfersFrom()
    {
        return $this->hasMany(AssetTransfer::class, 'from_user_id');
    }

    /**
     * Get the asset transfers to this user.
     */
    public function transfersTo()
    {
        return $this->hasMany(AssetTransfer::class, 'to_user_id');
    }

    /**
     * Get the asset transfers processed by this user.
     */
    public function processedTransfers()
    {
        return $this->hasMany(AssetTransfer::class, 'processed_by');
    }

    /**
     * Get the print logs for this user.
     */
    public function printLogs()
    {
        return $this->hasMany(PrintLog::class, 'printed_by');
    }

    /**
     * Get the audit trail records for this user.
     */
    public function auditTrails()
    {
        return $this->hasMany(AuditTrail::class, 'performed_by');
    }

    /**
     * Get the role that owns this user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the logs that belong to this user.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return $this->role && $this->role->hasPermission($permission);
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
