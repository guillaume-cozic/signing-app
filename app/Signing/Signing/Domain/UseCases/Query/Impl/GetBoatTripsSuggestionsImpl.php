<?php


namespace App\Signing\Signing\Domain\UseCases\Query\Impl;


use App\Signing\Signing\Domain\Entities\Dto\SuggestionDto;
use App\Signing\Signing\Domain\Repositories\Read\ReadBoatTripRepository;
use App\Signing\Signing\Domain\UseCases\Query\GetBoatTripsSuggestions;

class GetBoatTripsSuggestionsImpl implements GetBoatTripsSuggestions
{
    public function __construct(
        private ReadBoatTripRepository $readBoatTripRepository
    ){}

    public function execute():array
    {
        $suggestions = [];
        $boatTrips = $this->readBoatTripRepository->getNearToFinishOrStart();
        foreach($boatTrips as $boatTrip){
            $action = 'finish';
            if($boatTrip->startAt() == null){
                $action = 'start';
            }
            $suggestions[] = new SuggestionDto($action, $boatTrip);
        }
        return $suggestions;
    }
}
