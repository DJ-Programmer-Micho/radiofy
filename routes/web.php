<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// General/Guest Route
Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin-101')->middleware(['auth:admin'])->group(function () {
    // Admin dashboard and related routes
});

// Subscriber Routes
Route::prefix('reg-201')->middleware(['auth:subscriber'])->group(function () {
    // Subscriber dashboard and related routes
});

// Listener Routes
Route::prefix('listener')->middleware(['auth:listener'])->group(function () {
    // Listener interface routes
});

