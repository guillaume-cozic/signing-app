<?php

namespace Tests;

use App\Signing\School\Domain\Repositories\InternshipRepository;
use App\Signing\Shared\Providers\AuthGateway;
use App\Signing\Shared\Providers\DateProvider;
use App\Signing\Shared\Services\ContextService;
use App\Signing\Signing\Domain\Provider\IdentityProvider;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Domain\Repositories\FleetRepository;
use App\Signing\Signing\Domain\Repositories\Read\ReadBoatTripRepository;
use App\Signing\Signing\Domain\Repositories\RentalPackageRepository;
use App\Signing\Signing\Domain\Repositories\SailorRentalPackageRepository;
use App\Signing\Signing\Domain\Repositories\SailorRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Event;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected IdentityProvider $identityProvider;
    protected FleetRepository $fleetRepository;
    protected InternshipRepository $internshipRepository;
    protected BoatTripRepository $boatTripRepository;
    protected RentalPackageRepository $rentalPackageRepository;
    protected SailorRentalPackageRepository $sailorRentalPackageRepository;
    protected SailorRepository $sailorRepository;
    protected ReadBoatTripRepository $readBoatTripRepository;
    protected DateProvider $dateProvider;
    protected ContextService $contextService;
    protected AuthGateway $authGateway;

    protected function setUp() :void
    {
        parent::setUp();
        $this->identityProvider = app(IdentityProvider::class);
        $this->fleetRepository = app(FleetRepository::class);
        $this->boatTripRepository = app(BoatTripRepository::class);
        $this->readBoatTripRepository = app(ReadBoatTripRepository::class);
        $this->rentalPackageRepository = app(RentalPackageRepository::class);
        $this->sailorRentalPackageRepository = app(SailorRentalPackageRepository::class);
        $this->sailorRepository = app(SailorRepository::class);
        $this->dateProvider = app(DateProvider::class);
        $this->contextService = app(ContextService::class);
        $this->contextService->setSailingClubId(1);
        $this->authGateway = app(AuthGateway::class);
        $this->internshipRepository = app(InternshipRepository::class);
        Event::fake();
    }

    public function now():Carbon
    {
        return Carbon::instance($this->dateProvider->current());
    }
}
