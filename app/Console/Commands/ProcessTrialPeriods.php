<?php

namespace App\Console\Commands;

use App\Exceptions\InsufficientBalanceException;
use App\Models\TrialPeriod;
use App\Models\UnlimitedSubscription;
use App\Services\TrialPeriodService;
use Illuminate\Console\Command;

class ProcessTrialPeriods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process-trial-periods';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * @throws InsufficientBalanceException
     */
    public function handle()
    {
        TrialPeriod::where('expired_at', '<', now())
            ->active()
            ->chunk(100, function ($trialPeriods) {
                foreach ($trialPeriods as $trialPeriod) {
                    $this->info('Deactivating trial period for user: '.$trialPeriod->user->id);
                    $service = new TrialPeriodService($trialPeriod->user);
                    $service->deactivateTrialPeriod($trialPeriod);
                }
            });
    }
}
