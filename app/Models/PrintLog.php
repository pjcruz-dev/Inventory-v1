<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrintLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'printed_by',
        'printed_at',
        'print_format',
        'copies',
        'destination_printer',
        'note',
    ];

    protected $casts = [
        'printed_at' => 'datetime',
        'copies' => 'integer',
    ];

    /**
     * Get the asset that was printed.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Get the user that printed the asset.
     */
    public function printedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'printed_by');
    }
}