<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manufacturer extends Model
{
    protected $fillable = [
        'name',
        'description',
        'website',
        'contact_email',
        'contact_phone',
        'address',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the assets for the manufacturer.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * Scope a query to only include active manufacturers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
