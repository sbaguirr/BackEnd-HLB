<?php

use Illuminate\Database\Seeder;
use App\Models\Solicitud;

class SolicitudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('solicitudes')->delete();
        Solicitud::create([
            'hora_realizacion' => '10:00:00',
            'fecha_realizacion' => '2020-06-10',
            'observacion' => 'Ninguna',
            'estado' => 'EP',       #En progreso
            'prioridad' => 'A',     #Alta
            'tipo' => 'ST',         #Servicio Técnico 
            'id_firma' => null,
            'id_usuario' => 'administracion'
        ]);
        Solicitud::create([
            'hora_realizacion' => '10:50:00',
            'fecha_realizacion' => '2020-06-10',
            'observacion' => 'Ninguna',
            'estado' => 'EP',       #En progreso
            'prioridad' => 'M',     #Media
            'tipo' => 'AE',          #Asignación de equipo
            'id_firma' => null,
            'id_usuario' => 'userfinanzas'
        ]);


        Solicitud::create([
            'hora_realizacion' => '11:00:00',
            'fecha_realizacion' => '2020-06-10',
            'observacion' => 'Ninguna',
            'estado' => 'P',        #Pendiente
            'prioridad' => 'A',     #Alta
            'tipo' => 'ST',         #Servicio Técnico 
            'id_firma' => null,
            'id_usuario' => 'asistente'
        ]);
        Solicitud::create([
            'hora_realizacion' => '14:17:00',
            'fecha_realizacion' => '2020-06-10',
            'observacion' => 'Ninguna',
            'estado' => 'P',        #Pendiente
            'prioridad' => 'B',     #Baja
            'tipo' => 'AE',         #Asignación de equipo
            'id_firma' => null,
            'id_usuario' => 'administracion'
        ]);
        Solicitud::create([
            'hora_realizacion' => '15:01:00',
            'fecha_realizacion' => '2020-06-10',
            'observacion' => 'Ninguna',
            'estado' => 'P',         #Pendiente
            'prioridad' => 'CT',     #Crítica
            'tipo' => 'AE',         #Asignación de equipo
            'id_firma' => null,
            'id_usuario' => 'auditoria'
        ]);
        Solicitud::create([
            'hora_realizacion' => '15:34:00',
            'fecha_realizacion' => '2020-06-10',
            'observacion' => 'Ninguna',
            'estado' => 'P',         #Pendiente
            'prioridad' => 'M',     #Crítica
            'tipo' => 'ST',         #Servicio Técnico
            'id_firma' => null,
            'id_usuario' => 'laboratorio1'
        ]);



        Solicitud::create([
            'hora_realizacion' => '12:25:00',
            'fecha_realizacion' => '2020-06-10',
            'observacion' => 'Ninguna',
            'estado' => 'C',        #Completada
            'prioridad' => 'CT',    #Critica
            'tipo' => 'ST',         #Servicio Técnico 
            'id_firma' => null,
            'id_usuario' => 'laboratorio1'
        ]);
        Solicitud::create([
            'hora_realizacion' => '12:30:00',
            'fecha_realizacion' => '2020-06-10',
            'observacion' => 'Ninguna',
            'estado' => 'C',        #Completada
            'prioridad' => 'B',     #Baja
            'tipo' => 'AE',         #Asignación de equipo 
            'id_firma' => null,
            'id_usuario' => 'administracion'
        ]);


        Solicitud::create([
            'hora_realizacion' => '13:05:00',
            'fecha_realizacion' => '2020-06-10',
            'observacion' => 'Ninguna',
            'estado' => 'R',        #Rechazada
            'prioridad' => 'A',     #Alta
            'tipo' => 'AE',         #Asignación de equipo
            'id_firma' => null,
            'id_usuario' => 'auditoria'
        ]);
        Solicitud::create([
            'hora_realizacion' => '13:02:00',
            'fecha_realizacion' => '2020-06-10',
            'observacion' => 'Ninguna',
            'estado' => 'R',        #Rechazada
            'prioridad' => 'M',     #Media
            'tipo' => 'ST',         #Servicio técnico
            'id_firma' => null,
            'id_usuario' => 'dietetica'
        ]);
       




    }
}
