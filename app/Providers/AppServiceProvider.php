<?php

namespace App\Providers;


use App\Signing\Notifications\Domain\UseCases\Notifications\Impl\SendBoatTripEndedNotificationImpl;
use App\Signing\Notifications\Domain\UseCases\Notifications\Impl\SendBoatTripStartedNotificationImpl;
use App\Signing\Notifications\Domain\UseCases\Notifications\SendBoatTripEndedNotification;
use App\Signing\Notifications\Domain\UseCases\Notifications\SendBoatTripStartedNotification;
use App\Signing\Reporting\Domain\Charts\BoatTripsByDay;
use App\Signing\Reporting\Domain\Charts\BoatTripsByFleet;
use App\Signing\Reporting\Domain\Charts\FrequencyByDay;
use App\Signing\Reporting\Domain\Repositories\BoatTripReportingRepository;
use App\Signing\Reporting\Infrastructure\Repositories\SqlBoatTripReportingRepository;
use App\Signing\Shared\Providers\AuthGateway;
use App\Signing\Shared\Providers\DateProvider;
use App\Signing\Shared\Providers\Impl\AuthGatewayImpl;
use App\Signing\Shared\Providers\Impl\DateProviderImpl;
use App\Signing\Shared\Services\ContextService;
use App\Signing\Shared\Services\ContextServiceImpl;
use App\Signing\Signing\Domain\Provider\IdentityProvider;
use App\Signing\Signing\Domain\Provider\IdentityProviderImpl;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Domain\Repositories\Read\ReadBoatTripRepository;
use App\Signing\Signing\Domain\Repositories\FleetRepository;
use App\Signing\Signing\Domain\Repositories\Read\ReadFleetRepository;
use App\Signing\Signing\Domain\Repositories\Read\ReadRentalPackageRepository;
use App\Signing\Signing\Domain\Repositories\Read\ReadSailorRentalPackageRepository;
use App\Signing\Signing\Domain\Repositories\RentalPackageRepository;
use App\Signing\Signing\Domain\Repositories\SailorRentalPackageRepository;
use App\Signing\Signing\Domain\Repositories\SailorRepository;
use App\Signing\Signing\Domain\UseCases\AddBoatTrip;
use App\Signing\Signing\Domain\UseCases\AddFleet;
use App\Signing\Signing\Domain\UseCases\BoatTrip\AddReservation;
use App\Signing\Signing\Domain\UseCases\BoatTrip\CancelBoatTrip;
use App\Signing\Signing\Domain\UseCases\BoatTrip\ForceAddBoatTrip;
use App\Signing\Signing\Domain\UseCases\BoatTrip\Impl\AddReservationImpl;
use App\Signing\Signing\Domain\UseCases\BoatTrip\Impl\CancelBoatTripImpl;
use App\Signing\Signing\Domain\UseCases\BoatTrip\Impl\ForceAddBoatTripImpl;
use App\Signing\Signing\Domain\UseCases\BoatTrip\Impl\StartBoatTripImpl;
use App\Signing\Signing\Domain\UseCases\BoatTrip\StartBoatTrip;
use App\Signing\Signing\Domain\UseCases\DisableFleet;
use App\Signing\Signing\Domain\UseCases\EnableFleet;
use App\Signing\Signing\Domain\UseCases\EndBoatTrip;
use App\Signing\Signing\Domain\UseCases\GetBoatTripsList;
use App\Signing\Signing\Domain\UseCases\GetFleetsList;
use App\Signing\Signing\Domain\UseCases\Impl\AddBoatTripImpl;
use App\Signing\Signing\Domain\UseCases\Impl\AddFleetImpl;
use App\Signing\Signing\Domain\UseCases\Impl\DisableFleetImpl;
use App\Signing\Signing\Domain\UseCases\Impl\EnableFleetImpl;
use App\Signing\Signing\Domain\UseCases\Impl\EndBoatTripImpl;
use App\Signing\Signing\Domain\UseCases\Impl\GetBoatTripsListImpl;
use App\Signing\Signing\Domain\UseCases\Impl\GetFleetsListImpl;
use App\Signing\Signing\Domain\UseCases\Impl\UpdateFleetImpl;
use App\Signing\Signing\Domain\UseCases\Query\GetBoatTripsSuggestions;
use App\Signing\Signing\Domain\UseCases\Query\GetFleet;
use App\Signing\Signing\Domain\UseCases\Query\GetNumberBoatsOfFleetAvailable;
use App\Signing\Signing\Domain\UseCases\Query\Impl\GetBoatTripsSuggestionsImpl;
use App\Signing\Signing\Domain\UseCases\Query\Impl\GetFleetImpl;
use App\Signing\Signing\Domain\UseCases\Query\Impl\GetNumberBoatsOfFleetAvailableImpl;
use App\Signing\Signing\Domain\UseCases\RentalPackage\AddOrSubHoursToSailorRentalPackage;
use App\Signing\Signing\Domain\UseCases\RentalPackage\CreateRentalPackage;
use App\Signing\Signing\Domain\UseCases\RentalPackage\CreateSailorRentalPackage;
use App\Signing\Signing\Domain\UseCases\RentalPackage\DecreaseSailorRentalPackageHoursWhenBoatTripFinished;
use App\Signing\Signing\Domain\UseCases\RentalPackage\EditRentalPackage;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Impl\AddOrSubHoursToSailorRentalPackageImpl;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Impl\CreateRentalPackageImpl;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Impl\CreateSailorRentalPackageImpl;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Impl\DecreaseSailorRentalPackageHoursWhenBoatTripFinishedImpl;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Impl\EditRentalPackageImpl;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Impl\GetActionsSailorRentalPackageImpl;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\GetActionsSailorRentalPackage;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\GetRentalPackage;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\GetRentalPackages;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\Impl\GetRentalPackageImpl;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\Impl\GetRentalPackagesImpl;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\Impl\SearchSailorRentalPackagesImpl;
use App\Signing\Signing\Domain\UseCases\RentalPackage\Query\SearchSailorRentalPackages;
use App\Signing\Signing\Domain\UseCases\UpdateFleet;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Read\SqlReadBoatTripRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Read\SqlReadFleetRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Read\SqlReadRentalPackageRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Read\SqlReadSailorRentalPackageRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\SqlBoatTripRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\SqlFleetRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\SqlRentalPackageRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\SqlSailorRentalPackageRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\SqlSailorRepository;
use Illuminate\Support\ServiceProvider;
use Tests\Adapters\ContextServiceTestImpl;
use Tests\Unit\Adapters\Provider\FakeDateProvider;
use Tests\Unit\Adapters\Provider\FakeIdentityProvider;
use Tests\Unit\Adapters\Provider\InMemoryAuthGateway;
use Tests\Unit\Adapters\Repositories\InMemoryBoatTripRepository;
use Tests\Unit\Adapters\Repositories\InMemoryFleetRepository;
use Tests\Unit\Adapters\Repositories\InMemoryRentalPackageRepository;
use Tests\Unit\Adapters\Repositories\InMemorySailorRentalPackageRepository;
use Tests\Unit\Adapters\Repositories\InMemorySailorRepository;
use Tests\Unit\Adapters\Repositories\Read\InMemoryReadBoatTripRepository;
use ConsoleTVs\Charts\Registrar as Charts;


class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AddFleet::class, AddFleetImpl::class);
        $this->app->singleton(AddBoatTrip::class, AddBoatTripImpl::class);
        $this->app->singleton(EndBoatTrip::class, EndBoatTripImpl::class);
        $this->app->singleton(UpdateFleet::class, UpdateFleetImpl::class);
        $this->app->singleton(DisableFleet::class, DisableFleetImpl::class);
        $this->app->singleton(EnableFleet::class, EnableFleetImpl::class);
        $this->app->singleton(CancelBoatTrip::class, CancelBoatTripImpl::class);
        $this->app->singleton(ForceAddBoatTrip::class, ForceAddBoatTripImpl::class);
        $this->app->singleton(StartBoatTrip::class, StartBoatTripImpl::class);
        $this->app->singleton(CreateRentalPackage::class, CreateRentalPackageImpl::class);
        $this->app->singleton(EditRentalPackage::class, EditRentalPackageImpl::class);
        $this->app->singleton(CreateSailorRentalPackage::class, CreateSailorRentalPackageImpl::class);
        $this->app->singleton(DecreaseSailorRentalPackageHoursWhenBoatTripFinished::class, DecreaseSailorRentalPackageHoursWhenBoatTripFinishedImpl::class);
        $this->app->singleton(AddOrSubHoursToSailorRentalPackage::class, AddOrSubHoursToSailorRentalPackageImpl::class);

        $this->app->singleton(SendBoatTripEndedNotification::class, SendBoatTripEndedNotificationImpl::class);
        $this->app->singleton(SendBoatTripStartedNotification::class, SendBoatTripStartedNotificationImpl::class);

        $this->app->singleton(GetBoatTripsList::class, GetBoatTripsListImpl::class);
        $this->app->singleton(GetFleetsList::class, GetFleetsListImpl::class);
        $this->app->singleton(GetFleet::class, GetFleetImpl::class);
        $this->app->singleton(GetNumberBoatsOfFleetAvailable::class, GetNumberBoatsOfFleetAvailableImpl::class);
        $this->app->singleton(GetBoatTripsSuggestions::class, GetBoatTripsSuggestionsImpl::class);
        $this->app->singleton(GetRentalPackages::class, GetRentalPackagesImpl::class);
        $this->app->singleton(GetRentalPackage::class, GetRentalPackageImpl::class);
        $this->app->singleton(GetActionsSailorRentalPackage::class, GetActionsSailorRentalPackageImpl::class);
        $this->app->singleton(SearchSailorRentalPackages::class, SearchSailorRentalPackagesImpl::class);
        $this->app->singleton(AddReservation::class, AddReservationImpl::class);

        $this->app->singleton(ContextService::class, ContextServiceImpl::class);
        $this->app->singleton(AuthGateway::class, AuthGatewayImpl::class);

        if(config('app.env') == 'testing') {
            $this->app->singleton(IdentityProvider::class, FakeIdentityProvider::class);
            $this->app->singleton(DateProvider::class, FakeDateProvider::class);
            $this->app->singleton(FleetRepository::class, InMemoryFleetRepository::class);
            $this->app->singleton(BoatTripRepository::class, InMemoryBoatTripRepository::class);
            $this->app->singleton(ContextService::class, ContextServiceTestImpl::class);
            $this->app->singleton(AuthGateway::class, InMemoryAuthGateway::class);
            $this->app->singleton(ReadBoatTripRepository::class, InMemoryReadBoatTripRepository::class);
            $this->app->singleton(RentalPackageRepository::class, InMemoryRentalPackageRepository::class);
            $this->app->singleton(SailorRentalPackageRepository::class, InMemorySailorRentalPackageRepository::class);
            $this->app->singleton(SailorRepository::class, InMemorySailorRepository::class);
        }

        if(config('app.env') == 'testing-db') {
            $this->app->singleton(IdentityProvider::class, FakeIdentityProvider::class);
            $this->app->singleton(DateProvider::class, FakeDateProvider::class);
            $this->app->singleton(FleetRepository::class, SqlFleetRepository::class);
            $this->app->singleton(BoatTripRepository::class, SqlBoatTripRepository::class);
            $this->app->singleton(ReadBoatTripRepository::class, SqlReadBoatTripRepository::class);
            $this->app->singleton(ReadRentalPackageRepository::class, SqlReadRentalPackageRepository::class);
            $this->app->singleton(ReadFleetRepository::class, SqlReadFleetRepository::class);
            $this->app->singleton(RentalPackageRepository::class, SqlRentalPackageRepository::class);
            $this->app->singleton(ContextService::class, ContextServiceTestImpl::class);
            $this->app->singleton(AuthGateway::class, InMemoryAuthGateway::class);
            $this->app->singleton(ReadSailorRentalPackageRepository::class, SqlReadSailorRentalPackageRepository::class);
            $this->app->singleton(SailorRentalPackageRepository::class, SqlSailorRentalPackageRepository::class);
            $this->app->singleton(SailorRepository::class, SqlSailorRepository::class);
        }

        if(config('app.env') === 'local' || config('app.env') === "production") {
            $this->app->singleton(IdentityProvider::class, IdentityProviderImpl::class);
            $this->app->singleton(DateProvider::class, DateProviderImpl::class);
            $this->app->singleton(FleetRepository::class, SqlFleetRepository::class);
            $this->app->singleton(BoatTripRepository::class, SqlBoatTripRepository::class);
            $this->app->singleton(ReadBoatTripRepository::class, SqlReadBoatTripRepository::class);
            $this->app->singleton(ReadFleetRepository::class, SqlReadFleetRepository::class);
            $this->app->singleton(RentalPackageRepository::class, SqlRentalPackageRepository::class);
            $this->app->singleton(ReadRentalPackageRepository::class, SqlReadRentalPackageRepository::class);
            $this->app->singleton(ReadSailorRentalPackageRepository::class, SqlReadSailorRentalPackageRepository::class);
            $this->app->singleton(SailorRentalPackageRepository::class, SqlSailorRentalPackageRepository::class);
            $this->app->singleton(SailorRepository::class, SqlSailorRepository::class);
        }

        $this->app->singleton(BoatTripReportingRepository::class, SqlBoatTripReportingRepository::class);
    }

    public function boot(Charts $charts)
    {
        $charts->register([
            BoatTripsByDay::class,
            BoatTripsByFleet::class,
            FrequencyByDay::class,
        ]);
    }
}
