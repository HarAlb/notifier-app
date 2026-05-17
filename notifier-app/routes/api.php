<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Src\Infrastructure\Http\Controllers\NotificationBatchController;

Route::prefix('notifications')->group(function () {
    Route::post('', [NotificationBatchController::class, 'store']);
});
