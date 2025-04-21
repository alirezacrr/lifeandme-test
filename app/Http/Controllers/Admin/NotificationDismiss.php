<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationDismiss extends Controller
{
    public function __invoke(Notification $notification)
    {
        auth()->user()->dismissedNotifications()->attach($notification->id);
        return back();
    }
}
