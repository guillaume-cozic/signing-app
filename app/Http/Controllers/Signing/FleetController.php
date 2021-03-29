<?php


namespace App\Http\Controllers\Signing;


use App\Http\Requests\Domain\Fleet\AddFleetRequest;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\UseCases\AddFleet;
use App\Signing\Signing\Domain\UseCases\DisableFleet;
use App\Signing\Signing\Domain\UseCases\EnableFleet;
use App\Signing\Signing\Domain\UseCases\GetFleetsList;
use App\Signing\Signing\Domain\UseCases\Query\GetFleet;
use App\Signing\Signing\Domain\UseCases\UpdateFleet;
use Illuminate\Http\Request;
use Tests\TestCase;

class FleetController extends TestCase
{
    public function listShips()
    {
        return view('signing.fleet.list');
    }

    public function getFleetList(Request $request, GetFleetsList $getFleetsList)
    {
        $start = $request->input('start', 0);
        $search = $request->input('search.value', '');
        $perPage = $request->input('length', 10);
        $sortDir = $request->input('order.0.dir', null);
        $sortIndex = $request->input('order.0.column', null);
        $sort = $request->input('columns.'.$sortIndex.'.name', null);

        $fleets = $getFleetsList->execute($search, $start, $perPage, $sort, $sortDir);

        foreach ($fleets as $fleet) {
            $fleetsData[] = [
                $fleet->id,
                $fleet->name,
                $fleet->totalAvailable,
                $fleet->state,
                '',
                route('page.fleet.edit', ['fleetId' => $fleet->id])
            ];
        }

        return [
            'draw' => $request->get('draw'),
            'recordsTotal' => count($fleets),
            'recordsFiltered' => $fleets->total(),
            'data' => $fleetsData ?? [],
        ];
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

    public function showEdit(string $fleetId, GetFleet $getFleet)
    {
        $fleet = $getFleet->execute($fleetId);
        return view('signing.fleet.edit', [
            'fleet' => $fleet
        ]);
    }

    public function edit(string $fleetId, Request $request, UpdateFleet $updateFleet)
    {
        $name = $request->input('name', '');
        $total = $request->input('total_available', 0);
        $state = $request->input('state');
        $state = $state === 'on' ? Fleet::STATE_ACTIVE : Fleet::STATE_INACTIVE;
        $updateFleet->execute($fleetId, $total, $name, $state);
        return redirect()->route('fleet.list');
    }

    public function disable(Request $request, DisableFleet $disableFleet)
    {
        $disableFleet->execute($request->input('fleet_id'));
        return [];
    }

    public function enable(Request $request, EnableFleet $enableFleet)
    {
        $enableFleet->execute($request->input('fleet_id'));
        return [];
    }
}
