<?php

namespace App\Models\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

trait RecordsAudit
{
    public static function bootRecordsAudit(): void
    {
        static::created(function (Model $model): void {
            static::logAudit('created', $model, 'Created', null, $model->toArray());
        });

        static::updated(function (Model $model): void {
            $changes = $model->getChanges();
            if (!empty($changes)) {
                $original = array_intersect_key($model->getOriginal(), $changes);
                static::logAudit('updated', $model, 'Updated', $original, $changes);
            }
        });

        static::deleted(function (Model $model): void {
            static::logAudit('deleted', $model, 'Deleted', $model->toArray(), null);
        });
    }

    protected static function logAudit(string $action, Model $model, string $description, ?array $old = null, ?array $new = null): void
    {
        if (!auth()->check()) {
            return;
        }

        AuditLog::create([
            'user_id'        => auth()->id(),
            'action'         => $action,
            'auditable_type' => class_basename($model),
            'auditable_id'   => $model->getKey(),
            'description'    => $description,
            'changes'        => ['old' => $old, 'new' => $new],
        ]);
    }
}
