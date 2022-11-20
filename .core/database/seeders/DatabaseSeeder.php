<?php

namespace Database\Seeders;
 
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            typesSeeder::class,
            operationsSeeder::class,
            countriesSeeder::class,
            sourcesSeeder::class,
            footagesSeeder::class,
    }
}
