<?php


namespace App\Signing\Shared\Entities;


interface HasState
{
    public function getState():State;
}
