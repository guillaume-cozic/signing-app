<?php

namespace Tests;

use App\Signing\Shared\Providers\AuthGateway;
use App\Signing\Shared\Providers\DateProvider;
use App\Signing\Shared\Services\ContextService;
use App\Signing\Shared\Services\Translations\TranslationService;
use App\Signing\Signing\Domain\Provider\IdentityProvider;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Domain\Repositories\FleetRepository;
use App\Signing\Signing\Domain\Repositories\Read\ReadBoatTripRepository;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Event;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected IdentityProvider $identityProvider;
    protected FleetRepository $fleetRepository;
    protected BoatTripRepository $boatTripRepository;
    protected ReadBoatTripRepository $readBoatTripRepository;
    protected DateProvider $dateProvider;
    protected TranslationService $translationService;
    protected ContextService $contextService;
    protected AuthGateway $authGateway;

    protected function setUp() :void
    {
        parent::setUp();
        $this->identityProvider = app(IdentityProvider::class);
        $this->fleetRepository = app(FleetRepository::class);
        $this->boatTripRepository = app(BoatTripRepository::class);
        $this->readBoatTripRepository = app(ReadBoatTripRepository::class);
        $this->dateProvider = app(DateProvider::class);
        $this->translationService = app(TranslationService::class);
        $this->contextService = app(ContextService::class);
        $this->contextService->setSailingClubId(1);
        $this->authGateway = app(AuthGateway::class);
        Event::fake();
    }
}
