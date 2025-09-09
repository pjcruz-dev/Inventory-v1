<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the assets for this category.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * Get the logs for this category.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(Log::class, 'asset_category_id');
    }
}
