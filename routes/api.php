<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;



Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('/tasks', TaskController::class)->except(['create', 'edit']);
    Route::post('/tasks/{task}/assign', [TaskController::class, 'assignTask']);
    Route::get('/me', [AuthController::class, 'me']);
});
