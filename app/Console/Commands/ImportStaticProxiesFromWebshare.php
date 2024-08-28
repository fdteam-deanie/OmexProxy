<?php

namespace App\Console\Commands;

use App\ProxyProviders\Webshare\WebshareStaticProxyProvider;
use Illuminate\Console\Command;

class ImportStaticProxiesFromWebshare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webshare-static-proxies:import';

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
        $provider = new WebshareStaticProxyProvider();
        $provider->importProxies();
    }
}
