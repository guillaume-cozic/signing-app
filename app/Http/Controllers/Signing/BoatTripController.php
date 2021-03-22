<?php


namespace App\Http\Controllers\Signing;


use App\Http\Controllers\Controller;
use App\Signing\Signing\Domain\UseCases\AddBoatTrip;
use App\Signing\Signing\Domain\UseCases\GetBoatTripsList;
use App\Signing\Signing\Domain\UseCases\GetFleetsList;
use Illuminate\Http\Request;

class BoatTripController extends Controller
{
    public function index(GetFleetsList $getFleetsList)
    {
        $fleets = $getFleetsList->execute();
        return view('dashboard', [
            'fleets' => $fleets
        ]);
    }

    public function search(Request $request, GetBoatTripsList $getBoatTripsList)
    {
        $start = $request->input('start', 0);
        $perPage = $request->input('length', 10);
        $boatTrips = $getBoatTripsList->execute($start, $perPage);

        foreach ($boatTrips as $boatTrip) {
            $boats = '';
            foreach($boatTrip->boats as $boat => $qty){
                $boats .= $qty. ' '.substr($boat, 0,8).'</br>';
            }
            $boats = $boats !== '' ? $boats : 'Matériel perso';
            $boatTripsData[] = [
                $boats,
                $boatTrip->name,
                $boatTrip->startAt->add(\DateInterval::createFromDateString('+1 hours'))->format('H:i'),
            ];
        }

        return [
            'draw' => $request->get('draw'),
            'recordsTotal' => count($boatTrips),
            'recordsFiltered' => $boatTrips->total(),
            'data' => $boatTripsData ?? [],
        ];
    }

    public function add(Request $request, AddBoatTrip $addBoatTrip)
    {
        $boats = [];
        $name = '';
        $hours = 1;
        $addBoatTrip->execute($boats, $name, $hours);
    }
}
