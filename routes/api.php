<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('find', [ProjectController::class, 'index']);
    Route::post('find', [ProjectController::class, 'show']);
    Route::delete('find/{project}', [ProjectController::class, 'destroy']);

    Route::post('logout', [AuthController::class, 'logout']);
});
