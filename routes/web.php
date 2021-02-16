<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/profile', [\App\Http\Controllers\UserController::class, 'profile'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/ships', [\App\Http\Controllers\Ships\ShipsController::class, 'listShips'])
    ->middleware(['auth'])
    ->name('ships.list');

require __DIR__.'/auth.php';
