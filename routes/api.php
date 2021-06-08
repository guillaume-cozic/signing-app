<?php

use Illuminate\Support\Facades\Route;

Route::get('/v1/fleet/rent', [\App\Http\Controllers\Api\V1\Fleet\RentController::class, 'showRent']);
Route::get('/v1/fleet/rents', [\App\Http\Controllers\Api\V1\Fleet\RentController::class, 'showRents']);
