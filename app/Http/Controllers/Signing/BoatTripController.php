<?php


namespace App\Http\Controllers\Signing;


use App\Http\Controllers\Controller;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\UseCases\AddBoatTrip;
use App\Signing\Signing\Domain\UseCases\BoatTrip\CancelBoatTrip;
use App\Signing\Signing\Domain\UseCases\BoatTrip\ForceAddBoatTrip;
use App\Signing\Signing\Domain\UseCases\BoatTrip\StartBoatTrip;
use App\Signing\Signing\Domain\UseCases\EndBoatTrip;
use App\Signing\Signing\Domain\UseCases\GetBoatTripsList;
use App\Signing\Signing\Domain\UseCases\GetFleetsList;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BoatTripController extends Controller
{
    public function index(GetFleetsList $getFleetsList)
    {
        $fleets = $getFleetsList->execute(['filters' => ['state' => Fleet::STATE_ACTIVE]]);
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
            $total = 0;
            foreach($boatTrip->boats as $boat => $qty){
                $boats .= $qty. ' '.$boat.'</br>';
                $total += $qty;
            }
            $boats = $boats !== '' ? $boats : 'Matériel perso';

            $startAt = clone $boatTrip->startAt;
            $shouldEndAt = $boatTrip->startAt->add(\DateInterval::createFromDateString('+'.$boatTrip->hours.' hours'));
            if($shouldEndAt < new Carbon(new \DateTime())) {
                $state = 'success';
                $message = 'Sur le retour';
                if(abs($shouldEndAt->getTimestamp() - (new \DateTime())->getTimestamp()) > 15*60){
                    $state = 'danger';
                    $message = 'En retard';
                }
            }else {
                $state = 'info';
                $message = 'En navigation';
            }
            $boatTripsData[] = [
                $boats,
                '<span class="badge bg-info">'.$total.'</span>',
                $boatTrip->name,
                '<i class="fas fa-clock time-icon"></i> '.$startAt->format('H:i').' <small>'.$boatTrip->hours.' heure(s)</small>',
                '   <span style="display: inline-block;">
                        <span class="badge bg-'.$state.'">'.$message.'</span>
                        <i class="fas fa-clock time-icon"></i> '.$shouldEndAt->format('H:i'). '
                    </span>',
                '
                    <i style="cursor: pointer;" data-href="'.route('boat-trip.start', ['boatTripId' => $boatTrip->id]).'"
                     data-toggle="tooltip" data-placement="top" title="Démarrer"
                    class="btn-start fa fa-play text-green p-1"></i>
                    <i style="cursor: pointer;" data-href="'.route('boat-trip.end', ['boatTripId' => $boatTrip->id]).'"
                     data-toggle="tooltip" data-placement="top" title="Emarger"
                    class="btn-end fa fa-pause text-blue p-1"></i>
                    <i style="cursor: pointer;" data-href="'.route('boat-trip.cancel', ['boatTripId' => $boatTrip->id]).'"
                     data-toggle="tooltip" data-placement="top" title="Supprimer la sortie"
                    class="btn-cancel fa fa-trash text-red p-1"></i>
                '
            ];
        }

        return [
            'draw' => $request->get('draw'),
            'recordsTotal' => count($boatTrips),
            'recordsFiltered' => $boatTrips->total(),
            'data' => $boatTripsData ?? [],
        ];
    }

    public function endedBoatTripList(Request $request, GetBoatTripsList $getBoatTripsList)
    {
        $start = $request->input('start', 0);
        $search = $request->input('search.value', '');
        $perPage = $request->input('length', 10);
        $sortDir = $request->input('order.0.dir', null);
        $sortIndex = $request->input('order.0.column', null);
        $sort = $request->input('columns.'.$sortIndex.'.name', null);
        $filters = ['ended' => true];
        $boatTrips = $getBoatTripsList->execute($search, $start, $perPage, $sort, $sortDir, $filters);

        foreach ($boatTrips as $boatTrip) {
            $boats = '';
            foreach($boatTrip->boats as $boat => $qty){
                $boats .= $qty. ' '.$boat.'</br>';
            }
            $boats = $boats !== '' ? $boats : 'Matériel perso';

            $shouldEndAt = $boatTrip->startAt->add(\DateInterval::createFromDateString('+'.$boatTrip->hours.' hours'));

            $boatTripsData[] = [
                $boats,
                $boatTrip->name,
                '<i class="fas fa-clock time-icon"></i> '.$shouldEndAt->format('H:i'),
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
        $fleets = $getFleetsList->execute(['filters' => ['state' => Fleet::STATE_ACTIVE]]);
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

    public function forceAdd(Request $request, ForceAddBoatTrip $forceAddBoatTrip)
    {
        $boats = $request->input('boats');
        $name = $request->input('name');
        $hours = $request->input('hours', 1);
        $startAt = $request->input('start_at');

        $boatsProcessed = [];
        foreach($boats as $boat){
            $boatsProcessed[$boat['id']] = isset($boatsProcessed[$boat['id']]) ? $boatsProcessed[$boat['id']] + $boat['number'] : $boat['number'];
        }
        $forceAddBoatTrip->execute($boatsProcessed, $name, $hours);
        return [];
    }

    public function start(string $boatTripId, StartBoatTrip $startBoatTrip)
    {
        $startBoatTrip->execute($boatTripId);
        return [];
    }

    public function cancel(string $boatTripId, CancelBoatTrip $cancelBoatTrip)
    {
        $cancelBoatTrip->execute($boatTripId);
        return [];
    }

    public function end(string $boatTripId, EndBoatTrip $endBoatTrip)
    {
        $endBoatTrip->execute($boatTripId);
        return [];
    }
}
