<?php

namespace App\Http\Controllers;

use App\Models\UnidadAcademica;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    /**
     * Mostrar vista de configuración
     */
    public function index()
    {
        $user = auth()->user();
        
        // Solo admin puede configurar
        if (!$user->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a la configuración');
        }
        
        $unidadAcademica = $user->unidadAcademica;
        $modulosDisponibles = UnidadAcademica::modulosDisponibles();
        
        return view('configuracion.index', compact('unidadAcademica', 'modulosDisponibles'));
    }

    /**
     * Actualizar configuración de módulos
     */
    public function updateModulos(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasRole('admin')) {
            abort(403, 'No tienes permisos para actualizar la configuración');
        }
        
        $unidadAcademica = $user->unidadAcademica;
        
        $validated = $request->validate([
            'modulos' => 'nullable|array',
            'modulos.*' => 'string',
        ]);
        
        $modulosActivos = $validated['modulos'] ?? [];
        
        $unidadAcademica->update([
            'modulos_activos' => $modulosActivos,
        ]);
        
        return redirect()
            ->route('configuracion.index')
            ->with('success', 'Configuración de módulos actualizada correctamente');
    }

    /**
     * Actualizar configuración general
     */
    public function updateGeneral(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasRole('admin')) {
            abort(403, 'No tienes permisos para actualizar la configuración');
        }
        
        $unidadAcademica = $user->unidadAcademica;
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:unidades_academicas,codigo,' . $unidadAcademica->id,
            'descripcion' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = [
            'nombre' => $validated['nombre'],
            'codigo' => $validated['codigo'],
            'descripcion' => $validated['descripcion'] ?? null,
        ];
        
        // Manejar logo
        if ($request->hasFile('logo')) {
            if ($unidadAcademica->logo && str_starts_with($unidadAcademica->logo, 'storage/')) {
                $oldLogoPath = str_replace('storage/', '', $unidadAcademica->logo);
                \Storage::disk('public')->delete($oldLogoPath);
            }
            
            $logoPath = $request->file('logo')->store('logos', 'public');
            $data['logo'] = 'storage/' . $logoPath;
        }
        
        $unidadAcademica->update($data);
        
        return redirect()
            ->route('configuracion.index')
            ->with('success', 'Configuración general actualizada correctamente');
    }

    /**
     * Actualizar configuración avanzada
     */
    public function updateConfiguracion(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasRole('admin')) {
            abort(403, 'No tienes permisos para actualizar la configuración');
        }
        
        $unidadAcademica = $user->unidadAcademica;
        
        $configuracion = $unidadAcademica->configuracion ?? [];
        
        // Actualizar configuraciones específicas
        if ($request->has('tickets_auto_asignar')) {
            $configuracion['tickets']['auto_asignar'] = $request->boolean('tickets_auto_asignar');
        }
        
        if ($request->has('tickets_require_approval')) {
            $configuracion['tickets']['require_approval'] = $request->boolean('tickets_require_approval');
        }
        
        if ($request->has('notificaciones_email')) {
            $configuracion['notificaciones']['email'] = $request->boolean('notificaciones_email');
        }
        
        $unidadAcademica->update([
            'configuracion' => $configuracion,
        ]);
        
        return redirect()
            ->route('configuracion.index')
            ->with('success', 'Configuración avanzada actualizada correctamente');
    }
}