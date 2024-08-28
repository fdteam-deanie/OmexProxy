<?php

namespace App\Console\Commands;

use App\Models\Pivot\UserProxy;
use App\Models\UnlimitedSubscription;
use App\Services\ProxyHost\ProxyHostWebhooksService;
use Illuminate\Console\Command;

class ProcessUnlimitedSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process-unlimited-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process unlimited subscriptions';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $unlimitedSubscriptionIds = UnlimitedSubscription::where('expired_at', '<', now())
            ->active()->get()->pluck('id')->toArray();

        UnlimitedSubscription::where('expired_at', '<', now())
            ->active()
            ->update([
                'active' => false
            ]);

        $userProxies = UserProxy::whereIn('unlimited_subscription_id', $unlimitedSubscriptionIds)
            ->where('is_paid', true)
            ->get();

        foreach ($userProxies as $userProxy) {
            $proxy = $userProxy->proxy;
            $user = $userProxy->user;

            $provider = $proxy->getProvider();
            if(!is_null($provider))
            {
                $provider->revokeUserAccessToProxy($user, $proxy);
            }


            $userProxy->update([
                'is_paid' => false
            ]);
        }
    }
}
