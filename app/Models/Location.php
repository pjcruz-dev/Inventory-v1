<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the validation rules for the location.
     */
    public static function validationRules($id = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('locations')->ignore($id),
            ],
            'address' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get the assets for the location.
     */
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * Get the users for the location.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}