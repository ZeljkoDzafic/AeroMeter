<?php

use Illuminate\Database\Seeder;

class StationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positions = [
            'Banja Luka' => [
                'lat' => '44.7785709',
                'lng' => '17.1361267'
            ],
            'Gradiska' => [
                'lat' => '45.1413827',
                'lng' => '17.2405045'
            ],
            'Srbac' => [
                'lat' => '45.0965046',
                'lng' => '17.5078242'
            ],
            'Mrkonjic Grad' => [
                'lat' => '44.4149267',
                'lng' => '17.0813563'
            ],
            'Laktasi' => [
                'lat' => '44.9075524',
                'lng' => '17.2817192'
            ],
            'Bijeljina' => [
                'lat' => '44.760706',
                'lng' => '19.1697901'
            ],
            'Mostar' => [
                'lat' => '43.3395487',
                'lng' => '17.786221'
            ],
            'Sarajevo' => [
                'lat' => '43.3395487',
                'lng' => '17.786221'
            ],
            'Pale' => [
                'lat' => '43.8174955',
                'lng' => '18.5493794'
            ],
            'Prijedor' => [
                'lat' => '44.9828959',
                'lng' => '16.6664094'
            ],
            'Prnjavor' => [
                'lat' => '44.8688679',
                'lng' => '17.6325089'
            ],
            'Novi Sad' => [
                'lat' => '45.2722076',
                'lng' => '19.7794008'
            ],
        ];
        $users = App\User::get();
        foreach($users as $user) {
            $stations = factory(App\Station::class, 2)->make();
            foreach($stations as $station) {
                $position = array_shift($positions);
                $station->lat = $position['lat'];
                $station->lng = $position['lng'];
            }
            $user->stations()->saveMany($stations);
        };


        $tags = App\Tag::get();

        $stations = App\Station::get();
        foreach($stations as $station) {
            $aerometrics = factory(App\Aerometric::class, 20)->make();
            $station->tags()->sync($tags->random(3)->lists('id')->toArray());
            $station->aerometrics()->saveMany($aerometrics);
        }
    }
}
