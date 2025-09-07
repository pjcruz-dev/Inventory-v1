<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditTrail extends Model
{
    use HasFactory;

    protected $table = 'audit_trail';

    protected $fillable = [
        'entity_type',
        'entity_id',
        'action',
        'performed_by',
        'performed_at',
        'changes',
        'note',
    ];

    protected $casts = [
        'changes' => 'array',
        'performed_at' => 'datetime',
    ];

    /**
     * Get the user that performed the action.
     */
    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    /**
     * Create a new audit trail record.
     *
     * @param string $entityType
     * @param int $entityId
     * @param string $action
     * @param int|null $performedBy
     * @param array|null $changes
     * @param string|null $note
     * @return AuditTrail
     */
    public static function log(
        string $entityType,
        int $entityId,
        string $action,
        ?int $performedBy = null,
        ?array $changes = null,
        ?string $note = null
    ): AuditTrail {
        return self::create([
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'action' => $action,
            'performed_by' => $performedBy,
            'changes' => $changes,
            'note' => $note,
        ]);
    }
}