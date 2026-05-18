<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Src\Infrastructure\Http\Controllers\NotificationBatchController;
use Src\Infrastructure\Http\Controllers\NotificationMessageController;

Route::prefix('notifications')->group(function () {
    Route::post('', [NotificationBatchController::class, 'store'])->middleware('idempotency');
    Route::get('{id}/messages', [NotificationMessageController::class, 'index']);
});
