<?php


namespace App\Http\Controllers\Api\V1\Fleet;


use App\Http\Controllers\Controller;

class RentController extends Controller
{
    public function showRent()
    {
        return view('embed.one-fleet');
    }
}

