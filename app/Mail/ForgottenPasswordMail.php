<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgottenPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $passwordRessetData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($passwordRessetData)
    {
        $this->passwordRessetData = $passwordRessetData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = url('api/users/forgotPassword/'.$this->passwordRessetData->token);
        return $this->from('bonlinh23195@gmail.com')->view('Mail.forgot_password')->with([
            'email'=>$this->passwordRessetData->email,
            'url' => $url,
        ]);
    }
}
