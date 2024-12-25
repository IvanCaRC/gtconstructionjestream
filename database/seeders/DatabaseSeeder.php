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
         \App\Models\User::factory(3)->create();

        $this->call(RoleSeeder::class);
        $this->call(familiaSeeder::class);
        $this->call(ProveedoresSeeder::class);
        
         \App\Models\User::factory()->create([
             'name' => 'Marisela',
             'first_last_name' => 'Gonzalez',
             'second_last_name' => 'Torres',
             'email' => 'administracion@gtcgroup.com.mx',
             'number' => '+52 12291750175',
             'status' => true,
             'estadoEliminacion' => false,
             'password' => Hash::make('gtconstructions'),
         ])->assignRole('Administrador');
         
         
    }
}
