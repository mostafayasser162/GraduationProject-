<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StartupRejectedResponseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $startupName;
    public $requestDescription;

    public function __construct($startupName, $requestDescription)
    {
        $this->startupName = $startupName;
        $this->requestDescription = $requestDescription;
    }

    public function build()
    {
        return $this->subject('Your offer has been rejected')
            ->view('emails.response_rejected')
            ->with([
                'startupName' => $this->startupName,
                'requestDescription' => $this->requestDescription,
            ]);
    }
}
