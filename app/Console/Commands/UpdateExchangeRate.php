<?php

namespace App\Console\Commands;

use App\Enums\CoinFullName;
use App\Enums\CoinType;
use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class UpdateExchangeRate extends Command
{
    const API_URL = 'https://api.coingecko.com/api/v3/simple/price?ids=bitcoin%2Clitecoin&vs_currencies=usd';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currencies:rate:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update exchange rate of currencies';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $response = file_get_contents(env('CURRENCY_EXCHANGE_URL', self::API_URL));
        $responseArray = json_decode($response, true);
        $currencies = Currency::all();
        foreach ($currencies as $currency) {
            if ($currency->name === Currency::USD_CODE) {
                continue;
            }
            $currency->exchange_rate = $responseArray[$currency->name][Currency::USD_CODE];
            $currency->save();
        }
    }
}
