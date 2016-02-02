<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Aero',
            'email' => 'admin@admin.com',
            'password' => bcrypt('lol123'),
            'remember_token' => str_random(10),
            'admin' => true
        ]);

        factory(App\User::class, 3)->create();
    }
}
