<?php

return [
    'categorias' => [
        'label' => 'Categorias',
        'permissions' => [
            'categorias.create' => 'Crear',
            'categorias.read'   => 'Listar',
            'categorias.update' => 'Editar',
            'categorias.delete' => 'Eliminar',
        ],
    ],

    'cajas' => [
        'label' => 'Cajas',
        'permissions' => [
            'cajas.open'  => 'Abrir',
            'cajas.close' => 'Cerrar',
            'cajas.read'  => 'Listar',
        ],
    ],

    'estadisticas' => [
        'label' => 'Estadisticas',
        'permissions' => [
            'dashboard.show'        => 'Dashboard',
            'ventas_y_cajas.report' => 'Reportes',
        ],
    ],

    'ventas' => [
        'label' => 'Ventas',
        'permissions' => [
            'ventas.create' => 'Crear',
            'ventas.read'   => 'Listar',
            'ventas.update' => 'Editar',
            'ventas.delete' => 'Eliminar',
        ],
    ],

    'gastos' => [
        'label' => 'Gastos',
        'permissions' => [
            'gastos.create' => 'Crear',
            'gastos.read'   => 'Listar',
            'gastos.update' => 'Editar',
            'gastos.delete' => 'Eliminar',
        ],
    ],

    'impuestos' => [
        'label' => 'Impuestos',
        'permissions' => [
            'impuestos.create' => 'Crear',
            'impuestos.update' => 'Editar',
            'impuestos.delete' => 'Eliminar',
        ],
    ],    

    'negocio' => [
        'label' => 'Negocio',
        'permissions' => [
            'negocio.create' => 'Crear',
            'negocio.update' => 'Editar',
        ],
    ],

    'ingredientes' => [
        'label' => 'Ingredientes',
        'permissions' => [
            'ingredientes.create' => 'Crear',
            'ingredientes.update' => 'Editar',
            'ingredientes.delete' => 'Eliminar',
        ],
    ],  

    'inventario' => [
        'label' => 'Inventario',
        'permissions' => [
            'inventario.create' => 'Crear',
            'inventario.read'   => 'Listar',
            'inventario.update' => 'Editar',
            'inventario.delete' => 'Eliminar',
        ],
    ],  

    'mesas' => [
        'label' => 'Mesas',
        'permissions' => [
            'mesas.create' => 'Crear',
            'mesas.update' => 'Editar',
            'mesas.delete' => 'Eliminar',
        ],
    ],  

    'productos' => [
        'label' => 'Productos',
        'permissions' => [
            'productos.create' => 'Crear',
            'productos.update' => 'Editar',
            'productos.delete' => 'Eliminar',
        ],
    ],  

    'promociones' => [
        'label' => 'Promociones',
        'permissions' => [
            'promociones.create' => 'Crear',
            'promociones.update' => 'Editar',
            'promociones.delete' => 'Eliminar',
        ],
    ],  

    'roles' => [
        'label' => 'Roles',
        'permissions' => [
            'roles.create' => 'Crear',
            'roles.read'   => 'Listar',
            'roles.update' => 'Editar',
            'roles.delete' => 'Eliminar',
        ],
    ],  

    'usuarios' => [
        'label' => 'Usuarios',
        'permissions' => [
            'usuarios.create' => 'Crear',
            'usuarios.update' => 'Editar',
            'usuarios.delete' => 'Eliminar',
        ],
    ],
];