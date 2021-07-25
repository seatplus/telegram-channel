<?php

use Illuminate\Support\Facades\Route;
use Seatplus\TelegramChannel\Http\Controllers\TelegramAuthController;
use Seatplus\TelegramChannel\Http\Controllers\TelegramNotificationController;

Route::middleware(['web', 'auth'])
    ->group(function () {

        Route::prefix('notifications/telegram')
            ->group(function () {
                Route::get('', [TelegramNotificationController::class, 'index'])->name('telegram.notification.index');
            });

        Route::prefix('/auth/telegram/')
            ->group(function () {
                Route::get('callback', [TelegramAuthController::class, 'handle']);
                Route::get('/infos', [TelegramAuthController::class, 'infos'])->name('telegram.auth.infos');
            });

    });

