<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CodeController;
use App\Models\Code;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/codes', [CodeController::class, 'index']);
Route::post('/codes', [CodeController::class, 'store']);

Route::put('/codes/{id}', [CodeController::class, 'update']);
Route::delete('/codes/{id}', [CodeController::class, 'destroy']);

