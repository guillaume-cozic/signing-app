<?php


namespace App\Signing\Signing\Domain\Repositories;


use App\Signing\Signing\Domain\Entities\Fleet;

interface FleetRepository
{
    public function get(string $id):?Fleet;
    public function save(Fleet $s):void;
}
