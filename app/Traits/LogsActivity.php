<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    public static function booted()
    {
        static::created(function (Model $model) {
            static::logActivity($model, 'Created ' . class_basename($model));
        });

        static::updated(function (Model $model) {
            $changes = $model->getChanges();
            // Remove timestamps from changes if they are the only things that changed
            unset($changes['updated_at']);

            if (!empty($changes)) {
                $original = array_intersect_key($model->getOriginal(), $changes);
                static::logActivity($model, 'Updated ' . class_basename($model), [
                    'old' => $original,
                    'new' => $changes,
                ]);
            }
        });

        static::deleted(function (Model $model) {
            static::logActivity($model, 'Deleted ' . class_basename($model));
        });
    }

    protected static function logActivity(Model $model, string $description, array $properties = [])
    {
        $user = Auth::user();

        ActivityLog::create([
            'tenant_id' => $user->tenant_id ?? $model->tenant_id ?? null,
            'user_id' => $user->id ?? null,
            'description' => $description,
            'subject_id' => $model->id,
            'subject_type' => get_class($model),
            'properties' => !empty($properties) ? $properties : null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
