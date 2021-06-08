<?php


namespace App\Http\Controllers\Api\V1\Fleet;


use App\Http\Controllers\Controller;
use App\Signing\Shared\Services\ContextService;
use App\Signing\Signing\Domain\UseCases\GetFleetsList;

class RentController extends Controller
{
    public function __construct()
    {
        app(ContextService::class)->set(1);
    }

    public function showRent()
    {
        return view('embed.rent-fleet');
    }

    public function showRents(GetFleetsList $getFleetsList)
    {
        $fleets = $getFleetsList->execute();
        return view('embed.rent-fleets', ['fleets' => $fleets]);
    }
}

