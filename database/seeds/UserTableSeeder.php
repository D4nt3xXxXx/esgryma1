<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            "name" => "Administrador",
            "email" => "admin@esgryma.com",
            "password" => \Illuminate\Support\Facades\Hash::make("admin123")
        ]);
    }
}
