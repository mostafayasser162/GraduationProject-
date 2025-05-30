<?php

namespace App\Mail;

use App\Models\Deal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepositPaidNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $deal;

    public function __construct(Deal $deal)
    {
        $this->deal = $deal;
    }

    public function build()
    {
        return $this->subject('Startup has paid the deposit')
            ->markdown('emails.deposit_paid')
            ->with([
                'factoryName' => $this->deal->factory->name,
                'startupName' => $this->deal->request->startup->name ?? 'Startup',
                'dealId' => $this->deal->id,
                'price' => $this->deal->price,
            ]);
    }
}
