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
                    'operation' => 'Fall Gelb'
                ),
                array(
                    'operation' => 'Occupation'
                ),
                array(
                    'operation' => 'Jubilee'
                ),
                array(
                    'operation' => 'Overlord'
                ),
                array(
                    'operation' => 'Battle for Normandy'
                ),
                array(
                    'operation' => 'Liberation of France'
                ),
                array(
                    'operation' => 'Liberation of Belgium'
                ),
                array(
                    'operation' => 'Market Garden'
                ),
                array(
                    'operation' => 'Battle of the Bulge'
                ),
                array(
                    'operation' => 'Phoney War'
                ),
                array(
                    'operation' => 'Cobra'
                ),
                array(
                    'operation' => 'Battle for Germany'
                ),
                array(
                    'operation' => 'Battle of Berlin'
                ),
                array(
                    'operation' => 'Armistice'
                ),              
                array(
                    'operation' => 'Falaise Gab'
                ),
                array(
                    'operation' => 'Liberation of The Netherlands'
                ),
                array(
                    'operation' => 'Post-War'
                ),             

            )
        );
    }
}
