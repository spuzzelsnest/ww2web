<?php

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
        // $this->call(UsersTableSeeder::class);
        $this->call(typesSeeder::class);
        $this->call(operationsSeeder::class);
        $this->call(countriesSeeder::class);
        $this->call(sourcesSeeder::class);
        $this->call(footagesSeeder::class);
    }
}
