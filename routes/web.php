<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reporting\ReportingController;
use App\Http\Controllers\Signing\BoatTripController;
use App\Http\Controllers\Signing\FleetController;
use App\Http\Controllers\Signing\RentalPackageController;
use App\Http\Controllers\Signing\ReservationController;
use App\Http\Controllers\Signing\SailorRentalPackageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/',  [Controller::class, 'home']);
Route::get('/home',  function (){
    return redirect()->intended('/dashboard');
});

Route::get('/dashboard',  [BoatTripController::class, 'index'])
    ->middleware(['auth', 'boat-trips-not-closed'])
    ->name('dashboard');

Route::get('/profile', [UserController::class, 'profile'])
    ->middleware(['auth'])
    ->name('user.profile');

Route::post('/profile', [UserController::class, 'update'])
    ->middleware(['auth'])
    ->name('user.profile.save');

Route::get('/fleets', [FleetController::class, 'listShips'])
    ->middleware(['auth'])
    ->name('fleet.list');

Route::post('/fleet', [FleetController::class, 'add'])
    ->middleware(['auth'])
    ->name('fleet.add');

Route::post('/fleets', [FleetController::class, 'getFleetList'])
    ->middleware(['auth'])
    ->name('fleet.list.data');

Route::get('{fleetId}/fleet', [FleetController::class, 'showEdit'])
    ->middleware(['auth'])
    ->name('page.fleet.edit');

Route::post('{fleetId}/fleet', [FleetController::class, 'edit'])
    ->middleware(['auth'])
    ->name('fleet.edit');

Route::post('fleets/disable', [FleetController::class, 'disable'])
    ->middleware(['auth'])
    ->name('fleet.disable');

Route::post('fleets/enable', [FleetController::class, 'enable'])
    ->middleware(['auth'])
    ->name('fleet.enable');

Route::post('fleets/mass', [FleetController::class, 'massCreate'])
    ->middleware(['auth'])
    ->name('fleets.mass.create');

Route::post('/boat-trips/list', [BoatTripController::class, 'search'])
    ->middleware(['auth'])
    ->name('boat-trips.list.data');

Route::post('/boat-trips/list/ended', [BoatTripController::class, 'endedBoatTripList'])
    ->middleware(['auth'])
    ->name('ended-boat-trips.list.data');

Route::post('/boat-trips', [BoatTripController::class, 'add'])
    ->middleware(['auth'])
    ->name('boat-trips.add');

Route::post('/boat-trips/force', [BoatTripController::class, 'forceAdd'])
    ->middleware(['auth'])
    ->name('boat-trips.force-add');

Route::post('{boatTripId}/boat-trip/cancel', [BoatTripController::class, 'cancel'])
    ->middleware(['auth'])
    ->name('boat-trip.cancel');

Route::post('{boatTripId}/boat-trip/end', [BoatTripController::class, 'end'])
    ->middleware(['auth'])
    ->name('boat-trip.end');

Route::post('{boatTripId}/boat-trip/start', [BoatTripController::class, 'start'])
    ->middleware(['auth'])
    ->name('boat-trip.start');

Route::get('reporting', [ReportingController::class, 'showReporting'])
    ->middleware(['auth'])
    ->name('reporting.show');

Route::get('dashboard/availability', [FleetController::class, 'showAvailabilityBoats'])
    ->middleware(['auth'])
    ->name('dashboard.availability');

Route::get('dashboard/suggestions', [BoatTripController::class, 'getSuggestionsBoatTrip'])
    ->middleware(['auth'])
    ->name('dashboard.suggestions');

Route::get('boat-trips-not-ended', [BoatTripController::class, 'notEnded'])
    ->middleware(['auth'])
    ->name('boat-trips.not-ended');

Route::post('boat-trips-mass-end', [BoatTripController::class, 'massEnd'])
    ->middleware(['auth'])
    ->name('boat-trips.mass-end');

Route::middleware(['auth'])->group(function (){

    Route::prefix('reservation')->group(function (){
        Route::post('', [ReservationController::class, 'add'])->name('reservation.add');
        Route::post('force', [ReservationController::class, 'forceAdd'])->name('reservation.add.force');
        Route::get('', [ReservationController::class, 'reservations'])->name('reservation.list');
    });

    Route::prefix('rental-package')->group(function (){
        Route::get('', [RentalPackageController::class, 'showAddRentalPackage'])->name('rental-package.add');
        Route::post('', [RentalPackageController::class, 'processAddRentalPackage'])->name('rental-package.add.process');
        Route::post('{id}/edit', [RentalPackageController::class, 'processEditRentalPackage'])->name('rental-package.edit.process');
        Route::get('{id}/edit', [RentalPackageController::class, 'showEdit'])->name('rental-package.edit');
    });


    Route::prefix('sailor-rental-packages')->group(function () {
        Route::get('', [SailorRentalPackageController::class, 'index'])->name('sailor-rental-package.index');
        Route::post('', [SailorRentalPackageController::class, 'processAdd'])->name('sailor-rental-package.add');
        Route::post('add', [SailorRentalPackageController::class, 'processAddAjax'])->name('sailor-rental-package.add.ajax');
        Route::post('list', [SailorRentalPackageController::class, 'listSailorRentalPackage'])->name('sailor-rental-package.list');
        Route::post('{id}/add-hours', [SailorRentalPackageController::class, 'addHours'])->name('sailor-rental-package.add-hours');
        Route::post('{id}/decrease-hours', [SailorRentalPackageController::class, 'decreaseHours'])->name('sailor-rental-package.decrease-hours');
        Route::get('{id}/actions', [SailorRentalPackageController::class, 'getActions'])->name('sailor-rental-package.actions');
        Route::get('download-import-file', [SailorRentalPackageController::class, 'downloadImportTemplate'])->name('sailor-rental-package.download-import');
        Route::post('import', [SailorRentalPackageController::class, 'importSailorRentalPackage'])->name('sailor-rental-package.import');
        Route::get('import', [SailorRentalPackageController::class, 'showResultImport'])->name('sailor-rental-package.show-result-import');
    });

    Route::get('sailors', [SailorRentalPackageController::class, 'sailorAutocomplete'])->name('sailor.autocomplete');
    Route::post('modal/boat-trips', [BoatTripController::class, 'serveHtmlModal'])->name('boat-trips.modal');
});

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
    Route::get('redirect', [App\Http\Controllers\Teamwork\TeamMemberController::class, 'redirectToMainTeam'])->name('teams.members.redirect');
    Route::get('members/resend/{invite_id}', [App\Http\Controllers\Teamwork\TeamMemberController::class, 'resendInvite'])->name('teams.members.resend_invite');
    Route::post('members/{id}', [App\Http\Controllers\Teamwork\TeamMemberController::class, 'invite'])->name('teams.members.invite');
    Route::delete('members/{id}/{user_id}', [App\Http\Controllers\Teamwork\TeamMemberController::class, 'destroy'])->name('teams.members.destroy');

    Route::get('accept/{token}', [App\Http\Controllers\Teamwork\AuthController::class, 'acceptInvite'])->name('teams.accept_invite');
});
