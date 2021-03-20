<?php


namespace App\Signing\Signing\Domain\UseCases\System\Impl;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\UseCases\System\CreateFleetWhenTeamCreated;

class CreateFleetWhenTeamCreatedImpl implements CreateFleetWhenTeamCreated
{
    private array $fleets = [
        'hobie cat 15'
    ];

    public function execute()
    {
        foreach ($this->fleets as $fleet) {
            (new Fleet(new Id(), 0))->create($fleet, '');
        }
    }
}
