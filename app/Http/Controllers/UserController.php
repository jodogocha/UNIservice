<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Dependencia;
use App\Models\UnidadAcademica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Listar todos los usuarios
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'dependencia', 'unidadAcademica']);

        // Filtro por estado
        if ($request->has('estado') && $request->estado !== '') {
            $query->where('activo', $request->estado);
        }

        // Filtro por rol
        if ($request->has('rol') && $request->rol !== '') {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->rol);
            });
        }

        // Búsqueda por nombre, apellido o email
        if ($request->has('buscar') && $request->buscar !== '') {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('name', 'LIKE', "%{$buscar}%")
                  ->orWhere('apellido', 'LIKE', "%{$buscar}%")
                  ->orWhere('email', 'LIKE', "%{$buscar}%")
                  ->orWhere('documento', 'LIKE', "%{$buscar}%");
            });
        }

        $usuarios = $query->orderBy('created_at', 'desc')->paginate(15);
        $roles = Role::all();

        return view('usuarios.index', compact('usuarios', 'roles'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $roles = Role::all();
        $unidadesAcademicas = UnidadAcademica::where('activo', true)->get();
        $dependencias = Dependencia::where('activo', true)->get();

        return view('usuarios.create', compact('roles', 'unidadesAcademicas', 'dependencias'));
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'documento' => 'nullable|string|unique:users,documento',
            'telefono' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
            'dependencia_id' => 'required|exists:dependencias,id',
            'unidad_academica_id' => 'required|exists:unidades_academicas,id',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ], [
            'name.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.unique' => 'Este email ya está registrado',
            'documento.unique' => 'Este documento ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'dependencia_id.required' => 'La dependencia es obligatoria',
            'unidad_academica_id.required' => 'La unidad académica es obligatoria',
            'roles.required' => 'Debe asignar al menos un rol',
        ]);

        try {
            $usuario = User::create([
                'name' => $validated['name'],
                'apellido' => $validated['apellido'],
                'email' => $validated['email'],
                'documento' => $validated['documento'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'password' => Hash::make($validated['password']),
                'dependencia_id' => $validated['dependencia_id'],
                'unidad_academica_id' => $validated['unidad_academica_id'],
                'activo' => $request->has('activo') ? true : false,
            ]);

            // Asignar roles
            $usuario->roles()->sync($validated['roles']);

            return redirect()
                ->route('usuarios.index')
                ->with('success', 'Usuario creado exitosamente');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalle del usuario
     */
    public function show(User $usuario)
    {
        $usuario->load(['roles.permissions', 'dependencia', 'unidadAcademica']);

        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(User $usuario)
    {
        $roles = Role::all();
        $unidadesAcademicas = UnidadAcademica::where('activo', true)->get();
        $dependencias = Dependencia::where('activo', true)->get();
        $usuario->load('roles');

        return view('usuarios.edit', compact('usuario', 'roles', 'unidadesAcademicas', 'dependencias'));
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'documento' => 'nullable|string|unique:users,documento,' . $usuario->id,
            'telefono' => 'nullable|string|max:20',
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'dependencia_id' => 'required|exists:dependencias,id',
            'unidad_academica_id' => 'required|exists:unidades_academicas,id',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ], [
            'name.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.unique' => 'Este email ya está registrado',
            'documento.unique' => 'Este documento ya está registrado',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'dependencia_id.required' => 'La dependencia es obligatoria',
            'unidad_academica_id.required' => 'La unidad académica es obligatoria',
            'roles.required' => 'Debe asignar al menos un rol',
        ]);

        try {
            // Actualizar datos básicos
            $dataToUpdate = [
                'name' => $validated['name'],
                'apellido' => $validated['apellido'],
                'email' => $validated['email'],
                'documento' => $validated['documento'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'dependencia_id' => $validated['dependencia_id'],
                'unidad_academica_id' => $validated['unidad_academica_id'],
                'activo' => $request->has('activo') ? true : false,
            ];

            // Actualizar contraseña solo si se proporciona
            if (!empty($validated['password'])) {
                $dataToUpdate['password'] = Hash::make($validated['password']);
            }

            $usuario->update($dataToUpdate);

            // Actualizar roles
            $usuario->roles()->sync($validated['roles']);

            return redirect()
                ->route('usuarios.index')
                ->with('success', 'Usuario actualizado exitosamente');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar usuario
     */
    public function destroy(User $usuario)
    {
        // No permitir eliminar al usuario autenticado
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario');
        }

        // No permitir eliminar usuarios con tickets activos
        if ($usuario->tickets()->whereIn('estado', ['pendiente', 'en_proceso', 'listo'])->exists()) {
            return back()->with('error', 'No se puede eliminar un usuario con tickets activos');
        }

        $usuario->delete();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }

    /**
     * Cambiar estado del usuario (activar/desactivar)
     */
    public function cambiarEstado(User $usuario)
    {
        // No permitir desactivar al usuario autenticado
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes desactivar tu propio usuario');
        }

        $usuario->update([
            'activo' => !$usuario->activo
        ]);

        $estado = $usuario->activo ? 'activado' : 'desactivado';

        return back()->with('success', "Usuario {$estado} exitosamente");
    }

    /**
     * Obtener dependencias por unidad académica (AJAX)
     */
    public function getDependencias($unidadAcademicaId)
    {
        $dependencias = Dependencia::where('unidad_academica_id', $unidadAcademicaId)
            ->where('activo', true)
            ->get(['id', 'nombre']);

        return response()->json($dependencias);
    }
}