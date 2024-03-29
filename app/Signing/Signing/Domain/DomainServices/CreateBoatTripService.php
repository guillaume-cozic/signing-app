<?php


namespace App\Signing\Signing\Domain\DomainServices;


use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Providers\DateProvider;
use App\Signing\Signing\Domain\Entities\BoatTrip\BoatTrip;
use App\Signing\Signing\Domain\Entities\Builder\BoatTripBuilder;
use App\Signing\Signing\Domain\Exceptions\BoatNotAvailable;

class CreateBoatTripService
{
    public function __construct(
        private DateProvider $dateProvider
    ){}

    /**
     * @throws BoatNotAvailable
     */
    public function execute(
        bool $force,
        array $boats,
        string $name,
        float $numberHours,
        string $startAtHours = null,
        bool $startNow = null,
        ?bool $startAuto = false,
        bool $isInstructor = false,
        bool $isMember = false,
        ?string $note = null,
        ?string $sailorId = null,
        bool $doNotDecreaseHours = false,
    )
    {
        $boatTripBuilder = BoatTripBuilder::build((new Id())->id())
            ->withSailor(name:$name, isInstructor: $isInstructor, isMember: $isMember, sailorId: $sailorId)
            ->withNote($note)
            ->withOptions(['do_not_decrease_hours' => $doNotDecreaseHours])
            ->withBoats($boats);

        $boatTrip = $this->buildBoatTrip($startNow, $boatTripBuilder, $numberHours, $startAtHours, $startAuto);
        $boatTrip->create($force, $startNow);
    }

    private function buildBoatTrip(?bool $startNow, BoatTripBuilder $boatTripBuilder, float $numberHours, string $startAtHours = null, ?bool $startAuto = false): BoatTrip
    {
        if ($startNow) {
            return $boatTripBuilder->inProgress($numberHours);
        }
        $shouldStartAt = $this->calculateShouldStartAt($startAtHours);
        if($startAuto){
            return $boatTripBuilder->withDates(startAt: $shouldStartAt, numberHours: $numberHours)->get();
        }
        return $boatTripBuilder->notStarted(shouldStartAt: $shouldStartAt, numberHours: $numberHours);
    }

    private function calculateShouldStartAt(?string $startAtHours): \DateTime
    {
        if ($startAtHours === null) {
            return $this->dateProvider->current()->add(new \DateInterval('PT5M'));
        }
        if(!str_contains($startAtHours, '-')) {
            list($hours, $minutes) = explode(':', $startAtHours);
            return $this->dateProvider->current()->setTime($hours, $minutes);
        }
        return (new \DateTime())->setTimestamp(strtotime($startAtHours));
    }
}
