<?php

use Illuminate\Support\Facades\Route;
use Sopamo\Double\Http\Controllers\DoubleApiController;


Route::post('/data', [DoubleApiController::class, 'loadData']);
Route::post('/action', [DoubleApiController::class, 'runAction']);
