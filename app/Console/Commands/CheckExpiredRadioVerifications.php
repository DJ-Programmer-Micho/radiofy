<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\RadioVerification;

class CheckExpiredRadioVerifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'radio:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check expired radio verifications and update the verified flag to 0 for expired radios.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Retrieve all RadioVerification records that have expired
        $expiredVerifications = RadioVerification::where('expire_date', '<', $now)->get();

        foreach ($expiredVerifications as $verification) {
            // Assuming you have a polymorphic relationship defined on RadioVerification
            // as radioable(), which returns either a RadioConfiguration or ExternalRadioConfiguration.
            $radio = $verification->radioable;

            if ($radio && $radio->verified != 0) {
                $radio->update(['verified' => 0]);
                $this->info("Radio ID {$radio->id} marked as not verified.");
            }
        }

        $this->info('Expired radio verifications processed successfully.');
    }
}
