<?php


namespace App\Http\Controllers;


use App\Http\Requests\ContactRequest;
use App\Mail\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function showContact()
    {
        $name = Auth::user()->surname;
        $email = Auth::user()->email;
        return view('contact.contact-us', [
            'name' => $name,
            'email' => $email,
        ]);
    }

    public function processSendEmail(ContactRequest $request)
    {
        Mail::send(new Contact(
            $request->input('message'),
            $request->input('name'),
            $request->input('email'),
            $request->input('phone'),
        ));
        session()->flash('contact_ok',  "L'email a bien été envoyé");
        return redirect()->back();
    }
}
