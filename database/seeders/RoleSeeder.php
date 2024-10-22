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
        //Permiso unico de Administrador
        Permission::create(['name' => 'admin.dashboardAdmin'])->assignRole($role1);
        //Permiso unico de ventas
        Permission::create(['name' => 'ventas.dashboardVentas'])->assignRole($role2);
        //Permiso unico de Compras
        Permission::create(['name' => 'compras.dashboardCompras'])->assignRole($role3);
        //Permiso unico de Finanzas
        Permission::create(['name' => 'finanzas.dashboardFinanzas'])->assignRole($role4);
        //Permisos Compartidos:
        //Administracio  y Compras
        $permission1 = Permission::create(['name' => 'compras.collapsed']);
        $roles = Role::whereIn('name', ['Administrador', 'Compras'])->get();
        foreach ($roles as $role) {
            $role->givePermissionTo($permission1);
        }
        //Administracion y Ventas
        $permission2 = Permission::create(['name' => 'ventas.collapsed']);
        $roles = Role::whereIn('name', ['Administrador', 'Ventas'])->get();
        foreach ($roles as $role) {
            $role->givePermissionTo($permission2);
        }
        //Administracion y Finanzas
        $permission3 = Permission::create(['name' => 'finanzas.collapsed']);
        $roles = Role::whereIn('name', ['Administrador', 'Finanzas'])->get();
        foreach ($roles as $role) {
            $role->givePermissionTo($permission3);
        }
    }
}
