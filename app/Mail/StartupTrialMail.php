<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StartupTrialMail extends Mailable
{
    use Queueable, SerializesModels;

    public $startup;

    /**
     * Create a new message instance.
     */
    public function __construct($startup)
    {
        $this->startup = $startup;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Welcome to your Free Trial!')
            ->view('emails.startup_trial')
            ->with([
                'name' => $this->startup->name,
                'loginUrl' => url('/login'),
                'trialEnd' => $this->startup->trial_ends_at->toFormattedDateString()
            ]);
    }
}
