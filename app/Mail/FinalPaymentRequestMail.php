<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class FinalPaymentRequestMail extends Mailable
{
    public $factoryName;
    public $requestDescription;
    public $amountDue;

    public function __construct($factoryName, $requestDescription, $amountDue)
    {
        $this->factoryName = $factoryName;
        $this->requestDescription = $requestDescription;
        $this->amountDue = $amountDue;
    }

    public function build()
    {
        return $this->subject('Final Payment Due for Your Order')
                    ->view('emails.final-payment-request')
                    ->with([
                        'factoryName' => $this->factoryName,
                        'requestDescription' => $this->requestDescription,
                        'amountDue' => $this->amountDue,
                    ]);
    }
}
