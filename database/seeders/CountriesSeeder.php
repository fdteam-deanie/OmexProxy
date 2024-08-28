<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('countries')->truncate();

        $csvFile = database_path('seeders/data/countries.csv');
        $csv = fopen($csvFile, 'r');
        fgetcsv($csv);

        while (($row = fgetcsv($csv, 1000, ',')) !== false) {
            DB::table('countries')->insert([
                'continent_id' => $row[1],
                'code' => $row[2],
                'name' => $row[3],
                'active' => (bool) $row[4],
                'min_ping' => $row[5],
                'max_ping' => $row[6],
                'min_speed' => $row[7],
                'max_speed' => $row[8],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        fclose($csv);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
