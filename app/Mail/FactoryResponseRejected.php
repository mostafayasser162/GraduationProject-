<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\FactoryResponse;

class FactoryResponseRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $response;

    public function __construct(FactoryResponse $response)
    {
        $this->response = $response;
    }

    public function build()
    {
        return $this->subject('Your Factory Offer Was Rejected by system')
            ->view('emails.factory_response_rejected');
    }
}
