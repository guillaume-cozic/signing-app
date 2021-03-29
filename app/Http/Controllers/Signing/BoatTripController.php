<?php


namespace App\Http\Controllers\Signing;


use App\Http\Controllers\Controller;
use App\Signing\Signing\Domain\UseCases\AddBoatTrip;
use App\Signing\Signing\Domain\UseCases\GetBoatTripsList;
use App\Signing\Signing\Domain\UseCases\GetFleetsList;
use Carbon\Carbon;
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
        $search = $request->input('search.value', '');
        $perPage = $request->input('length', 10);
        $sortDir = $request->input('order.0.dir', null);
        $sortIndex = $request->input('order.0.column', null);
        $sort = $request->input('columns.'.$sortIndex.'.name', null);
        $boatTrips = $getBoatTripsList->execute($search, $start, $perPage, $sort, $sortDir);

        foreach ($boatTrips as $boatTrip) {
            $boats = '';
            foreach($boatTrip->boats as $boat => $qty){
                $boats .= $qty. ' '.$boat.'</br>';
            }
            $boats = $boats !== '' ? $boats : 'Matériel perso';

            $shouldEndAt = $boatTrip->startAt->add(\DateInterval::createFromDateString('+'.$boatTrip->hours.' hours'));
            if($shouldEndAt < new Carbon(new \DateTime())) {

                $state = 'success';
                if(abs($shouldEndAt->getTimestamp() - (new \DateTime())->getTimestamp()) > 30*60){
                    $state = 'danger';
                }

                $percentageCompletion = 100;
            }else {
                $diff = $shouldEndAt->diff(new Carbon(new \DateTime()));
                $h = $diff->h + round($diff->i / 60, 1);

                $percentageCompletion = ($boatTrip->hours - $h) / $boatTrip->hours * 100;
                $state = 'info';
            }
            $boatTripsData[] = [
                $boats,
                $boatTrip->name,
                '<div class="progress progress-xs progress-striped active">
                    <div class="progress-bar bg-'.$state.'" style="width: '.$percentageCompletion.'%"></div>
                </div><br/><i class="fas fa-clock"></i> '.$shouldEndAt->format('H:i'),
                '<i class="fa fa-hourglass-start text-green p-2"></i><i class="fa fa-clock text-blue p-2"></i><i class="fa fa-trash text-red p-2"></i>'
            ];
        }

        return [
            'draw' => $request->get('draw'),
            'recordsTotal' => count($boatTrips),
            'recordsFiltered' => $boatTrips->total(),
            'data' => $boatTripsData ?? [],
        ];
    }

    public function serveHtmlModal(Request $request, GetFleetsList $getFleetsList)
    {
        $count = $request->input('count');
        $fleets = $getFleetsList->execute();
        return view('modal.partials.add-boat-trip-form', [
            'fleets' => $fleets,
            'count' => $count
        ]);
    }

    public function add(Request $request, AddBoatTrip $addBoatTrip)
    {
        $boats = $request->input('boats');
        $name = $request->input('name');
        $hours = $request->input('hours', 1);
        $startAt = $request->input('start_at');

        $boatsProcessed = [];
        foreach($boats as $boat){
            $boatsProcessed[$boat['id']] = isset($boatsProcessed[$boat['id']]) ? $boatsProcessed[$boat['id']] + $boat['number'] : $boat['number'];
        }
        $addBoatTrip->execute($boatsProcessed, $name, $hours);
        return [];
    }
}
