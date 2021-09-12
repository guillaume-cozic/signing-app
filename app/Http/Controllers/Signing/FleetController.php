<?php


namespace App\Http\Controllers\Signing;


use App\Http\Requests\Domain\Fleet\AddFleetRequest;
use App\Http\Requests\Domain\Fleet\EditFleetRequest;
use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
use App\Signing\Signing\Domain\Exceptions\FleetAlreadyExist;
use App\Signing\Signing\Domain\UseCases\AddFleet;
use App\Signing\Signing\Domain\UseCases\DisableFleet;
use App\Signing\Signing\Domain\UseCases\EnableFleet;
use App\Signing\Signing\Domain\UseCases\GetFleetsList;
use App\Signing\Signing\Domain\UseCases\Query\GetFleet;
use App\Signing\Signing\Domain\UseCases\Query\GetNumberBoatsOfFleetAvailable;
use App\Signing\Signing\Domain\UseCases\UpdateFleet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FleetController extends Controller
{
    private function getFleetsInit():array
    {
        return [
            'Catamaran' => [
                'Hobie cat 15',
                'Hobie cat T1',
                'Hobie cat 16',
                'Rs cat 14',
                'Rs cat 16',
            ],
            'Dériveur' => [
                'Optimist',
                'Laser',
                'Laser Pico',
                'Fusion'
            ],
            'Planche à voile' => [
                'Planche à voile débutant',
                'funboard'
            ],
            'Kayak et Paddle' => [
                'Kayak simple',
                'Kayak double',
                'Paddle',
                'Pédalo'
            ]
        ];
    }

    public function listShips(GetFleetsList $getFleetsList)
    {
        $fleets = $getFleetsList->execute(['filters' => ['state' => Fleet::STATE_ACTIVE]], 0, 0, 'name');
        $fleetsInit = $this->getFleetsInit();
        return view('signing.fleet.list', [
            'fleetsInit' => $fleetsInit,
            'showModalInit' => count($fleets) === 0
        ]);
    }

    public function getFleetList(Request $request, GetFleetsList $getFleetsList)
    {
        $start = $request->input('start', 0);
        $search = $request->input('search.value', '');
        $perPage = $request->input('length', 10);
        $sortDir = $request->input('order.0.dir', null);
        $sortIndex = $request->input('order.0.column', null);
        $sort = $request->input('columns.'.$sortIndex.'.name', null);

        $fleets = $getFleetsList->execute(['search' => $search], $start, $perPage, $sort, $sortDir);

        foreach ($fleets as $fleet) {
            $fleetsData[] = [
                $fleet->id,
                $fleet->name,
                $fleet->totalAvailable,
                $fleet->state,
                '',
                route('page.fleet.edit', ['fleetId' => $fleet->id]),
                route('fleet.enable'),
                route('fleet.disable')
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
        $name = trim($request->input('name', ''));
        $total = $request->input('total_available', 0);
        $state = $request->input('state');
        $state = $state === 'on' ? Fleet::STATE_ACTIVE : Fleet::STATE_INACTIVE;
        try {
            $addFleet->execute($name, '', $total, $state);
            return redirect()->route('fleet.list');
        }catch (FleetAlreadyExist $e){
            session()->flash('fleet_error',  "Une flotte du même nom existe déjà.");
            return redirect()->route('fleet.list')->withInput($request->all());
        }
    }

    public function showEdit(string $fleetId, GetFleet $getFleet)
    {
        $fleet = $getFleet->execute($fleetId);
        return view('signing.fleet.edit', [
            'fleet' => $fleet
        ]);
    }

    public function edit(string $fleetId, EditFleetRequest $request, UpdateFleet $updateFleet)
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

    public function showAvailabilityBoats(GetNumberBoatsOfFleetAvailable $getNumberBoatsOfFleetAvailable)
    {
        $fleets = $getNumberBoatsOfFleetAvailable->execute();
        return view('dashboard.availability-fleet', [
            'fleets' => $fleets
        ]);
    }

    public function massCreate(Request $request, AddFleet $addFleet)
    {
        $fleets = $request->input('fleets');
        foreach($fleets as $fleet) {
            try {
                $addFleet->execute($fleet, '', 0, Fleet::STATE_ACTIVE);
            }catch (FleetAlreadyExist $e){
                continue;
            }
        }
        return redirect()->back();
    }

}
