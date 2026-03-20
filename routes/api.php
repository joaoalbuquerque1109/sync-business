<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\ExcelImportController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProposalController;
use App\Http\Controllers\Api\V1\ProposalExportController;
use App\Http\Controllers\Api\V1\ProposalTemplateController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::prefix('auth')->group(function (): void {
        Route::post('login', [AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->group(function (): void {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
        });
    });

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::apiResource('users', UserController::class);
        Route::patch('users/{user}/status', [UserController::class, 'updateStatus']);
        Route::patch('users/{user}/password', [UserController::class, 'updatePassword']);

        Route::get('roles', [RoleController::class, 'index']);
        Route::get('permissions', [RoleController::class, 'permissions']);

        Route::apiResource('clients', ClientController::class);
        Route::apiResource('products', ProductController::class);
        Route::apiResource('proposals', ProposalController::class);
        Route::patch('proposals/{proposal}/status', [ProposalController::class, 'updateStatus']);
        Route::get('proposals/{proposal}/versions', [ProposalController::class, 'versions']);
        Route::post('proposals/{proposal}/versions', [ProposalController::class, 'storeVersion']);
        Route::apiResource('proposal-templates', ProposalTemplateController::class);

        Route::prefix('import/excel')->group(function (): void {
            Route::post('clients', [ExcelImportController::class, 'clients']);
            Route::post('products', [ExcelImportController::class, 'products']);
            Route::post('proposal-template', [ExcelImportController::class, 'proposalTemplate']);
            Route::post('proposals/prefill', [ExcelImportController::class, 'proposalPrefill']);
        });

        Route::get('export/proposals/{proposal}/excel', [ProposalExportController::class, 'excel']);
        Route::get('dashboard/summary', [DashboardController::class, 'summary']);
    });
});
