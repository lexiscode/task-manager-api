<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/tasks/trashed', [TaskController::class, 'trashed']);

    Route::resource('/tasks', TaskController::class)->except(['create', 'edit']);
    Route::post('/tasks/{task}/assign', [TaskController::class, 'assignTask']);

    Route::post('/tasks/{id}/restore', [TaskController::class, 'restore']);
    Route::delete('/tasks/{id}/force', [TaskController::class, 'forceDelete']);

    Route::post('/import/tasks', [TaskController::class, 'import']);
    Route::get('/export/tasks', [TaskController::class, 'export']);

    Route::get('/me', [AuthController::class, 'me']);
});

Route::fallback(function (Request $request) {
    return response()->json([
        'status' => 'error',
        'message' => 'Route not found. If this should be a valid endpoint, check your URL or method.',
    ], 404);
});
