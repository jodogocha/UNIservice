<?php

namespace App\Http\Controllers;

use App\Models\UnidadAcademica;
use Illuminate\Http\Request;

class UnidadAcademicaController extends Controller
{
    /**
     * Listar todas las unidades académicas
     */
    public function index(Request $request)
    {
        $query = UnidadAcademica::withCount('dependencias');

        // Filtro por estado
        if ($request->has('estado') && $request->estado !== '') {
            $query->where('activo', $request->estado);
        }

        // Búsqueda por nombre
        if ($request->has('buscar') && $request->buscar !== '') {
            $query->where('nombre', 'LIKE', '%' . $request->buscar . '%');
        }

        $unidades = $query->orderBy('nombre')->paginate(15);

        return view('unidades-academicas.index', compact('unidades'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('unidades-academicas.create');
    }

    /**
     * Guardar nueva unidad académica
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:unidades_academicas,nombre',
            'codigo' => 'required|string|max:50|unique:unidades_academicas,codigo',
            'descripcion' => 'nullable|string',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique' => 'Este nombre ya está registrado',
            'codigo.required' => 'El código es obligatorio',
            'codigo.unique' => 'Este código ya está registrado',
        ]);

        try {
            UnidadAcademica::create([
                'nombre' => $validated['nombre'],
                'codigo' => $validated['codigo'],
                'descripcion' => $validated['descripcion'] ?? null,
                'activo' => $request->has('activo') ? true : false,
            ]);

            return redirect()
                ->route('unidades-academicas.index')
                ->with('success', 'Unidad Académica creada exitosamente');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al crear la unidad académica: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalle de la unidad académica
     */
    public function show(UnidadAcademica $unidadesAcademica)
    {
        $unidadesAcademica->load('dependencias');

        return view('unidades-academicas.show', compact('unidadesAcademica'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(UnidadAcademica $unidadesAcademica)
    {
        return view('unidades-academicas.edit', compact('unidadesAcademica'));
    }

    /**
     * Actualizar unidad académica
     */
    public function update(Request $request, UnidadAcademica $unidadesAcademica)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:unidades_academicas,nombre,' . $unidadesAcademica->id,
            'codigo' => 'required|string|max:50|unique:unidades_academicas,codigo,' . $unidadesAcademica->id,
            'descripcion' => 'nullable|string',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique' => 'Este nombre ya está registrado',
            'codigo.required' => 'El código es obligatorio',
            'codigo.unique' => 'Este código ya está registrado',
        ]);

        try {
            $unidadesAcademica->update([
                'nombre' => $validated['nombre'],
                'codigo' => $validated['codigo'],
                'descripcion' => $validated['descripcion'] ?? null,
                'activo' => $request->has('activo') ? true : false,
            ]);

            return redirect()
                ->route('unidades-academicas.index')
                ->with('success', 'Unidad Académica actualizada exitosamente');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar la unidad académica: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar unidad académica
     */
    public function destroy(UnidadAcademica $unidadesAcademica)
    {
        // No permitir eliminar si tiene dependencias
        if ($unidadesAcademica->dependencias()->count() > 0) {
            return back()->with('error', 'No se puede eliminar una unidad académica que tiene dependencias asociadas');
        }

        $unidadesAcademica->delete();

        return redirect()
            ->route('unidades-academicas.index')
            ->with('success', 'Unidad Académica eliminada exitosamente');
    }

    /**
     * Cambiar estado de la unidad académica
     */
    public function cambiarEstado(UnidadAcademica $unidadesAcademica)
    {
        $unidadesAcademica->update([
            'activo' => !$unidadesAcademica->activo
        ]);

        $estado = $unidadesAcademica->activo ? 'activada' : 'desactivada';

        return back()->with('success', "Unidad Académica {$estado} exitosamente");
    }
}