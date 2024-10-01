<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

Route::post('/publish', [NotificationController::class, 'publishMessage']);