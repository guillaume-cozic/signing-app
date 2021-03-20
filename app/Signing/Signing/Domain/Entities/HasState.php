<?php


namespace App\Signing\Signing\Domain\Entities;


interface HasState
{
    public function getState():State;
}
