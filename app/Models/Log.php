<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    protected $fillable = [
        'category',
        'asset_id',
        'user_id',
        'role_id',
        'permission_id',
        'department_id',
        'project_id',
        'event_type',
        'ip_address',
        'user_agent',
        'remarks'
    ];

    /**
     * Get the asset that owns this log.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Get the user that owns this log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the role that owns this log.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the permission that owns this log.
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * Get the department that owns this log.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the project that owns this log.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
