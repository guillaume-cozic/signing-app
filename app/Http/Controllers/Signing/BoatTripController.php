<?php


namespace App\Http\Controllers\Signing;


use App\Http\Controllers\Controller;
use App\Http\Requests\Domain\BoatTrip\AddBoatTripRequest;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\UseCases\AddBoatTrip;
use App\Signing\Signing\Domain\UseCases\BoatTrip\CancelBoatTrip;
use App\Signing\Signing\Domain\UseCases\BoatTrip\ForceAddBoatTrip;
use App\Signing\Signing\Domain\UseCases\BoatTrip\StartBoatTrip;
use App\Signing\Signing\Domain\UseCases\EndBoatTrip;
use App\Signing\Signing\Domain\UseCases\GetBoatTripsList;
use App\Signing\Signing\Domain\UseCases\GetFleetsList;
use App\Signing\Signing\Domain\UseCases\Query\GetBoatTripsSuggestions;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BoatTripController extends Controller
{
    public function index(GetFleetsList $getFleetsList)
    {
        $fleets = $getFleetsList->execute(['filters' => ['state' => Fleet::STATE_ACTIVE]], 0, 0);
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

            $startAt = isset($boatTrip->startAt) ? clone $boatTrip->startAt : clone $boatTrip->shouldStartAt;
            $shouldEndAt = (new Carbon($startAt))->add(\DateInterval::createFromDateString('+'.$boatTrip->hours.' hours'));

            $actions = [];
            if($boatTrip->startAt !== null){
                $state = 'info';
                $message = 'En navigation';
                $actions[] = 'end';
                $actions[] = 'cancel';
                if($boatTrip->startAt > new Carbon(new \DateTime())){
                    $message = 'A terre';
                    $state = 'warning';
                }
            }else{
                $state = 'warning';
                $message = 'A terre';
                $actions[] = 'start';
                $actions[] = 'cancel';
            }

            if($boatTrip->startAt !== null && $shouldEndAt < new Carbon(new \DateTime())) {
                $state = 'success';
                $message = 'Sur le retour';
                if(abs($shouldEndAt->getTimestamp() - (new \DateTime())->getTimestamp()) > 15*60){
                    $state = 'danger';
                    $message = 'En retard';
                }
            }

            $buttons = '';
            if(in_array('start', $actions)) {
                $buttons .= '<i style="cursor: pointer;" data-href="'.route('boat-trip.start', ['boatTripId' => $boatTrip->id]).'"
                     data-toggle="tooltip" data-placement="top" title="Démarrer la sortie"
                    class="btn-start fa fa-play text-green p-1"></i>';
            }
            if(in_array('end', $actions)) {
                $buttons .= '<i style="cursor: pointer;" data-href="'.route('boat-trip.end', ['boatTripId' => $boatTrip->id]).'"
                     data-toggle="tooltip" data-placement="top" title="Terminer la sortie"
                    class="btn-end fa fa-pause text-blue p-1"></i>';
            }
            if(in_array('cancel', $actions)) {
                $buttons .= '<i style="cursor: pointer;" data-href="'.route('boat-trip.cancel', ['boatTripId' => $boatTrip->id]).'"
                     data-toggle="tooltip" data-placement="top" title="Supprimer la sortie"
                    class="btn-cancel fa fa-trash text-red p-1"></i>';
            }

            $boatTripsData[] = [
                $boats,
                '<span class="badge bg-info">'.$total.'</span>',
                $boatTrip->name,
                '<i class="fas fa-clock time-icon"></i> '.$startAt->format('H:i').' <small>'.$boatTrip->hours.' h</small>',
                '   <span style="display: inline-block;">
                        <span class="badge bg-'.$state.'">'.$message.'</span>
                        <i class="fas fa-clock time-icon"></i> '.$shouldEndAt->format('H:i'). '
                    </span>',
                $buttons
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

    public function add(AddBoatTripRequest $request, AddBoatTrip $addBoatTrip)
    {
        $boats = $request->input('boats');
        $name = $request->input('name');
        $hours = $request->input('hours', 1);
        $startAt = $request->input('start_at', null);
        $startNow = $request->input('start_now');
        $startAuto = $request->input('start_auto');

        $boatsProcessed = [];
        foreach($boats as $boat){
            $boatsProcessed[$boat['id']] = isset($boatsProcessed[$boat['id']]) ? $boatsProcessed[$boat['id']] + $boat['number'] : $boat['number'];
        }
        $addBoatTrip->execute($boatsProcessed, $name, $hours, $startAt, $startNow, $startAuto);
        return [];
    }

    public function forceAdd(AddBoatTripRequest $request, ForceAddBoatTrip $forceAddBoatTrip)
    {
        $boats = $request->input('boats');
        $name = $request->input('name');
        $hours = $request->input('hours', 1);
        $startAt = $request->input('start_at', null);
        $startNow = $request->input('start_now');
        $startAuto = $request->input('start_auto');

        $boatsProcessed = [];
        foreach($boats as $boat){
            $boatsProcessed[$boat['id']] = isset($boatsProcessed[$boat['id']]) ? $boatsProcessed[$boat['id']] + $boat['number'] : $boat['number'];
        }
        $forceAddBoatTrip->execute($boatsProcessed, $name, $hours, $startAt, $startNow, $startAuto);
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

    public function getSuggestionsBoatTrip(GetBoatTripsSuggestions $getBoatTripsSuggestions)
    {
        $boatTripsData = [];
        $suggestions = $getBoatTripsSuggestions->execute();
        foreach ($suggestions as $suggestion) {
            $boats = '';
            $boatTrip = $suggestion->boatTrip;
            foreach($suggestion->boatTrip->boats as $boat => $qty){
                $boats .= $qty. ' '.$boat.'</br>';
            }
            $boats = $boats !== '' ? $boats : 'Matériel perso';

            $boatTripsData[] = [
                'boats' => $boats,
                'name' => $boatTrip->name,
                'action' => $suggestion->action,
                'boat-trip_id' => $boatTrip->id
            ];
        }
        return view('boattrips.suggestions', ['suggestions' => $boatTripsData]);
    }
}
