<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class typesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('types')->insert(
            array(
                array(      
                    'type'          => 'Afoto',
                    'description'   => 'Allied Photo'
                ),
                array(
                    'type'          => 'Xfoto',
                    'description'   => 'Axis Photo'
                ),
                array(
                    'type'          => 'Avideo',
                    'description'   => 'Allied Video'
                ),
                array(      
                    'type'          => 'Xvideo',
                    'description'   => 'Axis Video'
                ),
                array(
                    'type'          => 'Aaudio',
                    'description'   => 'Allied Audio'
                ),
                array(
                    'type'          => 'Xaudio',
                    'description'   => 'Axis Audio'
                ),
            ));
    }
}
