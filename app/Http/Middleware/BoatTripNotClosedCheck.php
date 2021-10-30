<?php

namespace App\Http\Middleware;

use App\Signing\Signing\Domain\Repositories\Read\ReadBoatTripRepository;
use Closure;
use Illuminate\Http\Request;

class BoatTripNotClosedCheck
{
    public function handle(Request $request, Closure $next)
    {
        $boatTrips = app(ReadBoatTripRepository::class)->getNotClosedYesterdayOrMore();
        if(!empty($boatTrips)) {
            $request->session()->put('boat-trips_not_closed', $boatTrips);
        }
        return $next($request);
    }
}
