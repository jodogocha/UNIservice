<?php

namespace App\Http\Controllers;

use App\Models\UnidadAcademica;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    /**
     * Mostrar vista de configuración
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Solo admin puede configurar
        if (!$user->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a la configuración');
        }
        
        // Obtener todas las unidades académicas
        $unidadesAcademicas = UnidadAcademica::with('dependencias', 'users')
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();
        
        // Obtener la unidad seleccionada (por defecto la del usuario)
        $unidadSeleccionada = null;
        if ($request->has('unidad_id')) {
            $unidadSeleccionada = UnidadAcademica::findOrFail($request->unidad_id);
        } else {
            $unidadSeleccionada = $user->unidadAcademica;
        }
        
        $modulosDisponibles = UnidadAcademica::modulosDisponibles();
        
        return view('configuracion.index', compact(
            'unidadesAcademicas',
            'unidadSeleccionada',
            'modulosDisponibles'
        ));
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
        
        $validated = $request->validate([
            'unidad_id' => 'required|exists:unidades_academicas,id',
            'modulos' => 'nullable|array',
            'modulos.*' => 'string',
        ]);
        
        $unidadAcademica = UnidadAcademica::findOrFail($validated['unidad_id']);
        $modulosActivos = $validated['modulos'] ?? [];
        
        $unidadAcademica->update([
            'modulos_activos' => $modulosActivos,
        ]);
        
        return redirect()
            ->route('configuracion.index', ['unidad_id' => $unidadAcademica->id])
            ->with('success', "Configuración de módulos actualizada correctamente para {$unidadAcademica->nombre}");
    }

    /**
     * Actualizar configuración general de una unidad
     */
    public function updateGeneral(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasRole('admin')) {
            abort(403, 'No tienes permisos para actualizar la configuración');
        }
        
        $validated = $request->validate([
            'unidad_id' => 'required|exists:unidades_academicas,id',
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $unidadAcademica = UnidadAcademica::findOrFail($validated['unidad_id']);
        
        // Validar que el código sea único (excepto para la unidad actual)
        $existeCodigo = UnidadAcademica::where('codigo', $validated['codigo'])
            ->where('id', '!=', $unidadAcademica->id)
            ->exists();
            
        if ($existeCodigo) {
            return back()->withErrors(['codigo' => 'Este código ya está en uso por otra unidad académica.']);
        }
        
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
            ->route('configuracion.index', ['unidad_id' => $unidadAcademica->id])
            ->with('success', "Configuración general actualizada correctamente para {$unidadAcademica->nombre}");
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
        
        $validated = $request->validate([
            'unidad_id' => 'required|exists:unidades_academicas,id',
        ]);
        
        $unidadAcademica = UnidadAcademica::findOrFail($validated['unidad_id']);
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
            ->route('configuracion.index', ['unidad_id' => $unidadAcademica->id])
            ->with('success', "Configuración avanzada actualizada correctamente para {$unidadAcademica->nombre}");
    }

    /**
     * Copiar configuración de una unidad a otra
     */
    public function copiarConfiguracion(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasRole('admin')) {
            abort(403, 'No tienes permisos para copiar configuraciones');
        }
        
        $validated = $request->validate([
            'unidad_origen_id' => 'required|exists:unidades_academicas,id',
            'unidad_destino_id' => 'required|exists:unidades_academicas,id|different:unidad_origen_id',
        ]);
        
        $unidadOrigen = UnidadAcademica::findOrFail($validated['unidad_origen_id']);
        $unidadDestino = UnidadAcademica::findOrFail($validated['unidad_destino_id']);
        
        // Copiar módulos activos y configuración
        $unidadDestino->update([
            'modulos_activos' => $unidadOrigen->modulos_activos,
            'configuracion' => $unidadOrigen->configuracion,
        ]);
        
        return redirect()
            ->route('configuracion.index', ['unidad_id' => $unidadDestino->id])
            ->with('success', "Configuración copiada exitosamente de {$unidadOrigen->nombre} a {$unidadDestino->nombre}");
    }
}