<?php


namespace App\Signing\Signing\Domain\Repositories;


use App\Signing\Signing\Domain\Entities\Support;

interface SupportRepository
{
    public function get(string $id):?Support;
    public function save(Support $s):void;
}
