<?php

namespace App\Http\Controllers;

use App\Models\UnidadAcademica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UnidadAcademicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unidades = UnidadAcademica::withCount('dependencias')
            ->orderBy('nombre')
            ->paginate(10);

        return view('unidades-academicas.index', compact('unidades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('unidades-academicas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:20|unique:unidades_academicas,codigo',
            'descripcion' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'activo' => 'boolean',
        ]);

        try {
            $data = $validated;
            
            // Manejar la subida del logo
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
                $data['logo'] = 'storage/' . $logoPath;
            }

            $data['activo'] = $request->has('activo');

            UnidadAcademica::create($data);

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
     * Display the specified resource.
     */
    public function show(UnidadAcademica $unidadesAcademica)
    {
        $unidadesAcademica->load(['dependencias' => function($query) {
            $query->withCount('users');
        }]);
        
        return view('unidades-academicas.show', compact('unidadesAcademica'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnidadAcademica $unidadesAcademica)
    {
        return view('unidades-academicas.edit', compact('unidadesAcademica'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnidadAcademica $unidadesAcademica)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:20|unique:unidades_academicas,codigo,' . $unidadesAcademica->id,
            'descripcion' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'activo' => 'boolean',
        ]);

        try {
            $data = $validated;
            
            // Manejar la subida del logo
            if ($request->hasFile('logo')) {
                // Eliminar el logo anterior si existe
                if ($unidadesAcademica->logo && str_starts_with($unidadesAcademica->logo, 'storage/')) {
                    $oldLogoPath = str_replace('storage/', '', $unidadesAcademica->logo);
                    Storage::disk('public')->delete($oldLogoPath);
                }
                
                $logoPath = $request->file('logo')->store('logos', 'public');
                $data['logo'] = 'storage/' . $logoPath;
            }

            $data['activo'] = $request->has('activo');

            $unidadesAcademica->update($data);

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
     * Remove the specified resource from storage.
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
     * Change the status of the academic unit
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