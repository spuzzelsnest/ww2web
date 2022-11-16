<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class footagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('footages')->insert(

            array(
                    array(
                        'typeid'      => '1',
                        'name'        => 'duinkerke06',
                        'shortdesc'   => 'Fall Gelb',
                        'info'        => 'After the retreat of the British, the village was heavenly damaged and littered with all what could not be taken over the channel.',
                        'lat'         => '51.04112625',
                        'lng'         => '2.40394855',
                        'place'       => 'Duinkerke',
                        'country'     => 'France',
                        'date'        => '1940-06-05',
                        'source'      => '',
                        'remark'     => '',
                        'published'   => '1'
                ),
        ));

    }
}
