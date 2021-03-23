<?php


namespace App\Http\Controllers;


use App\Http\Requests\Domain\Account\UserUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

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
        $user->save();
        return redirect()->route('user.profile');
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $currentUser = Auth::user();
            $avatar = $request->file('file');
            $filename = 'avatar.'.$avatar->getClientOriginalExtension();
            $save_path = storage_path().'/users/id/'.$currentUser->id.'/uploads/images/avatar/';
            $path = $save_path.$filename;
            $public_path = '/images/profile/'.$currentUser->id.'/avatar/'.$filename;

            File::makeDirectory($save_path, $mode = 0755, true, true);
            Image::make($avatar)->resize(300, 300)->save($save_path.$filename);

            $currentUser->profile->avatar = $public_path;
            $currentUser->profile->save();

            return response()->json(['path' => $path], 200);
        }
        return response()->json(false, 200);
    }
}
