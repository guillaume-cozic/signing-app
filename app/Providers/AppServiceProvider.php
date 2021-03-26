<?php

namespace App\Providers;

use App\Signing\Shared\Providers\DateProvider;
use App\Signing\Shared\Services\ContextService;
use App\Signing\Shared\Services\ContextServiceImpl;
use App\Signing\Shared\Services\Translations\TranslationService;
use App\Signing\Shared\Services\Translations\TranslationServiceImpl;
use App\Signing\Signing\Domain\Provider\IdentityProvider;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Domain\Repositories\Read\ReadBoatTripRepository;
use App\Signing\Signing\Domain\Repositories\FleetRepository;
use App\Signing\Signing\Domain\Repositories\Read\ReadFleetRepository;
use App\Signing\Signing\Domain\UseCases\AddBoatTrip;
use App\Signing\Signing\Domain\UseCases\AddMemberBoatTrip;
use App\Signing\Signing\Domain\UseCases\AddFleet;
use App\Signing\Signing\Domain\UseCases\AddTimeToBoatTrip;
use App\Signing\Signing\Domain\UseCases\DelayBoatTripStart;
use App\Signing\Signing\Domain\UseCases\DisableFleet;
use App\Signing\Signing\Domain\UseCases\EnableFleet;
use App\Signing\Signing\Domain\UseCases\EndBoatTrip;
use App\Signing\Signing\Domain\UseCases\GetBoatTripsList;
use App\Signing\Signing\Domain\UseCases\GetFleetsList;
use App\Signing\Signing\Domain\UseCases\Impl\AddBoatTripImpl;
use App\Signing\Signing\Domain\UseCases\Impl\AddMemberBoatTripImpl;
use App\Signing\Signing\Domain\UseCases\Impl\AddFleetImpl;
use App\Signing\Signing\Domain\UseCases\Impl\AddTimeToBoatTripImpl;
use App\Signing\Signing\Domain\UseCases\Impl\DelayBoatTripStartImpl;
use App\Signing\Signing\Domain\UseCases\Impl\DisableFleetImpl;
use App\Signing\Signing\Domain\UseCases\Impl\EnableFleetImpl;
use App\Signing\Signing\Domain\UseCases\Impl\EndBoatTripImpl;
use App\Signing\Signing\Domain\UseCases\Impl\GetBoatTripsListImpl;
use App\Signing\Signing\Domain\UseCases\Impl\GetFleetsListImpl;
use App\Signing\Signing\Domain\UseCases\Impl\UpdateFleetImpl;
use App\Signing\Signing\Domain\UseCases\Query\GetFleet;
use App\Signing\Signing\Domain\UseCases\Query\Impl\GetFleetImpl;
use App\Signing\Signing\Domain\UseCases\System\CreateFleetWhenTeamCreated;
use App\Signing\Signing\Domain\UseCases\System\Impl\CreateFleetWhenTeamCreatedImpl;
use App\Signing\Signing\Domain\UseCases\UpdateFleet;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Read\SqlReadBoatTripRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Read\SqlReadFleetRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\SqlBoatTripRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\SqlFleetRepository;
use Illuminate\Support\ServiceProvider;
use Tests\Adapters\ContextServiceTestImpl;
use Tests\Unit\Adapters\Provider\FakeDateProvider;
use Tests\Unit\Adapters\Provider\FakeIdentityProvider;
use Tests\Unit\Adapters\Repositories\InMemoryBoatTripRepository;
use Tests\Unit\Adapters\Repositories\InMemoryFleetRepository;
use Tests\Unit\Adapters\Service\FakeTranslationService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AddFleet::class, AddFleetImpl::class);
        $this->app->singleton(AddBoatTrip::class, AddBoatTripImpl::class);
        $this->app->singleton(EndBoatTrip::class, EndBoatTripImpl::class);
        $this->app->singleton(AddMemberBoatTrip::class, AddMemberBoatTripImpl::class);
        $this->app->singleton(AddTimeToBoatTrip::class, AddTimeToBoatTripImpl::class);
        $this->app->singleton(UpdateFleet::class, UpdateFleetImpl::class);
        $this->app->singleton(DelayBoatTripStart::class, DelayBoatTripStartImpl::class);
        $this->app->singleton(DisableFleet::class, DisableFleetImpl::class);
        $this->app->singleton(EnableFleet::class, EnableFleetImpl::class);
        $this->app->singleton(CreateFleetWhenTeamCreated::class, CreateFleetWhenTeamCreatedImpl::class);

        $this->app->singleton(GetBoatTripsList::class, GetBoatTripsListImpl::class);
        $this->app->singleton(GetFleetsList::class, GetFleetsListImpl::class);
        $this->app->singleton(GetFleet::class, GetFleetImpl::class);

        $this->app->singleton(IdentityProvider::class, FakeIdentityProvider::class);
        $this->app->singleton(DateProvider::class, FakeDateProvider::class);
        $this->app->singleton(ContextService::class, ContextServiceImpl::class);

        if(config('app.env') == 'testing') {
            $this->app->singleton(FleetRepository::class, InMemoryFleetRepository::class);
            $this->app->singleton(BoatTripRepository::class, InMemoryBoatTripRepository::class);
            $this->app->singleton(TranslationService::class, FakeTranslationService::class);
            $this->app->singleton(ContextService::class, ContextServiceTestImpl::class);
        }
        if(config('app.env') == 'testing-db') {
            $this->app->singleton(FleetRepository::class, SqlFleetRepository::class);
            $this->app->singleton(BoatTripRepository::class, SqlBoatTripRepository::class);
            $this->app->singleton(ReadBoatTripRepository::class, SqlReadBoatTripRepository::class);
            $this->app->singleton(ReadFleetRepository::class, SqlReadFleetRepository::class);
            $this->app->singleton(TranslationService::class, TranslationServiceImpl::class);
            $this->app->singleton(ContextService::class, ContextServiceTestImpl::class);
        }

        if(config('app.env') == 'local') {
            $this->app->singleton(FleetRepository::class, SqlFleetRepository::class);
            $this->app->singleton(BoatTripRepository::class, SqlBoatTripRepository::class);
            $this->app->singleton(ReadBoatTripRepository::class, SqlReadBoatTripRepository::class);
            $this->app->singleton(ReadFleetRepository::class, SqlReadFleetRepository::class);
            $this->app->singleton(TranslationService::class, TranslationServiceImpl::class);
        }
    }

    public function boot()
    {
    }
}
