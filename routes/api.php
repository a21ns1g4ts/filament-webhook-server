<?php

use Illuminate\Support\Facades\Route;
use Marjose123\FilamentWebhookServer\Http\Controllers\WebhookController;

Route::prefix('/webhooks')->group(function () {
    Route::post('/', [WebhookController::class, 'create']);
});
