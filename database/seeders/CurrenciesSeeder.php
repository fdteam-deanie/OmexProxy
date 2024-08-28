<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('currencies')->insert([
            [ 'name' => 'usd', 'exchange_rate' => 1 ],
            [ 'name' => 'bitcoin', 'exchange_rate' => 1 ],
            [ 'name' => 'litecoin', 'exchange_rate' => 1 ]
        ]);
    }
}
