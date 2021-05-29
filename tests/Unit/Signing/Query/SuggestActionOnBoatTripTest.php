<?php


namespace Tests\Unit\Signing\Query;


use App\Signing\Signing\Domain\Entities\Dto\BoatTripsDTo;
use App\Signing\Signing\Domain\Entities\Dto\SuggestionDto;
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
        $boatTripDto = new BoatTripsDTo(id:'abc',  startAt: new \DateTime('-56 minutes'), boats:['abcd' => 1], name:'tabarly', hours:1);
        $this->readBoatTripRepository->save($boatTripDto);

        $suggestionExpected = new SuggestionDto('finish',  $boatTripDto);
        $suggestion = $this->getBoatTripsSuggestions->execute();
        self::assertEquals($suggestionExpected, $suggestion[0]);
    }

    /**
     * @test
     */
    public function shouldGetSuggestionOnBoatTripNearToStart()
    {
        $boatTripDto = new BoatTripsDTo(id:'abc',  shouldStartAt: new \DateTime('+4 minutes'), boats:['abcd' => 1], name:'tabarly', hours:1);
        $this->readBoatTripRepository->save($boatTripDto);

        $suggestionExpected = new SuggestionDto('start',  $boatTripDto);
        $suggestion = $this->getBoatTripsSuggestions->execute();
        self::assertEquals($suggestionExpected, $suggestion[0]);
    }
}
