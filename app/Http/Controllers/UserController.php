<?php


namespace App\Http\Controllers;


class UserController extends Controller
{
    public function profile()
    {
        return view('user.profile');
    }
}
