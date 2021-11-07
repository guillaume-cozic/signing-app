<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class RegisteredUserController extends Controller
{
    public function create(Request $request)
    {
        $invite = $request->has('invite');
        return view('auth.register', ['invite' => $invite]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'team_name' => 'required_if:invite,false|string|max:255|unique:teams,name',
            'firstname' => 'required|string|max:50',
            'surname' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        Auth::login($user = User::create([
            'firstname' => $request->firstname,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'uuid' => Uuid::uuid4()->toString()
        ]));

        if(isset($request->team_name)) {
            $user->team_name = $request->team_name;
            $user->assignRole(['BUYER', 'RTQ']);
        }

        event(new Registered($user));

        return redirect(RouteServiceProvider::HOME);
    }
}
