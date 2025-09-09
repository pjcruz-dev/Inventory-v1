<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'website',
        'description',
    ];

    /**
     * Get the assets for this vendor.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * Get the logs for this vendor.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(Log::class, 'vendor_id');
    }
}
