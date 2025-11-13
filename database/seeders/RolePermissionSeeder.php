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
            'dashboard-mbos bautisos',

            // 🔹 Blanco
            'ver-blanco',
            'editar-blanco',

            // 🔹 Desafío
            'ver-desafios',
            'ver-desafios distrital anuales',
            'ver-desafios bautisos mbos anuales',
            'editar-desafio bautisos anuales',

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

            // 🔹 Desafío Mensual //SATISFECHO
            'ver-desafios mensuales',
            'crear-desafios mensuales',
            'editar fechas-desafios mensuales',
            'editar desafios-desafios mensuales',

            // 🔹 Distritos //SATISFECHO
            'ver-distritos',
            'ver eliminados-distritos',
            'ver historial-distritos',
            'crear-distritos',
            'editar-distritos',
            'eliminar-distritos',
            'reactivar-distritos',

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

            // (IGLESIAS) asignaciones aparte //SATISFECHO
            'ver-asignacion iglesias',
            'asignar-asignacion iglesias',
            'cambiar-asignacion iglesias',
            'liberar-asignacion iglesias',

            // 🔹 Instructores //satisfecho
            'ver-instructores',
            'ver avance-instructores',
            'crear-instructores',
            'editar-instructores',
            'eliminar-instructores',

            // 🔹 Panel //safisfecho
            'ver dashboard pastores-panel',

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
            'ver - puntualidad',

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
            'gestionar-roles',
            'gestionar-permisos',
            'asignar-roles',
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
        $admin = Role::create(['name' => 'Administrador']);
        $admin->givePermissionTo([
            'ver-administrativo',

            // 🔹 Bautisos
            'ver-bautisos',
            'crear-bautisos',
            'editar-bautisos',
            'eliminar-bautisos',
            'dashboard-mbos bautisos',

            // 🔹 Blanco
            'ver-blanco',
            'editar-blanco',

            // 🔹 Desafío
            'ver-desafios',
            'ver-desafios distrital anuales',
            'ver-desafios bautisos mbos anuales',
            'editar-desafio bautisos anuales',

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

            // 🔹 Desafío Mensual //SATISFECHO
            'ver-desafios mensuales',
            'crear-desafios mensuales',
            'editar fechas-desafios mensuales',
            'editar desafios-desafios mensuales',

            // 🔹 Distritos //SATISFECHO
            'ver-distritos',
            'ver eliminados-distritos',
            'ver historial-distritos',
            'crear-distritos',
            'editar-distritos',
            'eliminar-distritos',
            'reactivar-distritos',

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

            // (IGLESIAS) asignaciones aparte //SATISFECHO
            'ver-asignacion iglesias',
            'asignar-asignacion iglesias',
            'cambiar-asignacion iglesias',
            'liberar-asignacion iglesias',

            // 🔹 Instructores //satisfecho
            'ver-instructores',
            'ver avance-instructores',
            'crear-instructores',
            'editar-instructores',
            'eliminar-instructores',

            // 🔹 Panel //safisfecho
            'ver dashboard pastores-panel',

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
            'ver - puntualidad',

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

            // 🔹 Visitas // SATISFECHOS
            'ver anual-visitas',
            'ver meses-visitas',
            'crear-visitas',
            'editar-visitas',
            'eliminar-visitas',
            'dashboard-visitas',
        ]);

        // ROL: Editor (puede crear y editar, pero no eliminar)
        $pastor = Role::create(['name' => 'Pastor']);
        $pastor->givePermissionTo([
            'ver dashboard pastores-panel',

            'ver-estudiantes',
            'ver avance-estudiantes',
            'crear-estudiantes',
            'editar-estudiantes',
            'eliminar-estudiantes',

            'ver-iglesias',
            'ver-instructores',
            'ver avance-instructores',
            'crear-instructores',
            'editar-instructores',
            'eliminar-instructores',
            'ver anual-visitas',
            'ver meses-visitas',
            'crear-visitas',
            'editar-visitas',
            'eliminar-visitas',
            'dashboard-visitas',
        ]);

        // ========================================
        // CREAR PERSONA SUPER ADMINISTRADOR
        // ========================================
        $superAdminPersona = Persona::find(1);
        $superAdminPersona->assignRole('Super Administrador');

        $pastor = Persona::find(3);
        $pastor->assignRole('Pastor');

        $administrador = Persona::find(2);
        $administrador->assignRole('Administrador');
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
