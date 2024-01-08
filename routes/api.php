<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('auth/login', 'login');
    Route::post('auth/verify', 'verify');
    Route::post('auth/register','register');
    Route::delete('auth/logout','logout')->middleware(['auth:sanctum']);
});

Route::controller(ContactController::class)->group(function () {
    Route::get('contact/view', 'view')->middleware(['auth:sanctum']);
    Route::post('contact/create', 'create')->middleware(['auth:sanctum']);
    Route::put('contact/update/{contact_id}','update')->middleware(['auth:sanctum']);
    Route::delete('contact/delete/{contact_id}','delete')->middleware(['auth:sanctum']);
});

