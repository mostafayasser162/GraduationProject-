<?php

namespace App\Console\Commands;

use App\Enums\FactoryResponse\Status;
use Illuminate\Console\Command;
use App\Models\FactoryResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\FactoryResponseRejected;

class RejectOldFactoryResponses extends Command
{
    protected $signature = 'factory-responses:reject-old';
    protected $description = 'Reject factory responses that are pending for more than 48 hours';

    public function handle()
    {
        $cutoff = Carbon::now()->subHours(48);

        $responses = FactoryResponse::where('status', 'pending')
            ->where('created_at', '<=', $cutoff)
            ->with('factory')   
            ->get();

        $count = 0;

        foreach ($responses as $response) {
            $response->update([
                'status' => Status::REJECTED(),
            ]);

            // إرسال الإيميل للـ Factory
            if ($response->factory && $response->factory->email) {
                Mail::to($response->factory->email)->send(new FactoryResponseRejected($response));
            }
        }

        $this->info("$count factory responses have been rejected and startups notified.");
    }
}
