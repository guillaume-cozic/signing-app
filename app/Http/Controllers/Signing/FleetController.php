<?php


namespace App\Http\Controllers\Signing;


use App\Http\Requests\Domain\Fleet\AddFleetRequest;
use App\Signing\Signing\Domain\Entities\Fleet;
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
        $name = $request->input('name', '');
        $total = $request->input('total_available', 0);
        $state = $request->input('state');
        $state = $state === 'on' ? Fleet::STATE_ACTIVE : Fleet::STATE_INACTIVE;
        $addFleet->execute($name, '', $total, $state);
        return redirect()->route('fleet.list');
    }
}
