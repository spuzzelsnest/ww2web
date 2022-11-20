<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class sourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('sources')->insert(
            array(
                array(
                    'source' => ''
                ),
                array(
                    'source' => 'Bundesarchiv'
                ),
                array(
                    'source' => 'US Signal Corps'
                ),
            )
        );
    }
}
