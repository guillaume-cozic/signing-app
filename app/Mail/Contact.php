<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $message,
        public string $name,
        public string $email,
        public string $phone,
    ){}

    public function build()
    {
        return $this->view('contact.mail', [
            'messageMail' => $this->message,
            'name' => $this->message,
            'email' => $this->email,
            'phone' => $this->phone,
        ])
            ->subject('Prise de contact WellSail : '.$this->name)
            ->to('guillaume.cozic@gmail.com')
            ->replyTo($this->email);
    }
}
