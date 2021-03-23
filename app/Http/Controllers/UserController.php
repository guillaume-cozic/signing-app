<?php


namespace App\Http\Controllers;


use App\Http\Requests\Domain\Account\UserUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function profile()
    {
        return view('user.profile', [
            'user' => Auth::user()->toArray()
        ]);
    }

    public function update(UserUpdateRequest $request)
    {
        $user = Auth::user();
        $user->firstname = $request->input('firstname');
        $user->surname = $request->input('surname');
        $user->email = $request->input('email');

        if ($request->hasFile('file')) {
            $avatar = $request->file('file');
            $filename = 'avatar.'.$avatar->getClientOriginalExtension();
            $save_path = storage_path().'/app/public/users/id/'.$user->id.'/';
            $public_path = '/storage/users/id/'.$user->id.'/'.$filename;

            File::makeDirectory($save_path, $mode = 0755, true, true);

            Image::make($avatar)->resize(300, 300)->save($save_path.$filename);

            $user->avatar = $public_path;
        }

        $user->save();
        return redirect()->route('user.profile');
    }

}
