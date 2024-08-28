<?php

namespace App\Console\Commands;

use App\Models\Pivot\UserProxy;
use App\Services\ProxyHost\ProxyHostWebhooksService;
use Illuminate\Console\Command;

class ProcessUserProxyAccesses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process-user-proxy-accesses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userProxies = UserProxy::where('expired_at', '<', now())
            ->where('is_paid', true)
            ->where('unlimited_subscription_id', null)
            ->get();

        foreach ($userProxies as $userProxy) {
            $proxy = $userProxy->proxy;
            if (is_null($proxy)) {
                continue;
            }
            $user = $userProxy->user;

            $provider = $proxy->getProvider();
            if(!empty($provider))
            {
                $provider->revokeUserAccessToProxy($user, $proxy);
            }

            $userProxy->update([
                'is_paid' => false
            ]);
        }
    }
}
