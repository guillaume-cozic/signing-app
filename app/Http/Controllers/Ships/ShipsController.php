<?php


namespace App\Http\Controllers\Ships;


use Tests\TestCase;

class ShipsController extends TestCase
{
    public function listShips()
    {
        return view('ships.list');
    }
}
