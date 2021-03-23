<?php


namespace App\Http\Controllers\Signing;


use App\Http\Requests\Domain\Fleet\AddFleetRequest;
use App\Signing\Signing\Domain\UseCases\AddFleet;
use App\Signing\Signing\Domain\UseCases\GetFleetsList;
use Tests\TestCase;

class FleetController extends TestCase
{
    public function listShips(GetFleetsList $getFleetsList)
    {
        $fleets = $getFleetsList->execute();
        return view('signing.fleet.list', [
            'fleets' => $fleets
        ]);
    }

    public function add(AddFleetRequest $request, AddFleet $addFleet)
    {
        $name = $request->input('name', null);
        $total = $request->input('total_available', null);
        $state = $request->input('state', false);
        $addFleet->execute($name, '', $total, $state);
        return redirect()->route('fleet.list');
    }
}
