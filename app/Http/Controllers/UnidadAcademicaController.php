<?php

namespace App\Http\Controllers;

use App\Models\UnidadAcademica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UnidadAcademicaController extends Controller
{
    /**
     * Listar todas las unidades académicas
     */
    public function index(Request $request)
    {
        $query = UnidadAcademica::withCount('dependencias');

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('activo', $request->estado);
        }

        // Búsqueda por nombre o código
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'LIKE', "%{$buscar}%")
                    ->orWhere('codigo', 'LIKE', "%{$buscar}%");
            });
        }

        $unidades = $query->orderBy('nombre')->paginate(15)->appends(request()->query());

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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique' => 'Este nombre ya está registrado',
            'codigo.required' => 'El código es obligatorio',
            'codigo.unique' => 'Este código ya está registrado',
            'logo.image' => 'El archivo debe ser una imagen',
            'logo.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif',
            'logo.max' => 'La imagen no debe superar los 2MB',
        ]);

        try {
            $data = [
                'nombre' => $validated['nombre'],
                'codigo' => $validated['codigo'],
                'descripcion' => $validated['descripcion'] ?? null,
                'activo' => $request->has('activo'),
            ];

            // Manejar la subida del logo
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
                $data['logo'] = 'storage/'.$logoPath;
            }

            UnidadAcademica::create($data);

            return redirect()
                ->route('unidades-academicas.index')
                ->with('success', 'Unidad Académica creada exitosamente');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al crear la unidad académica: '.$e->getMessage());
        }
    }

    /**
     * Mostrar detalle de la unidad académica
     */
    public function show(UnidadAcademica $unidadesAcademica)
    {
        $unidadesAcademica->load(['dependencias' => function ($query) {
            $query->withCount('users');
        }]);

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
            'nombre' => 'required|string|max:255|unique:unidades_academicas,nombre,'.$unidadesAcademica->id,
            'codigo' => 'required|string|max:50|unique:unidades_academicas,codigo,'.$unidadesAcademica->id,
            'descripcion' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique' => 'Este nombre ya está registrado',
            'codigo.required' => 'El código es obligatorio',
            'codigo.unique' => 'Este código ya está registrado',
            'logo.image' => 'El archivo debe ser una imagen',
            'logo.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif',
            'logo.max' => 'La imagen no debe superar los 2MB',
        ]);

        try {
            $data = [
                'nombre' => $validated['nombre'],
                'codigo' => $validated['codigo'],
                'descripcion' => $validated['descripcion'] ?? null,
                'activo' => $request->has('activo'),
            ];

            // Manejar la subida del logo
            if ($request->hasFile('logo')) {
                // Eliminar el logo anterior si existe
                if ($unidadesAcademica->logo && str_starts_with($unidadesAcademica->logo, 'storage/')) {
                    $oldLogoPath = str_replace('storage/', '', $unidadesAcademica->logo);
                    Storage::disk('public')->delete($oldLogoPath);
                }

                $logoPath = $request->file('logo')->store('logos', 'public');
                $data['logo'] = 'storage/'.$logoPath;
            }

            $unidadesAcademica->update($data);

            return redirect()
                ->route('unidades-academicas.index')
                ->with('success', 'Unidad Académica actualizada exitosamente');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar la unidad académica: '.$e->getMessage());
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

        // Eliminar el logo si existe
        if ($unidadesAcademica->logo && str_starts_with($unidadesAcademica->logo, 'storage/')) {
            $logoPath = str_replace('storage/', '', $unidadesAcademica->logo);
            Storage::disk('public')->delete($logoPath);
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
            'activo' => ! $unidadesAcademica->activo,
        ]);

        $estado = $unidadesAcademica->activo ? 'activada' : 'desactivada';

        return back()->with('success', "Unidad Académica {$estado} exitosamente");
    }
}
