<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PastorController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\AdministrativoController;
use App\Http\Controllers\DistritoController;
use App\Http\Controllers\IglesiaController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\BautisosController;
use App\Http\Controllers\VisitasController;
use App\Http\Controllers\EstudiantesController;
use App\Http\Controllers\InstructoresController;
use App\Http\Controllers\DesafioController;
use App\Http\Controllers\RemesaController;
use App\Http\Controllers\RemesaExcelController;
use App\Http\Controllers\PendientesController;
use App\Http\Controllers\PuntualidadController;
use App\Http\Controllers\RemesasDashboardController;
use App\Http\Controllers\BlancoController;
use App\Http\Controllers\DesafioAnualIglesiaController;
use App\Http\Controllers\DesafioMensualController;
use App\Http\Controllers\DesafioEventoController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Auth;
Route::get('/', function () {

    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    if ($user->hasRole('Super Administrador')) {
        return redirect()->route('panel');
    }

    if ($user->hasRole('Administrativo')) {
        return redirect()->route('panel.mbos');
    }

    if ($user->hasRole('Tesorero')) {
        return redirect()->route('dashboard.tesorero');
    }

    if ($user->hasRole('Secretaria')) {
        return redirect()->route('dashboard.secretario');
    }

    if ($user->hasRole('Pastor')) {
        return redirect()->route('dashboard.pastor');
    }

    return redirect()->route('panel');
});


Route::redirect('/', '/login');

// ===== TUS RUTAS ANTIGUAS (las reconstruimos juntos) =====
Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [PerfilController::class, 'index'])
        ->name('perfil.index');
    // 1) Actualizar datos personales
    Route::get('/panel/mbos', [PanelController::class, 'panel_mbos'])
        ->name('panel');

    Route::put('/perfil/datos', [PerfilController::class, 'updateData'])->name('profile.updateData');

    // 2) Cambiar contraseña
    Route::put('/perfil/password', [PerfilController::class, 'updatePassword'])->name('profile.updatePassword');

    // 3) Subir foto
    Route::post('/perfil/foto', [PerfilController::class, 'updatePhoto'])->name('profile.updatePhoto');
    /**
     *_________________DASHBOARDS PRINCIPALES
    * 
    */
     //pastor
    Route::get('/dashboard/pastor/', [PanelController::class, 'dashboard_pastores'])
        ->name('dashboard.pastor');

    Route::get('/dashboard/ver/pastor/{id}/{anio}', [PanelController::class, 'ver_avance_pastores'])
        ->name('dashboard.ver.pastor');
    //tesorero
    Route::get('/tesoreria/dashboard', [PanelController::class, 'dashboardTesorero'])
    ->name('dashboard.tesorero');
    //secretario
    Route::get('/secretario/dashboard', [PanelController::class, 'dashboardSecretario'])
    ->name('dashboard.secretario');

    /**
     *_________________BANCOS
    * 
    */
    Route::get('/blancos/index/', [BlancoController::class, 'index'])
        ->name('blancos.index');
    Route::post('/blancos/filtrar', [blancoController::class, 'index_filtro'])->name('blancos.filtro');
    
    /**
     *_________________Bautisos
    * 
    */
    Route::get('/bautizos/dashboard/', [BautisosController::class, 'dashboard_general'])
        ->name('bautizos.dashboard');

    /**
     *_________________PUNTUALIDADES
    * 
    */
    Route::get('/remesas/puntualidades/', [PuntualidadController::class, 'index'])
        ->name('remesas.puntualidades');
    
    /**
     *_________________PENDIENTES
    * 
    */
    Route::get('remesas/pendientes', [PendientesController::class, 'index'])
        ->name('remesas.pendientes');
    Route::post('/remesas/pendientes/anual/filtro', [PendientesController::class, 'filtro_anual'])->name('remesas.pendiente.anual.filtro');
    Route::get('remesas/pendientes/mensual', [PendientesController::class, 'index_mensual'])
        ->name('remesas.pendientes.mensual');

    Route::get('remesas/pendientes/distrital', [PendientesController::class, 'index_distrital'])
        ->name('remesas.pendientes.distrital');

    /**
     *_______________GRAFICOS DE REMESAS
    * 
    */


    Route::get('remesas/distrital/dash', [RemesasDashboardController::class, 'index_distrital'])
        ->name('remesas.distrital.dash');
    //muestra la grafica  de remesas balnco de 1 distrito
    Route::get('remesas/distrital/dash_general', [RemesasDashboardController::class, 'dashboard_finanzas_distrito'])
        ->name('remesas.distrital.dash_general');

    //muestra la grafica  de remesas de las filiales de 1 distrito
    Route::get('remesas/distrital/filial/dash_general', [RemesasDashboardController::class, 'dashboard_finanzas_filiales_distrito'])
        ->name('remesas.distrital.filial.dash_general');
    //muestra la grafica  de fondos locales de las filiales de 1 distrito
    Route::get('remesas/fondo_local/distrital/filial/dash_general', [RemesasDashboardController::class, 'dashboard_fondo_local_filiales_distrito'])
        ->name('remesas.fondo_local.distrital.filial.dash_general');

    Route::get('/remesas/tabla-distrital', [RemesasDashboardController::class, 'tabla_distrital'])
     ->name('remesas.tabla.distrital');

    /**
     *_______________DESAFIOS BAUTISOS POR DISTRITO
    * 
    */
    Route::get('desafios/bautizos/distrital/', [DesafioController::class, 'index_desafio_bautizos'])
        ->name('desafios.bautizos');

    Route::put('/desafios/update_2/{id}', [DesafioController::class, 'update_2'])
        ->name('desafios.update_2');
    /**
     *_______________DESAFIOS IGLESIAS ANUALES ESTUDIANTES Y INSTRUCTORES
    * 
    */
    Route::get('instructores/dashboard', [InstructoresController::class, 'index_desafios_inst'])
        ->name('instructores.dashboard');

    Route::post('/desafios/{id}/completar-iglesias', [DesafioController::class, 'asignarAnualIglesiasFaltantes_botom'])
        ->name('desafios.completar-faltantes');

    Route::get('instructores/mbos/distrital/', [InstructoresController::class, 'index_mbos'])
        ->name('instructores.mbos.distrital');

    Route::get('/desafios/asignacion/distrital/{id}', [InstructoresController::class, 'index_mbos_distrital'])
        ->name('desafios.asignacion.distrital');

    Route::put('/desafios/anual-iglesia/{id}/actualizar-est-inst', [InstructoresController::class, 'update_est_inst'])
    ->name('desafios.update.est_inst');

    Route::get('/desafios/asignacion/distrital/masivo/{id}', [InstructoresController::class, 'index_mbos_distrital_masivo'])
        ->name('desafios.asignacion.distrital.masivo');

    Route::put('/desafios/masivo', [InstructoresController::class, 'updateMasivo'])
        ->name('anual_iglesias.update.masivo');

    Route::get('instructores/mbos/distrital/ver', [InstructoresController::class, 'index_mbos_dashboard'])
        ->name('instructores.mbos.distrital.ver');
    
    Route::get('instructores/mbos/distrital/{id}/{anio}', [InstructoresController::class, 'index_instructores_mbos'])
        ->name('instructores.x.distrital'); //para ver los instructores de 1 distrito

    Route::get('estudiantes/mbos/distrital/{id}/{anio}', [InstructoresController::class, 'index_estudiantes_mbos'])
        ->name('estudiantes.x.distrital');

    Route::get('inst/estu/mbos/distrital/{id}/{anio}', [InstructoresController::class, 'dashboar_inst_estu_mbos'])
        ->name('mbos.x.distrital');

    /**
     *_________________IMPORTACION Y EXPOTACION DE ARCHIVOS EXCEL 
    * 
    */
    Route::post('/remesas/importar', [RemesaExcelController::class, 'import'])->name('remesas.import');

    Route::get('/remesas/index_import/', [RemesaExcelController::class, 'index'])
        ->name('remesas.indeximport');

    Route::delete('/remesas/importar/destroy/{id}', [RemesaExcelController::class, 'destroy'])->name('remesasimport.destroy');



    Route::get('/remesas/procesar/{anio}', [RemesaExcelController::class, 'procesarRemesas'])->name('remesas.procesar');

    /**
     * 
     */
    Route::get('/remesas/mes/{mes}/{anio}', [RemesaController::class, 'index_mes'])
        ->name('remesas.index_mes');

    Route::POST('/remesas/filial/', [RemesaController::class, 'llenar_filial'])
        ->name('remesas.filial');


    Route::POST('/remesas/filial/registrar/{id}', [RemesaController::class, 'registrar_remesa_filial'])
        ->name('remesasfilial.registrar');

    Route::POST('/remesas/iglesia/registrar/{id_remesa}', [RemesaController::class, 'registrar_remesa_iglesia'])
        ->name('remesasiglesia.registrar');


    


    // Ruta para procesar el formulario (POST)
    Route::post('remesas/crear', [RemesaController::class, 'crear'])->name('remesas.crear');


    /*ruta extra para Pastores*/ 
    Route::get('pastores/indexdelete', [PastorController::class, 'indexdelete'])
        ->name('pastores.indexdelete');
    Route::post('pastores/reactive/{id}', [PastorController::class, 'reactive'])
        ->name('pastores.reactive');
    /*ruta extra para Personales*/
    Route::post('personales/reactive/{id}', [PersonalController::class, 'reactive'])
        ->name('personales.reactive');
    Route::get('personales/indexdelete', [PersonalController::class, 'indexdelete'])
        ->name('personales.indexdelete');

    /*______________*/ 

    Route::get('bautisos/dashboard/pastoral', [BautisosController::class, 'dashboard_general_pastores'])
        ->name('bautiso.dashboard.pastoral');
    Route::get('bautisos/x/pastor', [BautisosController::class, 'bautisos_distrital'])
        ->name('bautiso.pastor');

    /*ruta extra para Distritos*/ 
    Route::get('distritos/asignaciones', [DistritoController::class, 'index_asignaciones'])
        ->name('distritos.asignaciones');

    Route::get('distritos/asiganual', [DistritoController::class, 'indexanual'])
        ->name('distritos.asiganual');

    Route::get('distritos/historiales', [DistritoController::class, 'index_historial'])
        ->name('distritos.historiales');

    Route::get('/distritos/historial/{id_distrito}', [DistritoController::class, 'historial'])
        ->name('distritos.historial');

    Route::get('/distritos/copiar-diriges', [DistritoController::class, 'copiarADiriges'])
        ->name('distritos.copiar.diriges');

    Route::get('/distritos/finalizar_asignaciones/{anio}', [DistritoController::class, 'Finalizar_Asignaciones'])
        ->name('distritos.finalizar_asignaciones');

    Route::get('distritos/indexdelete', [DistritoController::class, 'index_eliminado'])
        ->name('distritos.indexdelete');
        
    Route::post('distritos/reactive/{id}', [DistritoController::class, 'reactive'])
        ->name('distritos.reactive');

    /*ruta para signaciones */
    Route::get('/distritos/asignacion/mantener/{id_distrito}', [DistritoController::class, 'mantenerAsignacion'])->name('distritos.mantener');
    Route::get('/distritos/asignaciones/liberar/{id_distrito}', [DistritoController::class, 'liberarAsignacion'])->name('distritos.liberar');

    Route::get('/distritos/asignaciones/liberar_anual/{id_distrito}', [DistritoController::class, 'liberarAsignacionAnual'])->name('distritos.liberar_anual');
    Route::post('/distritos/asignar/cambiar/{id_distrito}', [DistritoController::class, 'cambiarAsignacion'])->name('distritos.cambiar');
    Route::post('/distritos/asignar/cambiaranual/{id_distrito}', [DistritoController::class, 'cambiarAsignacionAnual'])->name('distritos.cambiaranual');   

    /*ruta para visitas */
    Route::get('visitas/dashboard', [VisitasController::class, 'dashboard'])
        ->name('visitas.dashboard');

    Route::get('visitas/index_mes', [VisitasController::class, 'index_mes'])
        ->name('visitas.index_mes');

    Route::get('/visitas/llenar-mes/{id}', [VisitasController::class, 'index_llenar_visitas_mes'])
        ->name('visitas.llenar_mes');

    Route::delete('/visitas/{id_mensual}/{id_visita}', [VisitasController::class, 'destroy'])
        ->name('visitas.destroy');

    Route::get('/visitas/create/{id_mensual}', [VisitasController::class, 'create'])
        ->name('visitas.create');

    Route::get('/visitas/{id_mensual}/{id_visita}/edit', [VisitasController::class, 'edit'])
        ->name('visitas.edit');

    /*ruta para signaciones */

    
    Route::get('iglesias/dashboard_general', [IglesiaController::class, 'dashboard_general'])
        ->name('iglesias.dashboard_general');
    Route::post('iglesias/reactive/{id}', [IglesiaController::class, 'reactive'])
        ->name('iglesias.reactive');

        /*ruta extra para iglesias*/ 
    Route::get('iglesias/indexdelete', [IglesiaController::class, 'index_eliminado'])
        ->name('iglesias.indexdelete');
    Route::get('iglesias/asignaciones', [IglesiaController::class, 'index_asignaciones'])
        ->name('iglesias.asignaciones');

    Route::get('iglesias/index_pastores', [IglesiaController::class, 'index_pastores'])
        ->name('iglesias.index_pastores');

    Route::get('iglesias/index_pastores/asignacion_lideres', [IglesiaController::class, 'index_lideres_locales_pastores'])
        ->name('iglesias.index_pastores/asignacion_lideres');
        
    Route::put('/lideres/masivo', [IglesiaController::class, 'updateMasivo'])
        ->name('lideres.update.masivo');

    Route::get('iglesias/lideres_locales', [IglesiaController::class, 'resumenDistritos'])
        ->name('iglesias.lideres_locales');

    Route::get('iglesias/lideres_locales/{id}/detalle', [IglesiaController::class, 'detallePorDistrito'])
        ->name('iglesias.lideres_locales.detalle');


    Route::get('desafios/mesual', [DesafioController::class, 'index_mes'])
        ->name('desafios.mes');

    Route::post('/desafios/mes/store', [DesafioController::class, 'storeMes'])->name('desafios.store_mes');

    Route::get('/desafios/mes/{mes}/{anio}', [DesafioController::class, 'show_mes'])
        ->name('desafios.show_mes');

    //pruebas
    Route::post('/iglesias/{id}/asignar-distrito', [IglesiaController::class, 'asignarDistrito'])->name('iglesias.asignar');
    Route::post('/iglesias/{id}/cambiar-distrito', [IglesiaController::class, 'cambiarDistrito'])->name('iglesias.cambiar');
    Route::post('/iglesias/{id}/liberar', [IglesiaController::class, 'liberar'])->name('iglesias.liberar');





    /*RUTAS EXTRAS PARA EL PASTOR */
    Route::get('/pastor_perfil/{id_pastor}', [PastorController::class, 'perfil_pastor'])
        ->name('pastor.perfil');

        /**
         * _____________________DESAFIOS DE DISTRITO 
         * 
         */
    Route::get('/desafios/{id}/distrital', [DesafioController::class, 'index_distrital'])
        ->name('desafios.index_distrital');

    Route::get('desafio_eventos/indexdelete', [DesafioEventoController::class, 'index_eliminado'])
        ->name('desafio_eventos.indexdelete');

    Route::post('desafio_eventos/reactive/{id}', [DesafioEventoController::class, 'reactive'])
        ->name('desafio_eventos.reactive');

    Route::get('desafio_eventos/indexasignaciones', [DesafioEventoController::class, 'index_asignaciones'])
        ->name('desafio_eventos.indexasignaciones');

    Route::get('desafio_eventos/asignar/{id}/distritos', [DesafioEventoController::class, 'asignar_evento_distrito'])
        ->name('desafio_eventos.asignar_distritos');

    Route::get('desafio_eventos/mostrar_asignaciones/{id}/', [DesafioEventoController::class, 'mostrar_asignaciones_evento'])
        ->name('desafio_eventos.mostrar_asignaciones');

    Route::put('desafio_eventos/asignaciones/{id}', [DesafioEventoController::class, 'update_asignacion_desafio'])
        ->name('desafio_eventos.update_asignacion_desafio');
    
    Route::get('desafio_eventos/dashboard_asignaciones', [DesafioEventoController::class, 'index_dasboards_eventos'])
        ->name('desafio_eventos.dashboard_asignaciones');

    Route::get('desafio_evento/{id}/dashboard-bautizos', [DesafioEventoController::class, 'dashboard_bautizos_evento'])
        ->name('desafio_evento.dashboard.bautizos');

        /**
         * _____________________DESAFIOS MENSUALES DE LOS DISTRITOS 
         * 
         */

    Route::put('/mensuales_desafios/{id}', [DesafioMensualController::class, 'update_desafios'])
        ->name('mensuales_desafios.update'); 

    Route::get('mensuales/asignar_desafio/{mes}/{anio}', [DesafioMensualController::class, 'index_mes'])
        ->name('mensuales.asignar_desafio');

    Route::get('mensuales/asignar_desafio/masivo/{mes}/{anio}', [DesafioMensualController::class, 'index_mes_masivo'])
        ->name('mensuales.asignar_desafio.masivo');
    
    Route::put('/mensuales-desafios/masivo/update', [DesafioMensualController::class, 'updateMasivo'])
        ->name('mensuales_desafios.update.masivo');

    Route::get('mensuales/dashboard/{mes}/{anio}', [DesafioMensualController::class, 'dashboard_mes_x_distrito'])
        ->name('mensuales.dashboard');

    Route::get('mensuales/dashboard_meses', [DesafioMensualController::class, 'resumenMensualGeneral'])
        ->name('mensuales.dashboard_meses');
    //REMESAS
    Route::get('/pdf/remesas/{anio}/{mes}', [RemesaController::class, 'exportRemesaMensualPDF'])
    ->name('pdf.remesas.mensual');

        /**
         * _____________________EXPORTACIONES DE PDF Y EXCEL
         */
    Route::get('/puntualidad/export-excel', [PuntualidadController::class, 'exportExcel']);
    Route::get('/puntualidad/export-pdf', [PuntualidadController::class, 'exportPdf'])
        ->name('puntualidad.exportPdf');

    Route::get('/remesas/tabla-distrital/excel-direct', [RemesasDashboardController::class, 'exportDistritalExcelDirect'])
        ->name('remesas.tabla.excel.direct');
    Route::get('/remesas/tabla-distrital/pdf', [RemesasDashboardController::class, 'exportDistritalPDF'])
        ->name('remesas.tabla.pdf');

    Route::get('/blancos/filtro/{anio}', [RemesasDashboardController::class, 'index_distrital_filtro'])
     ->name('blancos.filtro');

    //remsas filiales
    Route::get('/remesas/filiales-mensual', [RemesasDashboardController::class, 'tablaFilialesPivot'])
        ->name('remesas.filiales.pivot');

    Route::get('/remesas/filiales-mensual/excel', 
    [RemesasDashboardController::class, 'exportFilialesExcel'])
    ->name('remesas.filiales.excel');
    Route::get('/remesas/filiales-mensual/pdf', 
    [RemesasDashboardController::class, 'exportFilialesPDF'])
    ->name('remesas.filiales.pdf');


    Route::resource('pastores', PastorController::class);
    Route::resource('personales', PersonalController::class);
    Route::resource('administrativos', AdministrativoController::class);

    Route::resource('distritos', DistritoController::class);
    Route::resource('iglesias', IglesiaController::class);
    Route::resource('grupo', GrupoController::class);
    Route::resource('bautisos', BautisosController::class);
    Route::resource('visitas', VisitasController::class);
    Route::resource('estudiantes', EstudiantesController::class);
    Route::resource('instructores', InstructoresController::class);
    Route::resource('desafios', DesafioController::class);
    Route::resource('remesas', RemesaController::class);
    Route::resource('blancos', BlancoController::class);
    Route::resource('anual_iglesias', DesafioAnualIglesiaController::class);


    Route::resource('desafios_mensuales', DesafioMensualController::class);

    Route::resource('desafio_eventos', DesafioEventoController::class);

    Route::resource('roles', roleController::class);

});
require __DIR__.'/auth.php';