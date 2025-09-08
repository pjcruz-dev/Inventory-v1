<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

class AssetType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the validation rules for the asset type.
     */
    public static function validationRules($id = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('asset_types')->ignore($id),
            ],
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get the assets for this asset type.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}