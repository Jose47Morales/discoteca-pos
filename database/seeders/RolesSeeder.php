<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run()
    {
       
        // Permisos de categorías
        Permission::create(['name' => 'categorias.create']);
        Permission::create(['name' => 'categorias.read']);
        Permission::create(['name' => 'categorias.update']);
        Permission::create(['name' => 'categorias.delete']);

        // Permisos de caja
        Permission::create(['name' => 'cajas.open']);
        Permission::create(['name' => 'cajas.read']);
        Permission::create(['name' => 'cajas.close']);

        // Permisos de Estadisticas
        Permission::create(['name' => 'dashboard.show']);
        Permission::create(['name' => 'ventas_y_cajas.report']);

        // Permisos de ventas
        Permission::create(['name' => 'ventas.create']);
        Permission::create(['name' => 'ventas.read']);
        Permission::create(['name' => 'ventas.update']);
        Permission::create(['name' => 'ventas.delete']);

        // Permisos de gastos
        Permission::create(['name' => 'gastos.create']);
        Permission::create(['name' => 'gastos.read']);
        Permission::create(['name' => 'gastos.update']);
        Permission::create(['name' => 'gastos.delete']);

        // Permisos de impuestos
        Permission::create(['name' => 'impuestos.create']);
        Permission::create(['name' => 'impuestos.update']);
        Permission::create(['name' => 'impuestos.delete']);

        // Permisos de negocio
        Permission::create(['name' => 'negocio.create']);
        Permission::create(['name' => 'negocio.update']);

        // Permisos de ingredientes
        Permission::create(['name' => 'ingredientes.create']);
        Permission::create(['name' => 'ingredientes.update']);
        Permission::create(['name' => 'ingredientes.delete']);

        // Permisos de Inventario
        Permission::create(['name' => 'inventario.create']);
        Permission::create(['name' => 'inventario.read']);
        Permission::create(['name' => 'inventario.update']);
        Permission::create(['name' => 'inventario.delete']);

        // Permisos de mesas
        Permission::create(['name' => 'mesas.create']);
        Permission::create(['name' => 'mesas.update']);
        Permission::create(['name' => 'mesas.delete']);

        // Permisos de productos
        Permission::create(['name' => 'productos.create']);
        Permission::create(['name' => 'productos.update']);
        Permission::create(['name' => 'productos.delete']);

        // Permisos de promociones
        Permission::create(['name' => 'promociones.create']);
        Permission::create(['name' => 'promociones.update']);
        Permission::create(['name' => 'promociones.delete']);

        // Permisos de roles
        Permission::create(['name' => 'roles.create']);
        Permission::create(['name' => 'roles.read']);
        Permission::create(['name' => 'roles.update']);
        Permission::create(['name' => 'roles.delete']);

        // Usuarios
        Permission::create(['name' => 'usuarios.create']);
        Permission::create(['name' => 'usuarios.update']);
        Permission::create(['name' => 'usuarios.delete']);


        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $cajero = Role::create(['name' => 'cajero']);
        $cajero->givePermissionTo([
            'cajas.open',
            'cajas.close',
            'cajas.read',
            'gastos.create',
            'gastos.read',
            'gastos.update',
            'gastos.delete',
            'inventario.create',
            'inventario.update',
            'inventario.read',
            'mesas.create',
            'mesas.update',
            'ventas.create',
            'ventas.read',
            'ventas.update',
            'ventas.delete',
        ]);

        $vendedor = Role::create(['name' => 'vendedor']);
        $vendedor->givePermissionTo([
            'ventas.create',
            'ventas.read',
            'ventas.update',
        ]);
    }
}
