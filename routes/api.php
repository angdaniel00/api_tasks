<?php

use Illuminate\Support\Facades\Route;

Route::post('/create-token', [\App\Http\Controllers\AuthController::class, 'login']);
Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware(['auth:sanctum']);
Route::get('/user', [\App\Http\Controllers\AuthController::class, 'user'])->middleware(['auth:sanctum', 'abilities:check-status,place-orders']);

Route::apiResource('keywords', \App\Http\Controllers\KeywordController::class)->middleware(['auth:sanctum']);

Route::apiResource('users', \App\Http\Controllers\UserController::class)->middleware(['auth:sanctum']);
Route::put('change-password/users', [App\Http\Controllers\UserController::class, 'changePassword'])->middleware(['auth:sanctum']);

Route::apiResource('tasks', \App\Http\Controllers\TaskController::class)->middleware(['auth:sanctum']);
Route::post('add-keywords/tasks', [\App\Http\Controllers\TaskController::class, 'addKeyword'])->middleware(['auth:sanctum']);
Route::put('delete-keywords/tasks', [\App\Http\Controllers\TaskController::class, 'deleteKeyword'])->middleware(['auth:sanctum']);

Route::apiResource('teams', \App\Http\Controllers\TeamController::class)->middleware(['auth:sanctum']);
Route::post('add-user/teams', [\App\Http\Controllers\TeamController::class, 'addUser'])->middleware(['auth:sanctum']);
Route::put('delete-user/teams', [\App\Http\Controllers\TeamController::class, 'deleteUser'])->middleware(['auth:sanctum']);
