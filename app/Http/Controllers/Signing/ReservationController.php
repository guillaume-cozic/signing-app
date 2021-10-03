<?php

namespace App\Http\Controllers\Signing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Domain\BoatTrip\AddBoatTripRequest;
use App\Signing\Shared\Services\UseCaseHandler\UseCaseHandler;
use App\Signing\Signing\Application\ParametersWrapper\ReservationParameters;
use App\Signing\Signing\Domain\UseCases\BoatTrip\AddReservation;
use App\Signing\Signing\Domain\UseCases\GetBoatTripsList;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function add(AddBoatTripRequest $request, AddReservation $addReservation)
    {
        list($name, $hours, $shouldStartAt, $isMember, $isInstructor, $note, $boatsProcessed) = $this->form($request);
        $addReservation->execute($boatsProcessed, $name, $hours, $shouldStartAt, $isInstructor, $isMember, $note);
        return [];
    }

    public function forceAdd(AddBoatTripRequest $request, AddReservation $addReservation)
    {
        list($name, $hours, $shouldStartAt, $isMember, $isInstructor, $note, $boatsProcessed) = $this->form($request);

        (new UseCaseHandler($addReservation))
            ->execute(
                new ReservationParameters($boatsProcessed, $name, $hours, $shouldStartAt, $isInstructor, $isMember, $note, true)
            );
        return [];
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


    private function form(AddBoatTripRequest $request): array
    {
        $boats = $request->input('boats');
        $name = $request->input('name');
        $hours = $request->input('hours', 1);
        $shouldStartAt = $request->input('should_start_at', null);
        $isMember = $request->input('is_member', false) == 'on';
        $isInstructor = $request->input('is_instructor', false) == 'on';
        $sailorId = $request->input('sailor_id', null);
        $note = $request->input('note');

        $boatsProcessed = [];
        foreach ($boats as $boat) {
            $boatsProcessed[$boat['id']] = isset($boatsProcessed[$boat['id']]) ? $boatsProcessed[$boat['id']] + $boat['number'] : $boat['number'];
        }
        return array($name, $hours, $shouldStartAt, $isMember, $isInstructor, $note, $boatsProcessed);
    }

}
