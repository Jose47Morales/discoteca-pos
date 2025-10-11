<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    /**
     * Listar todos los usuarios.
     */
    public function index()
    {
        $users = User::with('roles', 'permissions')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Crear nuevo usuario.
     */
    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('users.create', compact('roles', 'permissions'));
    }

    /**
     * Guardar nuevo usuario.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->filled('roles')) {
            $user->syncRoles($request->roles); 
        }

        $user->load('roles', 'permissions');
    
        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Detalle de un usuario.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Editar un usuario.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        $userPermissions = $user->getDirectPermissions()->pluck('name')->toArray();

        return view('users.edit', compact('user', 'roles', 'permissions', 'userRoles', 'userPermissions'));
    }

    /**
     * Actualizar un usuario.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }   

        $user->save();

        if ($request->filled('roles')) {
            $user->syncRoles($request->roles);
            
        }
        
        $user->load('roles', 'permissions');
        
        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Eliminar un usuario.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
