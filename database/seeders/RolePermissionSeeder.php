<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Persona;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resetear caché de roles y permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ========================================
        // CREAR PERMISOS
        // ========================================
        $permissions = [
            // 🔹 Administrativo
            'ver-administrativo',

            // 🔹 Bautisos
            'ver-bautisos',
            'crear-bautisos',
            'editar-bautisos',
            'eliminar-bautisos',
            // 🔹 Bautisos para para pastores distritales
            'ver pastor-bautisos distrito',
            'ver dashboard pastor-bautisos distrito',
            // 🔹 Blanco //blanco de remesas
            'ver-blanco',
            'editar-blanco',

            // 🔹 Desafío
            'ver-desafios',
            'ver-desafios distrital anuales',

            // (DESAFIO) desafio bautisos
            'ver-desafios bautisos mbos anuales',//bautisos general mbos
            'editar-desafio bautisos anuales', //
            'dashboard-mbos bautisos', //esta en BautisosController

            //(DESAFIO) desafio de estudiantes y instructors
            'ver mbos-desafios anual Est Inst',//esta en InstructorController
            'editar mbos-desafios anual Est Inst',//esta en InstructorController
            'ver estudiantes de distritos-desafios anual Est Inst',//esta en InstructorController
            'ver instructores de distritos-desafios anual Est Inst',//esta en InstructorController
            'ver detalle de iglesias de distritos-desafios anual Est Inst',//esta en InstructorController
             
            // 🔹 Desafío Anual
            'editar-desafios anual Est Inst',
            'eliminar-desafios anual Est Inst',

            // 🔹 Desafío Evento
            'ver-desafios eventos',
            'ver-eliminados-desafios eventos',
            'crear-desafios eventos',
            'editar-desafios eventos',
            'eliminar-desafios eventos',
            'reactivar-desafios eventos',

             // 🔹 (sub tabla) Asignacion Desafío Evento
            'ver-asignacion desafios eventos',
            'ver desafio-asignacion desafios eventos',
            'asignar evento a distrito-asignacion desafios eventos',
            'actualizar desafios-asignacion desafios eventos',
            // (sub tabla) graficas  Desafío Evento 
            'ver-dashboards desafios eventos',
            'ver por evento-dashboards desafios eventos',
            // 🔹 Desafío Mensual //SATISFECHO
            'ver-desafios mensuales',
            'crear-desafios mensuales',
            'editar fechas-desafios mensuales',
            'editar desafios-desafios mensuales',
            // 🔹 Desafío Mensual MBOS//SATISFECHO
            'ver los blancos de 1 mes-desafios mensuales',
            'editar desafios mes masivo-desafios mensuales',
            'graficos x mes MBOS-desafios mensuales',
            'graficos todos los meses MBOS-desafios mensuales',


            // 🔹 Distritos //SATISFECHO
            'ver-distritos',
            'ver eliminados-distritos',
            'ver historial-distritos',
            'crear-distritos',
            'editar-distritos',
            'eliminar-distritos',
            'reactivar-distritos',
            //(DISTRITOS) //Asignacion de pastores, en año en curso
            'cambiar asignaciones ACT - distritos',
            //(DISTRITOS) //Asignaciones de pastores, para el siguiente año
            'cambiar asignaciones SIG - distritos',

            // 🔹 Estudiantes // SATISFECHO
            'ver-estudiantes',
            'ver avance-estudiantes',
            'crear-estudiantes',
            'editar-estudiantes',
            'eliminar-estudiantes',

            // 🔹 Grupo /SATISFECHO (falta asiganciones)
            'ver-grupos',
            'crear-grupos',
            'editar-grupos',
            'eliminar-grupos',

            // 🔹 Iglesias // SATISFECHO
            'ver-iglesias',
            'reactivar-iglesias',
            'ver eliminados-iglesias',
            'crear-iglesias',
            'editar-iglesias',
            'eliminar-iglesias',
            'ver pastor-iglesias',
            'editar pastor-iglesias',
            // LIDERES_LOCALES
            'ver x distritos-lideres locales',
            'ver x iglesias-lideres locales',
            'editar pastor iglesias-lideres locales',
            // (IGLESIAS) asignaciones aparte //SATISFECHO
            'asignaciones-iglesias',

            // 🔹 Instructores //satisfecho
            'ver-instructores',
            'ver avance-instructores',
            'crear-instructores',
            'editar-instructores',
            'eliminar-instructores',

            // 🔹 Panel //safisfecho
            'ver dashboard pastores-panel',
            'ver avance pastores-panel', //nose sabe que es

            // 🔹 Pastores  //SATISFECHO
            'ver-pastores',
            'ver eliminados-pastores',
            'crear-pastores',
            'editar-pastores',
            'eliminar-pastores',
            'reactivar-pastores',

            // 🔹 Pendientes //satisfecho
            'ver anual-pendientes',
            'ver distrital-pendientes',
            'ver mensual-pendientes',

            // 🔹 Personal // SATISFECHOS
            'ver-personal',
            'ver eliminados-personal',
            'crear-personal',
            'editar-personal',
            'eliminar-personal',
            'reactivar-personal',

            // 🔹 Puntualidad// SATISFECHOS
            'ver-puntualidad',

            // 🔹 Remesas// SATISFECHOS
            'ver meses-remesas',
            'crear meses-remesas',
            'ver remesas mes-remesas',

            'ver remesas filiales-remesas',
            'llenar remesas filiales-remesas',
            'registra remesas filiales-remesas',

            // 🔹 Remesa Excel// SATISFECHOS
            'ver-remesas excel',
            'importar-remesas excel',
            'eliminar-remesas excel',
            'guardar-remesas excel',

            // 🔹 Remesa Dashboard// FALTA EXPLORAR
            'ver-remesas dashboard',
            'ver dashboar pastor-remesas dashboard',
            'ver dashboar remesas filiales pastor-remesas dashboard',
            'ver dashboar fondo local pastor-remesas dashboard',

            // 🔹 Visitas // SATISFECHOS
            'ver anual-visitas',
            'ver meses-visitas',
            'crear-visitas',
            'editar-visitas',
            'eliminar-visitas',
            'dashboard-visitas',

    
            // Permisos de Reportes
            //'ver reportes',
            //'exportar reportes',
            
            // Permisos de Roles y Permisos
            'ver-roles',
            'crear-roles',
            'editar-roles',
            'eliminar-roles',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // ========================================
        // CREAR ROLES Y ASIGNAR PERMISOS
        // ========================================

        // ROL: Super Administrador (todos los permisos)
        $superAdmin = Role::create(['name' => 'Super Administrador']);
        $superAdmin->givePermissionTo(Permission::all());

        // ROL: Administrador (casi todos los permisos excepto gestión de roles)
        $admin = Role::create(['name' => 'Secretaria']);
        $admin->givePermissionTo([
                       // 🔹 Administrativo
            'ver-administrativo',

            // 🔹 Bautisos
            'ver-bautisos',
            'crear-bautisos',
            'editar-bautisos',
            'eliminar-bautisos',

            // 🔹 Desafío
            'ver-desafios',
            'ver-desafios distrital anuales',
            // (DESAFIO) desafio bautisos
            'ver-desafios bautisos mbos anuales',//bautisos general mbos
            'editar-desafio bautisos anuales', //
            'dashboard-mbos bautisos', //esta en BautisosController

            //(DESAFIO) desafio de estudiantes y instructors
            'ver mbos-desafios anual Est Inst',//esta en InstructorController
            'editar mbos-desafios anual Est Inst',//esta en InstructorController

             // 🔹 Desafío Anual
            'editar-desafios anual Est Inst',
            'eliminar-desafios anual Est Inst',
            'ver estudiantes de distritos-desafios anual Est Inst',
            'ver instructores de distritos-desafios anual Est Inst',
            'ver detalle de iglesias de distritos-desafios anual Est Inst',
            'editar-desafios anual Est Inst',
            'eliminar-desafios anual Est Inst',
            // 🔹 Desafío Evento
            'ver-desafios eventos',
            'ver-eliminados-desafios eventos',
            'crear-desafios eventos',
            'editar-desafios eventos',
            'eliminar-desafios eventos',
            'reactivar-desafios eventos',

            'ver-dashboards desafios eventos',
            'ver por evento-dashboards desafios eventos',
            'ver-desafios mensuales',
            'crear-desafios mensuales',
            'editar fechas-desafios mensuales',
            'editar desafios-desafios mensuales',
            'ver los blancos de 1 mes-desafios mensuales',
            'editar desafios mes masivo-desafios mensuales',
            'graficos x mes MBOS-desafios mensuales',
            'graficos todos los meses MBOS-desafios mensuales',

            // 🔹 Distritos //SATISFECHO
            'ver-distritos',
            'ver eliminados-distritos',
            'ver historial-distritos',
            'crear-distritos',
            'editar-distritos',
            'eliminar-distritos',
            'reactivar-distritos',
            //(DISTRITOS) //Asignacion de pastores, en año en curso
            'cambiar asignaciones ACT - distritos',
            //(DISTRITOS) //Asignaciones de pastores, para el siguiente año
            'cambiar asignaciones SIG - distritos',

            // 🔹 Grupo /SATISFECHO (falta asiganciones)
            'ver-grupos',
            'crear-grupos',
            'editar-grupos',
            'eliminar-grupos',

            // 🔹 Iglesias // SATISFECHO
            'ver-iglesias',
            'reactivar-iglesias',
            'ver eliminados-iglesias',
            'crear-iglesias',
            'editar-iglesias',
            'eliminar-iglesias',

            // (IGLESIAS) asignaciones aparte //SATISFECHO
            'ver x distritos-lideres locales',
            'ver x iglesias-lideres locales',
            'editar pastor iglesias-lideres locales',
            'asignaciones-iglesias',

 
            // 🔹 Panel //safisfecho
            'ver dashboard pastores-panel',
            'ver avance pastores-panel',
            // 🔹 Pastores  //SATISFECHO
            'ver-pastores',
            'ver eliminados-pastores',
            'crear-pastores',
            'editar-pastores',
            'eliminar-pastores',
            'reactivar-pastores',

            // 🔹 Personal // SATISFECHOS
            'ver-personal',
            'ver eliminados-personal',
            'crear-personal',
            'editar-personal',
            'eliminar-personal',
            'reactivar-personal',

            // 🔹 Remesa Dashboard// FALTA EXPLORAR
            'dashboard-visitas',
        ]);

        // ROL: Editor (puede crear y editar, pero no eliminar)
        $pastor = Role::create(['name' => 'Pastor']);
        $pastor->givePermissionTo([
            //el pastor puede ver sus bautisos 
            'ver pastor-bautisos distrito',
            'ver dashboard pastor-bautisos distrito',
            // 🔹 Estudiantes // SATISFECHO
            'ver-estudiantes',
            'ver avance-estudiantes',
            'crear-estudiantes',
            'editar-estudiantes',
            'eliminar-estudiantes',
            // 🔹 Iglesias // SATISFECHO
            'ver pastor-iglesias',
            'editar pastor-iglesias',
            'editar pastor iglesias-lideres locales',
            // 🔹 Instructores //satisfecho
            'ver-instructores',
            'ver avance-instructores',
            'crear-instructores',
            'editar-instructores',
            'eliminar-instructores',
            // pendientes
            'ver distrital-pendientes',
            // dashboard de remesas filiales
            'ver dashboar pastor-remesas dashboard',
            'ver dashboar remesas filiales pastor-remesas dashboard',
            'ver dashboar fondo local pastor-remesas dashboard',
            // 🔹 Panel //safisfecho
            'ver dashboard pastores-panel',
            'ver avance pastores-panel',
            // 🔹 Visitas // SATISFECHOS
            'ver anual-visitas',
            'ver meses-visitas',
            'crear-visitas',
            'editar-visitas',
            'eliminar-visitas',
            'dashboard-visitas',     ]);

        
        
        
        // ========================================
        
        $teso = Role::create(['name' => 'Tesorero']);
        $teso->givePermissionTo([
            'ver-administrativo',
            'ver-blanco',
            'editar-blanco',
            'ver-distritos',
            'ver historial-distritos',
            'ver-grupos',
            'ver-iglesias',
            'ver-pastores',
            'ver anual-pendientes',
            'ver distrital-pendientes',
            'ver mensual-pendientes',
            'ver-personal',
            'ver eliminados-personal',
            'crear-personal',
            'editar-personal',
            'eliminar-personal',
            'reactivar-personal',
            'ver-puntualidad',
            'ver meses-remesas',
            'crear meses-remesas',
            'ver remesas mes-remesas',
            'ver remesas filiales-remesas',
            'llenar remesas filiales-remesas',
            'registra remesas filiales-remesas',
            'ver-remesas excel',
            'importar-remesas excel',
            'eliminar-remesas excel',
            'guardar-remesas excel',
            'ver-remesas dashboard',
        ]);

        
        
        
        // ========================================
        
        
        
        
        
        // CREAR PERSONA SUPER ADMINISTRADOR
        // ========================================
        $superAdminPersona = Persona::find(1);
        $superAdminPersona->assignRole('Super Administrador');

        $pastor = Persona::find(3);
        $pastor->assignRole('Pastor');

        $administrador = Persona::find(2);
        $administrador->assignRole('Secretaria');
        $this->command->info('✅ Roles, permisos y usuarios de prueba creados exitosamente!');

        $pastor = Persona::find(4); $pastor->assignRole('Pastor');
        $pastor = Persona::find(5); $pastor->assignRole('Pastor');
        $pastor = Persona::find(6); $pastor->assignRole('Pastor');
        $pastor = Persona::find(7); $pastor->assignRole('Pastor');
        $pastor = Persona::find(8); $pastor->assignRole('Pastor');
        $pastor = Persona::find(9); $pastor->assignRole('Pastor');
        $pastor = Persona::find(10); $pastor->assignRole('Pastor');
        $pastor = Persona::find(11); $pastor->assignRole('Pastor');
        $pastor = Persona::find(12); $pastor->assignRole('Pastor');
        $pastor = Persona::find(13); $pastor->assignRole('Pastor');
        $pastor = Persona::find(14); $pastor->assignRole('Pastor');
        $pastor = Persona::find(15); $pastor->assignRole('Pastor');
        $pastor = Persona::find(16); $pastor->assignRole('Pastor');
        $pastor = Persona::find(17); $pastor->assignRole('Pastor');
        $pastor = Persona::find(18); $pastor->assignRole('Pastor');
        $pastor = Persona::find(19); $pastor->assignRole('Pastor');
        $pastor = Persona::find(20); $pastor->assignRole('Pastor');
        $pastor = Persona::find(21); $pastor->assignRole('Pastor');
        $pastor = Persona::find(22); $pastor->assignRole('Pastor');
        $pastor = Persona::find(23); $pastor->assignRole('Pastor');
        $pastor = Persona::find(24); $pastor->assignRole('Pastor');
        $pastor = Persona::find(25); $pastor->assignRole('Pastor');
        $pastor = Persona::find(26); $pastor->assignRole('Pastor');
        $pastor = Persona::find(27); $pastor->assignRole('Pastor');
        $pastor = Persona::find(28); $pastor->assignRole('Pastor');
        $pastor = Persona::find(29); $pastor->assignRole('Pastor');
        $pastor = Persona::find(30); $pastor->assignRole('Pastor');
        $pastor = Persona::find(31); $pastor->assignRole('Pastor');
        $pastor = Persona::find(32); $pastor->assignRole('Pastor');
        $pastor = Persona::find(33); $pastor->assignRole('Pastor');
        $pastor = Persona::find(34); $pastor->assignRole('Pastor');
        $pastor = Persona::find(35); $pastor->assignRole('Pastor');
        $pastor = Persona::find(36); $pastor->assignRole('Pastor');
        $pastor = Persona::find(37); $pastor->assignRole('Pastor');
        $pastor = Persona::find(38); $pastor->assignRole('Pastor');
        $pastor = Persona::find(39); $pastor->assignRole('Pastor');
        $pastor = Persona::find(40); $pastor->assignRole('Pastor');
        $pastor = Persona::find(41); $pastor->assignRole('Pastor');
    
    }
}
