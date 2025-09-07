<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peripheral extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'type',
        'details',
        'serial_no',
    ];

    /**
     * Get the asset that owns the peripheral.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}