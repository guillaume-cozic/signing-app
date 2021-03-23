<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard',  [\App\Http\Controllers\Signing\BoatTripController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/profile', [\App\Http\Controllers\UserController::class, 'profile'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/ships', [\App\Http\Controllers\Signing\FleetController::class, 'listShips'])
    ->middleware(['auth'])
    ->name('fleet.list');

Route::post('/ships', [\App\Http\Controllers\Signing\FleetController::class, 'add'])
    ->middleware(['auth'])
    ->name('fleet.add');

Route::post('/boat-trips', [\App\Http\Controllers\Signing\BoatTripController::class, 'search'])
    ->middleware(['auth'])
    ->name('boat-trips.list.data');


require __DIR__.'/auth.php';


Route::group(['prefix' => 'teams', 'namespace' => 'Teamwork'], function()
{
    Route::get('/', [App\Http\Controllers\Teamwork\TeamController::class, 'index'])->name('teams.index');
    Route::get('create', [App\Http\Controllers\Teamwork\TeamController::class, 'create'])->name('teams.create');
    Route::post('teams', [App\Http\Controllers\Teamwork\TeamController::class, 'store'])->name('teams.store');
    Route::get('edit/{id}', [App\Http\Controllers\Teamwork\TeamController::class, 'edit'])->name('teams.edit');
    Route::put('edit/{id}', [App\Http\Controllers\Teamwork\TeamController::class, 'update'])->name('teams.update');
    Route::delete('destroy/{id}', [App\Http\Controllers\Teamwork\TeamController::class, 'destroy'])->name('teams.destroy');
    Route::get('switch/{id}', [App\Http\Controllers\Teamwork\TeamController::class, 'switchTeam'])->name('teams.switch');

    Route::get('members/{id}', [App\Http\Controllers\Teamwork\TeamMemberController::class, 'show'])->name('teams.members.show');
    Route::get('members/resend/{invite_id}', [App\Http\Controllers\Teamwork\TeamMemberController::class, 'resendInvite'])->name('teams.members.resend_invite');
    Route::post('members/{id}', [App\Http\Controllers\Teamwork\TeamMemberController::class, 'invite'])->name('teams.members.invite');
    Route::delete('members/{id}/{user_id}', [App\Http\Controllers\Teamwork\TeamMemberController::class, 'destroy'])->name('teams.members.destroy');

    Route::get('accept/{token}', [App\Http\Controllers\Teamwork\AuthController::class, 'acceptInvite'])->name('teams.accept_invite');
});
