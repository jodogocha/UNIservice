<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Dependencia;
use App\Models\UnidadAcademica;
use Carbon\Carbon;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $humanidades = UnidadAcademica::where('codigo', 'FHCSyCG')->first();
        $funcionarios = User::whereHas('roles', function($q) {
            $q->where('slug', 'funcionario');
        })->where('unidad_academica_id', $humanidades->id)->get();

        $tecnicos = User::whereHas('roles', function($q) {
            $q->whereIn('slug', ['admin', 'encargado-lab']);
        })->where('unidad_academica_id', $humanidades->id)->get();

        if ($funcionarios->isEmpty()) {
            $this->command->error('No hay funcionarios disponibles. Ejecuta UserSeeder primero.');
            return;
        }

        $asuntos = [
            // Reparación
            'Computadora no enciende en sala de profesores',
            'Teclado con teclas dañadas en Laboratorio 2',
            'Mouse inalámbrico no funciona correctamente',
            'Monitor con pantalla parpadeante',
            'Impresora no imprime en calidad',
            'Proyector sin imagen en aula 301',
            'Parlantes con sonido distorsionado',
            'Cámara web sin imagen en sala de reuniones',
            'Escáner no detecta documentos',
            'UPS con batería agotada',
            
            // Mantenimiento
            'Limpieza de computadoras del laboratorio de informática',
            'Actualización de antivirus en todas las computadoras',
            'Mantenimiento preventivo de equipos del decanato',
            'Revisión de cableado de red en biblioteca',
            'Limpieza de ventiladores de CPU',
            'Actualización de sistema operativo',
            'Backup de archivos importantes',
            'Revisión de conexiones eléctricas',
            'Optimización de equipos lentos',
            'Mantenimiento de servidor de archivos',
            
            // Configuración
            'Configuración de red wifi en nueva oficina',
            'Configuración de correo electrónico institucional',
            'Configuración de impresora en red',
            'Configuración de VPN para trabajo remoto',
            'Configuración de acceso remoto',
            'Configuración de router en secretaría',
            'Configuración de compartición de archivos',
            'Configuración de permisos de usuario',
            'Configuración de respaldo automático',
            'Configuración de firewall institucional',
            
            // Asesoramiento
            'Asesoramiento sobre respaldo de información',
            'Consulta sobre actualización de computadora',
            'Orientación para compra de nueva laptop',
            'Dudas sobre seguridad informática',
            'Consulta sobre almacenamiento en la nube',
            'Asesoramiento en configuración de router',
            'Consulta sobre licencias de software',
            'Orientación para migración de datos',
            'Dudas sobre compatibilidad de programas',
            'Consulta sobre velocidad de internet',
            
            // Otros
            'Problema con acceso al sistema académico',
            'Error en la plataforma virtual',
            'Solicitud de usuario y contraseña para nuevo docente',
            'Reseteo de contraseña de correo institucional',
            'Problema de conectividad en sala de conferencias',
            'Solicitud de cable HDMI para proyección',
            'Necesito extensión eléctrica para evento',
            'Problema con el sistema de asistencia biométrico',
            'Solicitud de micrófono para conferencia',
            'Problema con el sistema de sonido del auditorio',
        ];

        $descripciones = [
            'El equipo presenta el problema desde hace varios días. Solicito atención urgente.',
            'He intentado reiniciar el equipo pero el problema persiste.',
            'El problema afecta el desarrollo normal de las actividades.',
            'Necesito que se revise lo más pronto posible.',
            'El equipo está completamente inoperativo.',
            'Se requiere atención técnica especializada.',
            'El problema es intermitente pero cada vez más frecuente.',
            'Ya se realizaron intentos de solución sin éxito.',
            'Se necesita una evaluación técnica del equipo.',
            'El problema está afectando a varios usuarios.',
        ];

        $estados = ['pendiente', 'en_proceso', 'listo', 'finalizado', 'cancelado'];
        $prioridades = ['baja', 'media', 'alta', 'urgente'];
        
        // ✅ CORREGIDO: Usar los mismos valores que la migración
        $tipos = ['mantenimiento', 'asesoramiento', 'reparacion', 'configuracion', 'otro'];

        $this->command->info('Generando 50 tickets de prueba...');
        $progressBar = $this->command->getOutput()->createProgressBar(50);

        for ($i = 0; $i < 50; $i++) {
            $solicitante = $funcionarios->random();
            $dependencia = $solicitante->dependencia;
            $fechaCreacion = Carbon::now()->subDays(rand(0, 60));
            
            // Determinar estado según antigüedad
            $diasDesdeCreacion = $fechaCreacion->diffInDays(Carbon::now());
            if ($diasDesdeCreacion < 5) {
                $estado = 'pendiente';
                $asignadoA = null;
            } elseif ($diasDesdeCreacion < 15) {
                $estado = rand(0, 1) ? 'pendiente' : 'en_proceso';
                $asignadoA = $estado === 'en_proceso' ? $tecnicos->random()->id : null;
            } elseif ($diasDesdeCreacion < 30) {
                $estado = ['en_proceso', 'listo'][rand(0, 1)];
                $asignadoA = $tecnicos->random()->id;
            } else {
                $estado = ['listo', 'finalizado', 'cancelado'][rand(0, 2)];
                $asignadoA = $estado !== 'cancelado' ? $tecnicos->random()->id : null;
            }

            // Determinar prioridad según tipo
            $tipoServicio = $tipos[array_rand($tipos)];
            if ($tipoServicio === 'reparacion') {
                $prioridad = ['media', 'alta', 'urgente'][rand(0, 2)];
            } elseif ($tipoServicio === 'asesoramiento') {
                $prioridad = ['baja', 'media'][rand(0, 1)];
            } else {
                $prioridad = $prioridades[array_rand($prioridades)];
            }

            $ticket = Ticket::create([
                'codigo' => 'TKT-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'asunto' => $asuntos[$i],
                'descripcion' => $descripciones[array_rand($descripciones)],
                'tipo_servicio' => $tipoServicio,
                'prioridad' => $prioridad,
                'estado' => $estado,
                'solicitante_id' => $solicitante->id,
                'dependencia_id' => $dependencia->id,
                'unidad_academica_id' => $humanidades->id,
                'asignado_a' => $asignadoA,
                'created_at' => $fechaCreacion,
                'updated_at' => $fechaCreacion,
            ]);

            // Agregar comentarios para tickets más antiguos
            if ($diasDesdeCreacion > 10 && $estado !== 'pendiente') {
                $this->agregarComentarios($ticket, $solicitante, $tecnicos);
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->newLine(2);
        $this->command->info('✓ 50 tickets creados exitosamente');
        
        // Mostrar estadísticas
        $this->mostrarEstadisticas();
    }

    private function agregarComentarios($ticket, $solicitante, $tecnicos)
    {
        $comentarios = [
            'Ticket recibido. Procederemos a revisar el equipo.',
            'Se está evaluando la situación.',
            'El problema ha sido identificado.',
            'Estamos trabajando en la solución.',
            'Se requieren repuestos adicionales.',
            'El equipo está siendo reparado.',
            'Casi listo, últimos ajustes.',
            'Problema solucionado. Favor verificar.',
            'Trabajo completado satisfactoriamente.',
            'Gracias por la atención brindada.',
        ];

        $numComentarios = rand(1, 3);
        for ($i = 0; $i < $numComentarios; $i++) {
            \App\Models\TicketComentario::create([
                'ticket_id' => $ticket->id,
                'user_id' => $i % 2 === 0 ? $tecnicos->random()->id : $solicitante->id,
                'comentario' => $comentarios[array_rand($comentarios)],
                'created_at' => $ticket->created_at->addDays(rand(1, 5)),
            ]);
        }
    }

    private function mostrarEstadisticas()
    {
        $total = Ticket::count();
        $pendientes = Ticket::where('estado', 'pendiente')->count();
        $enProceso = Ticket::where('estado', 'en_proceso')->count();
        $listos = Ticket::where('estado', 'listo')->count();
        $finalizados = Ticket::where('estado', 'finalizado')->count();
        $cancelados = Ticket::where('estado', 'cancelado')->count();

        $this->command->info('');
        $this->command->info('=== ESTADÍSTICAS DE TICKETS ===');
        $this->command->info("Total de tickets: {$total}");
        $this->command->info("Pendientes: {$pendientes}");
        $this->command->info("En Proceso: {$enProceso}");
        $this->command->info("Listos: {$listos}");
        $this->command->info("Finalizados: {$finalizados}");
        $this->command->info("Cancelados: {$cancelados}");
        $this->command->info('================================');
    }
}