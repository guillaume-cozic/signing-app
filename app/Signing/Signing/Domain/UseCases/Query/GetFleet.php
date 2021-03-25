<?php


namespace App\Signing\Signing\Domain\UseCases\Query;


use App\Signing\Signing\Domain\Entities\Dto\FleetDto;

interface GetFleet
{
    public function execute(string $fleeId):FleetDto;
}
