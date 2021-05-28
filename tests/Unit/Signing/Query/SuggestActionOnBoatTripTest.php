<?php


namespace Tests\Unit\Signing\Query;


use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\UseCases\Query\GetBoatTripsSuggestions;
use Tests\TestCase;

class SuggestActionOnBoatTripTest extends TestCase
{

    private GetBoatTripsSuggestions $getBoatTripsSuggestions;

    public function setUp(): void
    {
        parent::setUp();
        $this->getBoatTripsSuggestions = app(GetBoatTripsSuggestions::class);
    }

    /**
     * @test
     */
    public function shouldNotGetSuggestionsOnBoatTripWhenAnyBoatTrip()
    {
        $suggestions = $this->getBoatTripsSuggestions->execute();
        self::assertEmpty($suggestions);
    }


    /**
     * @test
     */
    public function shouldGetSuggestionOnBoatTripNearToFinish()
    {
        $boatTrip = BoatTripBuilder::build('abc')
            ->withBoats(['abcd' => 1])
            ->withSailor(name:'tabarly')
            ->withDates(startAt: new \DateTime('-56 minutes'))
            ->inProgress(1)
        ;
        $this->readBoatTripRepository->save($boatTrip->getState());

        $suggestionExpected = ['action' => 'finish', 'boat-trip' => $boatTrip->getState()];
        $suggestion = $this->getBoatTripsSuggestions->execute();
        self::assertEquals($suggestionExpected, $suggestion[0]);
    }

    /**
     * @test
     */
    public function shouldGetSuggestionOnBoatTripNearToStart()
    {
        $boatTrip = BoatTripBuilder::build('abc')
            ->withBoats(['abcd' => 1])
            ->withSailor(name:'tabarly')
            ->withDates(shouldStartAt: new \DateTime('+4 minutes'))
            ->inProgress(1)
        ;
        $this->readBoatTripRepository->save($boatTrip->getState());

        $suggestionExpected = ['action' => 'start', 'boat-trip' => $boatTrip->getState()];
        $suggestion = $this->getBoatTripsSuggestions->execute();
        self::assertEquals($suggestionExpected, $suggestion[0]);
    }

    /**
     * @test
     */
    /*public function shouldNotGetSuggestionsOnBoatTripAlreadyFinished()
    {
        $boatTrip = BoatTripBuilder::build('abc')
            ->withBoats(['abcd' => 1])
            ->withSailor(name:'tabarly')
            ->ended(1);
        $this->boatTripRepository->save($boatTrip->getState());

        $suggestions = $this->getBoatTripsSuggestions->execute();
        self::assertEmpty($suggestions);
    }*/


}
