<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'location',
        'about_me',
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
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique('users')->ignore($id),
            ],
            'password' => $id ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'about_me' => 'nullable|string|max:1000',
        ];
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
     * Get the location that the user belongs to.
     */
    public function locationRelation()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
