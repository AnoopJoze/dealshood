<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Locality;
use Illuminate\Support\Str;

class LocalitySeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | India → Kerala
        |--------------------------------------------------------------------------
        */

        $india = Locality::firstOrCreate(
            ['slug' => Str::slug('India')],
            [
                'name' => 'India',
                'type' => 'country',
            ]
        );

        $kerala = Locality::firstOrCreate(
            ['slug' => Str::slug('Kerala')],
            [
                'name' => 'Kerala',
                'type' => 'state',
                'parent_id' => $india->id,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Major Cities in Kerala
        |--------------------------------------------------------------------------
        */

        $kochi = Locality::firstOrCreate(
            ['slug' => Str::slug('Kochi')],
            [
                'name' => 'Kochi',
                'type' => 'city',
                'parent_id' => $kerala->id,
            ]
        );

        $thiruvananthapuram = Locality::firstOrCreate(
            ['slug' => Str::slug('Thiruvananthapuram')],
            [
                'name' => 'Thiruvananthapuram',
                'type' => 'city',
                'parent_id' => $kerala->id,
            ]
        );

        $kozhikode = Locality::firstOrCreate(
            ['slug' => Str::slug('Kozhikode')],
            [
                'name' => 'Kozhikode',
                'type' => 'city',
                'parent_id' => $kerala->id,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Kochi Areas
        |--------------------------------------------------------------------------
        */
        $this->createAreas($kochi, [
            'Edappally',
            'Vyttila',
            'Kaloor',
            'Palarivattom',
            'Aluva',
            'Fort Kochi',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Thiruvananthapuram Areas
        |--------------------------------------------------------------------------
        */
        $this->createAreas($thiruvananthapuram, [
            'Kowdiar',
            'Kazhakoottam',
            'Pattom',
            'Vazhuthacaud',
            'Sreekariyam',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Kozhikode Areas
        |--------------------------------------------------------------------------
        */
        $this->createAreas($kozhikode, [
            'Mavoor Road',
            'Kallai',
            'Beypore',
            'Ramanattukara',
            'Eranchipalam',
        ]);
    }

    private function createAreas($city, array $areas)
    {
        foreach ($areas as $area) {
            Locality::firstOrCreate(
                [
                    'slug' => Str::slug($area . '-' . $city->name),
                ],
                [
                    'name' => $area,
                    'type' => 'area',
                    'parent_id' => $city->id,
                ]
            );
        }
    }
}
