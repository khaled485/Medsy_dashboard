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
        $user= App\User::create([
        'name' => 'admin',
        'email' => 'khaledozile485@hotmail.com',
        'password' => bcrypt('kh@led22580825000')

            
        ]);

        $user->attachRole('super_admin');
        
    }
}
