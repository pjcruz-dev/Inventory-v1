<?php

namespace App\Services;

use App\Models\AuditTrail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Log a create action for a model.
     *
     * @param Model $model
     * @param string|null $note
     * @return AuditTrail
     */
    public function logCreated(Model $model, ?string $note = null): AuditTrail
    {
        return $this->log($model, 'CREATED', null, $note);
    }

    /**
     * Log an update action for a model.
     *
     * @param Model $model
     * @param array $oldAttributes
     * @param string|null $note
     * @return AuditTrail
     */
    public function logUpdated(Model $model, array $oldAttributes, ?string $note = null): AuditTrail
    {
        $changes = [
            'old' => $oldAttributes,
            'new' => $model->getAttributes(),
        ];

        return $this->log($model, 'UPDATED', $changes, $note);
    }

    /**
     * Log a delete action for a model.
     *
     * @param Model $model
     * @param string|null $note
     * @return AuditTrail
     */
    public function logDeleted(Model $model, ?string $note = null): AuditTrail
    {
        return $this->log($model, 'DELETED', null, $note);
    }

    /**
     * Log a transfer action for an asset.
     *
     * @param Model $model
     * @param array $transferDetails
     * @param string|null $note
     * @return AuditTrail
     */
    public function logTransferred(Model $model, array $transferDetails, ?string $note = null): AuditTrail
    {
        return $this->log($model, 'TRANSFERRED', $transferDetails, $note);
    }

    /**
     * Log a print action for an asset.
     *
     * @param Model $model
     * @param array $printDetails
     * @param string|null $note
     * @return AuditTrail
     */
    public function logPrinted(Model $model, array $printDetails, ?string $note = null): AuditTrail
    {
        return $this->log($model, 'PRINTED', $printDetails, $note);
    }

    /**
     * Log an action for a model.
     *
     * @param Model $model
     * @param string $action
     * @param array|null $changes
     * @param string|null $note
     * @return AuditTrail
     */
    public function log(Model $model, string $action, ?array $changes = null, ?string $note = null): AuditTrail
    {
        $entityType = class_basename($model);
        $entityId = $model->id;
        $performedBy = Auth::id();

        return AuditTrail::log(
            $entityType,
            $entityId,
            $action,
            $performedBy,
            $changes,
            $note
        );
    }
}