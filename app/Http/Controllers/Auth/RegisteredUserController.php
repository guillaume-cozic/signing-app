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
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $invite = $request->has('invite');
        return view('auth.register', ['invite' => $invite]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'team_name' => 'required_if:invite,false|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        Auth::login($user = User::create([
            'firstname' => $request->name,
            'surname' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'uuid' => Uuid::uuid4()->toString()
        ]));

        if(!$request->invite) {
            $user->team_name = $request->team_name;
        }
        event(new Registered($user));

        return redirect(RouteServiceProvider::HOME);
    }
}
