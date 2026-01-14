<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Request;

class UserActivityListener
{
    public function handleLogin(Login $event)
    {
        ActivityLog::create([
            'tenant_id' => $event->user->tenant_id,
            'user_id' => $event->user->id,
            'description' => 'User Logged In',
            'subject_id' => $event->user->id,
            'subject_type' => get_class($event->user),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    public function handleLogout(Logout $event)
    {
        if ($event->user) {
            ActivityLog::create([
                'tenant_id' => $event->user->tenant_id,
                'user_id' => $event->user->id,
                'description' => 'User Logged Out',
                'subject_id' => $event->user->id,
                'subject_type' => get_class($event->user),
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            Login::class,
            [UserActivityListener::class, 'handleLogin']
        );

        $events->listen(
            Logout::class,
            [UserActivityListener::class, 'handleLogout']
        );
    }
}
