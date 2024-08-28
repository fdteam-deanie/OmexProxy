<?php

namespace App\Console\Commands;

use App\Models\Proxy;
use App\Models\Rate;
use App\Services\ProxyPriceService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class UpdateProxyPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-proxy-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update proxy prices';

    protected ProxyPriceService $proxyPriceService;

    public function __construct(ProxyPriceService $proxyPriceService)
    {
        parent::__construct();
        $this->proxyPriceService = $proxyPriceService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating proxy prices...');

        Proxy::chunk(100, function ($proxies){
            foreach ($proxies as $proxy) {
                $this->info('Updating proxy price for proxy #' . $proxy->id);
                $this->proxyPriceService->updateProxyPrice($proxy);
            }
        });

        $this->info('Done!');
    }
}
