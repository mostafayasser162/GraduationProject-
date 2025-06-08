<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StartupPaymentRequiredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $startup;
    public $paymentUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($startup)
    {
        $this->startup = $startup;
        $this->paymentUrl = url('/pay-package?startup_id=' . $startup->id . '&package_id=' . $startup->package_id);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Payment Required to Activate Your Account')
            ->view('emails.startup_payment')
            ->with([
                'name' => $this->startup->name,
                'paymentUrl' => $this->paymentUrl,
                'packageId' => $this->startup->package_id
            ]);
    }
}
