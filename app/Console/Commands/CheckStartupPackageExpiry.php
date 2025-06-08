<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Startup;
use Carbon\Carbon;

class CheckStartupPackageExpiry extends Command
{
    protected $signature = 'startups:check-expiry';

    protected $description = 'Check and update startups whose package/trial period has expired';

    public function handle()
    {
        $now = Carbon::now();

        $expiredStartups = Startup::whereNotNull('package_ends_at')
            ->where('package_ends_at', '<', $now)
            ->where('status', 'APPROVED') // only downgrade active startups
            ->get();

        foreach ($expiredStartups as $startup) {
            $startup->update([
                'status' => 'HOLD',
            ]);

            // Optional: Send a notification or email
            // Notification::route(...)->notify(new PackageExpiredNotification($startup));

            $this->info("Startup ID {$startup->id} moved to HOLD due to expired package.");
        }

        $this->info('Checked all startups for expired packages.');
    }
}
