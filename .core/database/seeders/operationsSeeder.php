<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class operationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //operation
        \DB::table('operations')->insert(
            array(
                array(      
                    'operation' => ''
                ),
                array(
                    'operation' => 'Fall Gelb'
                ),
                array(
                    'operation' => 'Occupation'
                ),
                array(
                    'operation' => 'Overlord'
                ),
                array(
                    'operation' => 'Battle for Normandy'
                ),
                array(
                    'operation' => 'Market Garden'
                ),
                array(
                    'operation' => 'Battle of the Bulge'
                ),
            )
        );
    }
}
