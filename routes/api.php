<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\MaintenanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MaintenanceRuleController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/vehicles', [VehicleController::class, 'index']);
    Route::post('/vehicles', [VehicleController::class, 'store']);
    Route::get('/vehicles/{id}', [VehicleController::class, 'show']);
    Route::put('/vehicles/{id}', [VehicleController::class, 'update']);
    Route::delete('/vehicles/{id}', [VehicleController::class, 'destroy']);
    Route::get('/vehicles/{id}/maintenance-status', [VehicleController::class, 'maintenanceStatus']);

    Route::get('/maintenances', [MaintenanceController::class, 'index']);
    Route::post('/maintenances', [MaintenanceController::class, 'store']);
    Route::get('/maintenances/{id}', [MaintenanceController::class, 'show']);
    Route::put('/maintenances/{id}', [MaintenanceController::class, 'update']);
    Route::delete('/maintenances/{id}', [MaintenanceController::class, 'destroy']);

    Route::get('/maintenance-rules/active', [MaintenanceRuleController::class, 'active']);

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/maintenance-rules', [MaintenanceRuleController::class, 'index']);
    Route::post('/maintenance-rules', [MaintenanceRuleController::class, 'store']);
    Route::get('/maintenance-rules/{id}', [MaintenanceRuleController::class, 'show']);
    Route::put('/maintenance-rules/{id}', [MaintenanceRuleController::class, 'update']);
    Route::patch('/maintenance-rules/{id}/toggle', [MaintenanceRuleController::class, 'toggle']);
});
