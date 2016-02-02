<?php

use Illuminate\Database\Seeder;

class ConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config = [
            'aero' => [
                'lat' => '44.7785709',
                'lng' => '17.1361267',
                'zoom_level' => '7',
                'default_marker' => '1'
            ],
            'aerometrics' => [
                'properties' => [
                    'temperature' => ['min' => 1, 'max' => 99, 'color' => '#09476d'],
                    'pressure' => ['min' => 1, 'max' => 99, 'color' => '#afffed'],
                    'altitude' => ['min' => 1, 'max' => 99, 'color' => '#89c43c'],
                    'insolation' => ['min' => 1, 'max' => 99, 'color' => '#9d8fe0'],
                    'humidity' => ['min' => 1, 'max' => 99, 'color' => '#16e5a7'],
                    'co' => ['min' => 1, 'max' => 99, 'color' => '#fff699'],
                    'co2' => ['min' => 1, 'max' => 99, 'color' => '#a1d0f4'],
                    'methane' => ['min' => 1, 'max' => 99, 'color' => '#dfe524'],
                    'butane' => ['min' => 1, 'max' => 99, 'color' => '#1f58dd'],
                    'propane' => ['min' => 1, 'max' => 99, 'color' => '#e0971a'],
                    'benzene' => ['min' => 1, 'max' => 99, 'color' => '#fce25f'],
                    'ethanol' => ['min' => 1, 'max' => 99, 'color' => '#de30e8'],
                    'alcohol' => ['min' => 1, 'max' => 99, 'color' => '#559cdb'],
                    'hydrogen' => ['min' => 1, 'max' => 99, 'color' => '#7ae8f9'],
                    'ozone' => ['min' => 1, 'max' => 99, 'color' => '#2028c9'],
                    'cng' => ['min' => 1, 'max' => 99, 'color' => '#84f992'],
                    'lpg' => ['min' => 1, 'max' => 99, 'color' => '#db5e75'],
                    'coal_gas' => ['min' => 1, 'max' => 99, 'color' => '#fcc2c4'],
                    'smoke' => ['min' => 1, 'max' => 99, 'color' => '#d68b22']
                ]
            ]
        ];

        foreach(array_dot($config) as $key => $value) {
            $c = new App\Config();
            $c->key = $key;
            $c->value = $value;
            $c->save();
        }
    }
}
