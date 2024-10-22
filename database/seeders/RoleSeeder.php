<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'Administrador']);
        $role2 = Role::create(['name' => 'Ventas']);
        $role3 = Role::create(['name' => 'Compras']);
        $role4 = Role::create(['name' => 'Finanzas']);

        Permission::create(['name' => 'admin.dashboardAdmin'])->assignRole($role1);
        Permission::create(['name' => 'ventas.dashboardVentas'])->assignRole($role2);
        Permission::create(['name' => 'compras.dashboardCompras'])->assignRole($role3);
        Permission::create(['name' => 'finanzas.dashboardFinanzas'])->assignRole($role4);


    }
}
