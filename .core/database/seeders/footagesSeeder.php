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
                        'operationId' => '2',
                        'info'        => 'After the retreat of the British, the village was heavenly damaged and littered with all what could not be taken over the channel.',
                        'lat'         => '51.04112625',
                        'lng'         => '2.40394855',
                        'place'       => 'Duinkerke',
                        'countryId'   => '1',
                        'date'        => '1940-06-05',
                        'sourceId'    => '1',
                        'remark'      => '',
                        'published'   => '1'
                ),
        ));
    }
}
