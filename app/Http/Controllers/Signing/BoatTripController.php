<?php


namespace App\Http\Controllers\Signing;


use App\Http\Controllers\Controller;
use App\Http\Requests\Domain\BoatTrip\AddBoatTripRequest;
use App\Signing\Signing\Domain\Entities\Fleet\Fleet;
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
        $fleets = $getFleetsList->execute(['filters' => ['state' => Fleet::STATE_ACTIVE]], 0, 0, 'name');
        return view('dashboard', [
            'fleets' => $fleets,
            'fleetsCount' => $fleets->count()
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
        $filters = [
            'reservations' => (bool)$request->input('reservations', false)
        ];
        $boatTrips = $getBoatTripsList->execute($search, $start, $perPage, $sort, $sortDir, $filters);

        foreach ($boatTrips as $boatTrip) {
            $boats = '';
            $total = 0;
            foreach($boatTrip->boats as $boat => $qty){
                $boats .= $qty.' '.$boat.'</br>';
                $total += $qty;
            }
            $boats = $boats !== '' ? $boats : 'Matériel perso';

            $startAt = isset($boatTrip->startAt) ? clone $boatTrip->startAt : clone $boatTrip->shouldStartAt;
            $shouldEndAt = (new Carbon($startAt))->add(\DateInterval::createFromDateString('+'.$boatTrip->hours * 60 .' minutes'));

            $badgeName = "";
            if($boatTrip->isMember){
                $badgeName = '<span class="badge bg-primary">Adhérent</span>';
            }
            if($boatTrip->isInstructor){
                $badgeName = '<span class="badge bg-info">Moniteur</span>';
            }

            $actions = [];

            if(isset($boatTrip->note) && $boatTrip->note !== ''){
                $actions[] = 'more';
            }

            if($boatTrip->isReservation && $boatTrip->startAt === null) {
                $state = 'indigo';
                $message = 'Réservation';
                $actions[] = 'start';
                $actions[] = 'cancel';
            }

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
                if(!$boatTrip->isReservation) {
                    $state = 'warning';
                    $message = 'A terre';
                    $actions[] = 'start';
                    $actions[] = 'cancel';
                }
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
                $buttons .= '<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Démarrer la sortie">
                        <i data-href="'.route('boat-trip.start', ['boatTripId' => $boatTrip->id]).'" class="btn-start fa fa-play text-green p-1"></i>
                    </a>';
            }
            if(in_array('end', $actions)) {
                $buttons .= '<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Terminer la sortie">
                    <i data-href="'.route('boat-trip.end', ['boatTripId' => $boatTrip->id]).'"
                    class="btn-end fa fa-pause text-blue p-1"></i>
                    </a>';
            }
            if(in_array('cancel', $actions)) {
                $buttons .= '<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Supprimer la sortie">
                        <i style="cursor: pointer;" data-href="'.route('boat-trip.cancel', ['boatTripId' => $boatTrip->id]).'"
                        class="btn-cancel fa fa-trash text-red p-1"></i>
                    </a>';
            }
            if(in_array('more', $actions)) {
                $buttons .= '<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Notes">
                    <i style="cursor: pointer;"
                      data-note="'.$boatTrip->note.'"
                    class="btn-more fa fa-clipboard-list text-gray p-1"></i></a>';
            }

            $boatTripsData[] = [
                $boats,
                '<span class="badge bg-info">'.$total.'</span>',
                $badgeName.' '.$boatTrip->name,
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


    public function reservations(Request $request, GetBoatTripsList $getBoatTripsList)
    {
        $start = $request->input('start', 0);
        $search = $request->input('search.value', '');
        $perPage = $request->input('length', 10);
        $sortDir = $request->input('order.0.dir', null);
        $sortIndex = $request->input('order.0.column', null);
        $sort = $request->input('columns.'.$sortIndex.'.name', null);
        $filters = [
            'reservations' => (bool)$request->input('reservations', false)
        ];
        $boatTrips = $getBoatTripsList->execute($search, $start, $perPage, $sort, $sortDir, $filters);

        foreach ($boatTrips as $boatTrip) {
            $boats = '';
            $total = 0;
            foreach($boatTrip->boats as $boat => $qty){
                $boats .= $qty. ' '.$boat.'</br>';
                $total += $qty;
            }
            $boats = $boats !== '' ? $boats : 'Matériel perso';

            $startAt = isset($boatTrip->startAt) ? clone $boatTrip->startAt : clone $boatTrip->shouldStartAt;

            $state = 'success';
            $message = 'Réservation';

            $buttons = '<i style="cursor: pointer;" data-href="'.route('boat-trip.cancel', ['boatTripId' => $boatTrip->id]).'"
                 data-toggle="tooltip" data-placement="top" title="Supprimer la réservation"
                class="btn-cancel fa fa-trash text-red p-1"></i>';

            if(isset($boatTrip->note) && $boatTrip->note !== "") {
                $buttons .= '<i style="cursor: pointer;"
                     data-toggle="tooltip" data-placement="top" title="Notes" data-note="'.$boatTrip->note.'"
                    class="btn-more fa fa-clipboard-list text-gray p-1"></i>';
            }

            $badgeName = "";
            if($boatTrip->isMember){
                $badgeName = '<span class="badge bg-primary">Adhérent</span>';
            }
            if($boatTrip->isInstructor){
                $badgeName = '<span class="badge bg-info">Moniteur</span>';
            }

            setlocale(LC_TIME, "fr_FR");
            $boatTripsData[] = [
                $boats,
                '<span class="badge bg-info">'.$total.'</span>',
                mb_convert_encoding($badgeName.' '.$boatTrip->name, 'UTF-8', 'UTF-8'),
                '<i class="fas fa-clock time-icon"></i> '.utf8_encode(strftime('%a %e %b à %H:%M', $startAt->getTimestamp())).' <small>'.$boatTrip->hours.' h</small>',
                '   <span style="display: inline-block;">
                        <span class="badge bg-'.$state.'">'.$message.'</span>
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

            $shouldEndAt = $boatTrip->startAt->add(\DateInterval::createFromDateString('+'.$boatTrip->hours * 60 .' minutes'));

            $boatTripsData[] = [
                $boats,
                $boatTrip->name.' '.$boatTrip->note,
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
        $fleets = $getFleetsList->execute(['filters' => ['state' => Fleet::STATE_ACTIVE]], 0, 0, 'name');
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
        $isMember = $request->input('is_member', false) == 'on';
        $isInstructor = $request->input('is_instructor', false) == 'on';
        $isReservation = $request->input('is_reservation', false);
        $sailorId = $request->input('sailor_id', null);
        $note = $request->input('note');

        $boatsProcessed = [];
        foreach($boats as $boat){
            $boatsProcessed[$boat['id']] = isset($boatsProcessed[$boat['id']]) ? $boatsProcessed[$boat['id']] + $boat['number'] : $boat['number'];
        }
        $addBoatTrip->execute($boatsProcessed, $name, $hours, $startAt, $startNow, $startAuto, $isInstructor, $isMember, $isReservation, $note, $sailorId);
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
                $boats .= $qty. ' ' . $boat.'</br>';
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

    public function notEnded(Request $request)
    {
        return view('modal.partials.mass-close-form', [
            'boatTrips' => $request->session()->get('boat-trips_not_closed')
        ]);
    }

    public function massEnd(Request $request)
    {
        $boatTrips = $request->except('_token');
        foreach($boatTrips as $boatTrip => $action){
            if($action === 'close'){
                app(EndBoatTrip::class)->execute($boatTrip);
                continue;
            }
            app(CancelBoatTrip::class)->execute($boatTrip);
        }
        $request->session()->remove('boat-trips_not_closed');
        return back();
    }
}
