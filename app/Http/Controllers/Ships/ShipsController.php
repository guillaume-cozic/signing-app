<?php


namespace App\Http\Controllers\Ships;


use App\Http\Requests\Domain\Supports\AddSupportRequest;
use App\Signing\Signing\Domain\UseCases\AddSupport;
use Tests\TestCase;

class ShipsController extends TestCase
{
    public function listShips()
    {
        return view('ships.list');
    }

    public function add(AddSupportRequest $request, AddSupport $addSupport)
    {
        $name = $request->input('name', null);
        $total = $request->input('total_available', null);
        $state = $request->input('state', false);
        $addSupport->execute($name, '', $total, $state);
    }
}
