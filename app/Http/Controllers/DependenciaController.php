<?php

namespace App\Http\Controllers;

use App\Models\Dependencia;
use App\Models\UnidadAcademica;
use Illuminate\Http\Request;

class DependenciaController extends Controller
{
    /**
     * Listar todas las dependencias
     */
    public function index(Request $request)
    {
        $query = Dependencia::with('unidadAcademica')->withCount('users');

        // Filtro por unidad académica
        if ($request->has('unidad_academica') && $request->unidad_academica !== '') {
            $query->where('unidad_academica_id', $request->unidad_academica);
        }

        // Filtro por estado
        if ($request->has('estado') && $request->estado !== '') {
            $query->where('activo', $request->estado);
        }

        // Búsqueda por nombre
        if ($request->has('buscar') && $request->buscar !== '') {
            $query->where('nombre', 'LIKE', '%' . $request->buscar . '%');
        }

        $dependencias = $query->orderBy('nombre')->paginate(15);
        $unidadesAcademicas = UnidadAcademica::where('activo', true)->orderBy('nombre')->get();

        return view('dependencias.index', compact('dependencias', 'unidadesAcademicas'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $unidadesAcademicas = UnidadAcademica::where('activo', true)->orderBy('nombre')->get();

        return view('dependencias.create', compact('unidadesAcademicas'));
    }

    /**
     * Guardar nueva dependencia
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:dependencias,codigo',
            'unidad_academica_id' => 'required|exists:unidades_academicas,id',
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'codigo.required' => 'El código es obligatorio',
            'codigo.unique' => 'Este código ya está registrado',
            'unidad_academica_id.required' => 'Debe seleccionar una unidad académica',
        ]);

        try {
            Dependencia::create([
                'nombre' => $validated['nombre'],
                'codigo' => $validated['codigo'],
                'unidad_academica_id' => $validated['unidad_academica_id'],
                'descripcion' => $validated['descripcion'] ?? null,
                'activo' => $request->has('activo') ? 1 : 0,
            ]);

            return redirect()
                ->route('dependencias.index')
                ->with('success', 'Dependencia creada exitosamente');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al crear la dependencia: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalle de la dependencia
     */
    public function show(Dependencia $dependencia)
    {
        $dependencia->load(['unidadAcademica', 'users']);

        return view('dependencias.show', compact('dependencia'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Dependencia $dependencia)
    {
        $unidadesAcademicas = UnidadAcademica::where('activo', true)->orderBy('nombre')->get();

        return view('dependencias.edit', compact('dependencia', 'unidadesAcademicas'));
    }

    /**
     * Actualizar dependencia
     */
    public function update(Request $request, Dependencia $dependencia)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:dependencias,codigo,' . $dependencia->id,
            'unidad_academica_id' => 'required|exists:unidades_academicas,id',
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'codigo.required' => 'El código es obligatorio',
            'codigo.unique' => 'Este código ya está registrado',
            'unidad_academica_id.required' => 'Debe seleccionar una unidad académica',
        ]);

        try {
            $dependencia->update([
                'nombre' => $validated['nombre'],
                'codigo' => $validated['codigo'],
                'unidad_academica_id' => $validated['unidad_academica_id'],
                'descripcion' => $validated['descripcion'] ?? null,
                'activo' => $request->has('activo') ? 1 : 0,
            ]);

            return redirect()
                ->route('dependencias.index')
                ->with('success', 'Dependencia actualizada exitosamente');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar la dependencia: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar dependencia
     */
    public function destroy(Dependencia $dependencia)
    {
        // No permitir eliminar si tiene usuarios
        if ($dependencia->users()->count() > 0) {
            return back()->with('error', 'No se puede eliminar una dependencia que tiene usuarios asociados');
        }

        $dependencia->delete();

        return redirect()
            ->route('dependencias.index')
            ->with('success', 'Dependencia eliminada exitosamente');
    }

    /**
     * Cambiar estado de la dependencia
     */
    public function cambiarEstado(Dependencia $dependencia)
    {
        $dependencia->update([
            'activo' => !$dependencia->activo
        ]);

        $estado = $dependencia->activo ? 'activada' : 'desactivada';

        return back()->with('success', "Dependencia {$estado} exitosamente");
    }
}