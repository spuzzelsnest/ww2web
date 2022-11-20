<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class countriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //operation
        \DB::table('countries')->insert(
            array(
                array(      
                    'country' => 'Belgium'
                ),
                array(
                    'country' => 'France'
                ),
                array(
                    'country' => 'The Netherlands'
                ),
                array(
                    'country' => 'England'
                ),
                array(
                    'country' => 'Germany'
                ),
                array(
                    'country' => 'Spain'
                ),
            ));
		));
    }
}