<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VehicleController;

Route::get('/vehicles', [VehicleController::class, 'index']);
Route::post('/vehicles', [VehicleController::class, 'store']);
Route::get('/vehicles/{id}', [VehicleController::class, 'show']);
Route::put('/vehicles/{id}', [VehicleController::class, 'update']);
Route::delete('/vehicles/{id}', [VehicleController::class, 'destroy']);