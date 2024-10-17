<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

    

         \App\Models\User::factory(30)->create();


         \App\Models\User::factory()->create([
             'name' => 'Miguel Angel',
             'first_last_name' => 'Gomez',
             'second_last_name' => 'Romero',
             'email' => 'MiguelAngelp@gmail.com',
             'number' => '9516105949',
             'status' => true,
             'password' => Hash::make('miguelangel'),
         ]);
         
    }
}
