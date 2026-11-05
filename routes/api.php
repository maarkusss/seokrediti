<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Hoi\IndexController as HoiIndexController;
use App\Http\Controllers\LenderController;
use App\Http\Middleware\HasApiKey;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => [
        HasApiKey::class,
    ]
], function () {
    Route::apiResource('/lenders', LenderController::class)->only(['index', 'update']);

    Route::get('/sessions', [ApiController::class, 'sessions']);
    Route::get('/visitors', [ApiController::class, 'visitors']);
});
// Route::get('/hoi', [HoiIndexController::class, 'callApiFromHoi']);
