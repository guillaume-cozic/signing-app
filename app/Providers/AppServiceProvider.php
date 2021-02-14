<?php

namespace App\Providers;

use App\Signing\Shared\Providers\DateProvider;
use App\Signing\Shared\Services\Translations\TranslationService;
use App\Signing\Signing\Domain\Provider\IdentityProvider;
use App\Signing\Signing\Domain\Repositories\BoatTripRepository;
use App\Signing\Signing\Domain\Repositories\Read\ReadBoatTripRepository;
use App\Signing\Signing\Domain\Repositories\SupportRepository;
use App\Signing\Signing\Domain\UseCases\AddBoatTrip;
use App\Signing\Signing\Domain\UseCases\AddMemberBoatTrip;
use App\Signing\Signing\Domain\UseCases\AddSupport;
use App\Signing\Signing\Domain\UseCases\EndBoatTrip;
use App\Signing\Signing\Domain\UseCases\GetBoatTripsList;
use App\Signing\Signing\Domain\UseCases\Impl\AddBoatTripImpl;
use App\Signing\Signing\Domain\UseCases\Impl\AddMemberBoatTripImpl;
use App\Signing\Signing\Domain\UseCases\Impl\AddSupportImpl;
use App\Signing\Signing\Domain\UseCases\Impl\EndBoatTripImpl;
use App\Signing\Signing\Domain\UseCases\Impl\GetBoatTripsListImpl;
use App\Signing\Signing\Infrastructure\Repositories\Sql\Read\SqlReadBoatTripRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\SqlBoatTripRepository;
use App\Signing\Signing\Infrastructure\Repositories\Sql\SqlSupportRepository;
use Illuminate\Support\ServiceProvider;
use Tests\Unit\Adapters\Provider\FakeDateProvider;
use Tests\Unit\Adapters\Provider\FakeIdentityProvider;
use Tests\Unit\Adapters\Repositories\InMemoryBoatTripRepository;
use Tests\Unit\Adapters\Repositories\InMemorySupportRepository;
use Tests\Unit\Adapters\Service\FakeTranslationService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AddSupport::class, AddSupportImpl::class);
        $this->app->singleton(AddBoatTrip::class, AddBoatTripImpl::class);
        $this->app->singleton(EndBoatTrip::class, EndBoatTripImpl::class);
        $this->app->singleton(AddMemberBoatTrip::class, AddMemberBoatTripImpl::class);
        $this->app->singleton(GetBoatTripsList::class, GetBoatTripsListImpl::class);

        $this->app->singleton(IdentityProvider::class, FakeIdentityProvider::class);
        $this->app->singleton(DateProvider::class, FakeDateProvider::class);
        $this->app->singleton(TranslationService::class, FakeTranslationService::class);

        if(config('app.env') == 'testing') {
            $this->app->singleton(SupportRepository::class, InMemorySupportRepository::class);
            $this->app->singleton(BoatTripRepository::class, InMemoryBoatTripRepository::class);
        }
        if(config('app.env') == 'testing-db') {
            $this->app->singleton(SupportRepository::class, SqlSupportRepository::class);
            $this->app->singleton(BoatTripRepository::class, SqlBoatTripRepository::class);
            $this->app->singleton(ReadBoatTripRepository::class, SqlReadBoatTripRepository::class);
        }
    }

    public function boot()
    {

    }
}
