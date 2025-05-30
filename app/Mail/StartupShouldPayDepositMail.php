<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StartupShouldPayDepositMail extends Mailable
{
    use Queueable, SerializesModels;

    public $startupName;
    public $depositAmount;

    public function __construct($startupName, $depositAmount)
    {
        $this->startupName = $startupName;
        $this->depositAmount = $depositAmount;
    }

    public function build()
    {
        return $this->subject('Please Pay Your Deposit')
                    ->view('emails.startup_deposit')
                    ->with([
                        'startupName' => $this->startupName,
                        'depositAmount' => $this->depositAmount,
                    ]);
    }
}
