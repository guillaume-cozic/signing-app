<?php


namespace App\Signing\Signing\Domain\DomainServices;


use App\Signing\Shared\Entities\Id;
use App\Signing\Shared\Providers\DateProvider;
use App\Signing\Signing\Domain\Entities\BoatTrip;
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
    public function execute(bool $force, array $boats, string $name, int $numberHours, string $startAtHours = null, bool $startNow = null)
    {
        $boatTripBuilder = BoatTripBuilder::build((new Id())->id())
            ->withSailor(name:$name)
            ->withBoats($boats);

        $boatTrip = $this->buildBoatTrip($startNow, $boatTripBuilder, $numberHours, $startAtHours);
        $boatTrip->create($force);
    }

    private function buildBoatTrip(?bool $startNow, BoatTripBuilder $boatTripBuilder, int $numberHours, string $startAtHours = null): BoatTrip
    {
        if ($startNow) {
            return $boatTripBuilder->inProgress($numberHours);
        }
        $shouldStartAt = $this->calculateShouldStartAt($startAtHours);
        return $boatTripBuilder->notStarted(shouldStartAt: $shouldStartAt, numberHours: $numberHours);
    }

    private function calculateShouldStartAt(?string $startAtHours): bool|\DateTime
    {
        if ($startAtHours === null) {
            return $this->dateProvider->current()->add(new \DateInterval('PT5M'));
        }
        list($hours, $minutes) = explode(':', $startAtHours);
        return $this->dateProvider->current()->setTime($hours, $minutes);
    }
}
