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

        Storage::deleteDirectory('users');
        Storage::makeDirectory('users');
         \App\Models\User::factory(30)->create();


         \App\Models\User::factory()->create([
             'name' => 'Marisela',
             'first_last_name' => 'Gonzalez',
             'second_last_name' => 'Torres',
             'email' => 'administracion@gtcgroup.com.mx',
             'number' => '+52 12291750175',
             'status' => true,
             'password' => Hash::make('gtconstructions'),
         ]);
         
    }
}
