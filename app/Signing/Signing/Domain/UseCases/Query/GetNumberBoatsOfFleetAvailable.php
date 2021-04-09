<?php


namespace App\Signing\Signing\Domain\UseCases\Query;


interface GetNumberBoatsOfFleetAvailable
{
    public function execute():array;
}
