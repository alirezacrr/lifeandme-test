<?php

use App\Http\Controllers\Admin\NotificationDismiss;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::post('/notifications/dismiss/{notification}', NotificationDismiss::class)->name('filament.notifications.dismiss');
