<?php

namespace App\Console\Commands;

use App\ProxyProviders\Webshare\WebshareRotatingProxyProvider;
use App\ProxyProviders\Webshare\WebshareStaticProxyProvider;
use Illuminate\Console\Command;

class ImportRotatingProxiesFromWebshare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webshare-rotating-proxies:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $provider = new WebshareRotatingProxyProvider();
        $provider->importProxies();
    }
}
