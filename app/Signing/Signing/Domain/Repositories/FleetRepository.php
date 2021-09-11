<?php


namespace App\Signing\Signing\Domain\Repositories;


use App\Signing\Signing\Domain\Entities\Fleet;
use App\Signing\Signing\Domain\Entities\FleetState;

interface FleetRepository
{
    public function get(string $id):?Fleet;
    public function getByName(string $name):?Fleet;
    public function save(FleetState $s):void;
}
