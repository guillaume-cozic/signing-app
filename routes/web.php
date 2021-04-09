<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard',  [\App\Http\Controllers\Signing\BoatTripController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/profile', [\App\Http\Controllers\UserController::class, 'profile'])
    ->middleware(['auth'])
    ->name('user.profile');

Route::post('/profile', [\App\Http\Controllers\UserController::class, 'update'])
    ->middleware(['auth'])
    ->name('user.profile.save');

Route::get('/fleets', [\App\Http\Controllers\Signing\FleetController::class, 'listShips'])
    ->middleware(['auth'])
    ->name('fleet.list');

Route::post('/fleet', [\App\Http\Controllers\Signing\FleetController::class, 'add'])
    ->middleware(['auth'])
    ->name('fleet.add');

Route::post('/fleets', [\App\Http\Controllers\Signing\FleetController::class, 'getFleetList'])
    ->middleware(['auth'])
    ->name('fleet.list.data');

Route::get('{fleetId}/fleet', [\App\Http\Controllers\Signing\FleetController::class, 'showEdit'])
    ->middleware(['auth'])
    ->name('page.fleet.edit');

Route::post('{fleetId}/fleet', [\App\Http\Controllers\Signing\FleetController::class, 'edit'])
    ->middleware(['auth'])
    ->name('fleet.edit');

Route::post('fleets/disable', [\App\Http\Controllers\Signing\FleetController::class, 'disable'])
    ->middleware(['auth'])
    ->name('fleet.disable');

Route::post('fleets/enable', [\App\Http\Controllers\Signing\FleetController::class, 'enable'])
    ->middleware(['auth'])
    ->name('fleet.enable');

Route::post('/boat-trips/list', [\App\Http\Controllers\Signing\BoatTripController::class, 'search'])
    ->middleware(['auth'])
    ->name('boat-trips.list.data');

Route::post('/boat-trips', [\App\Http\Controllers\Signing\BoatTripController::class, 'add'])
    ->middleware(['auth'])
    ->name('boat-trips.add');

Route::post('{boatTripId}/boat-trip/cancel', [\App\Http\Controllers\Signing\BoatTripController::class, 'cancel'])
    ->middleware(['auth'])
    ->name('boat-trip.cancel');

Route::post('{boatTripId}/boat-trip/end', [\App\Http\Controllers\Signing\BoatTripController::class, 'end'])
    ->middleware(['auth'])
    ->name('boat-trip.end');

Route::post('modal/boat-trips', [\App\Http\Controllers\Signing\BoatTripController::class, 'serveHtmlModal'])
    ->middleware(['auth'])
    ->name('boat-trips.modal');


Route::get('reporting', [\App\Http\Controllers\Reporting\ReportingController::class, 'showReporting'])
    ->middleware(['auth'])
    ->name('reporting.show');

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
