<?php

namespace App\Traits;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Builder;

trait HasBranch
{
    /**
     * Boot the trait to add global scope
     */
    protected static function bootHasBranch()
    {
        static::addGlobalScope('branch', function (Builder $builder) {
            if (app()->runningInConsole()) {
                return;
            }

            // IMPORTANT: If we don't have a resolved user yet, 
            // calling auth()->user() or auth()->check() will trigger a User query.
            // If that User query triggers this scope again, we get infinite recursion.
            if (!auth()->hasUser()) {
                // If it's the User model being queried and no user is resolved, 
                // it's likely a login/auth attempt. We must NOT scope this.
                if (static::class === \App\Models\User::class) {
                    return;
                }

                // For other models, check if there's even a session to avoid triggering auth
                if (!auth()->check()) {
                    return;
                }
            }

            $user = auth()->user();
            if (!$user) {
                return;
            }

            $branchId = $user->branch_id ?? session('active_branch_id');
            $builder->where($builder->getQuery()->from . '.branch_id', $branchId);
        });

        static::creating(function ($model) {
            if (auth()->check()) {
                $user = auth()->user();
                // Automatically assign branch_id from user if not set
                if (!$model->branch_id && $user->branch_id) {
                    $model->branch_id = $user->branch_id;
                }
                // Or from session if owner is creating for a specific branch
                elseif (!$model->branch_id && session()->has('active_branch_id')) {
                    $model->branch_id = session('active_branch_id');
                }
            }
        });
    }

    /**
     * Get the branch that owns the model.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
