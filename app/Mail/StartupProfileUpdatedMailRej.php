<?php 
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StartupProfileUpdatedMailRej extends Mailable
{
    use Queueable, SerializesModels;

    public $user;



    public function __construct($user)
    {
        $this->user = $user;

    }

    public function build()
    {
        return $this->subject('Sorry Your Update Rquest Has Been Rejected')
                    ->view('emails.startup_update_profile_Rej');
    }
}


