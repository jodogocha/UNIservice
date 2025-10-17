<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Listar todos los roles
     */
    public function index()
    {
        $roles = Role::withCount('users', 'permissions')
            ->orderBy('nombre')
            ->paginate(15);

        return view('roles.index', compact('roles'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $permissions = Permission::orderBy('nombre')->get();

        return view('roles.create', compact('permissions'));
    }

    /**
     * Guardar nuevo rol
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:roles,nombre',
            'slug' => 'required|string|max:255|unique:roles,slug',
            'descripcion' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'nombre.required' => 'El nombre del rol es obligatorio',
            'nombre.unique' => 'Este nombre de rol ya existe',
            'slug.required' => 'El identificador (slug) es obligatorio',
            'slug.unique' => 'Este identificador ya existe',
        ]);

        // Crear rol
        $rol = Role::create([
            'nombre' => $validated['nombre'],
            'slug' => $validated['slug'],
            'descripcion' => $validated['descripcion'] ?? null,
        ]);

        // Asignar permisos
        if (isset($validated['permissions'])) {
            $rol->permissions()->sync($validated['permissions']);
        }

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol creado exitosamente');
    }

    /**
     * Mostrar detalle del rol
     */
    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);

        return view('roles.show', compact('role'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('nombre')->get();
        $role->load('permissions');

        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Actualizar rol
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:roles,nombre,' . $role->id,
            'slug' => 'required|string|max:255|unique:roles,slug,' . $role->id,
            'descripcion' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'nombre.required' => 'El nombre del rol es obligatorio',
            'nombre.unique' => 'Este nombre de rol ya existe',
            'slug.required' => 'El identificador (slug) es obligatorio',
            'slug.unique' => 'Este identificador ya existe',
        ]);

        // Actualizar rol
        $role->update([
            'nombre' => $validated['nombre'],
            'slug' => $validated['slug'],
            'descripcion' => $validated['descripcion'] ?? null,
        ]);

        // Actualizar permisos
        if (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        } else {
            $role->permissions()->detach();
        }

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol actualizado exitosamente');
    }

    /**
     * Eliminar rol
     */
    public function destroy(Role $role)
    {
        // No permitir eliminar roles con usuarios asignados
        if ($role->users()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un rol que tiene usuarios asignados');
        }

        // No permitir eliminar roles del sistema
        if (in_array($role->slug, ['admin', 'encargado-lab', 'funcionario'])) {
            return back()->with('error', 'No se puede eliminar un rol del sistema');
        }

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol eliminado exitosamente');
    }
}