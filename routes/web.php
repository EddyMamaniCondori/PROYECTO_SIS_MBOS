<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PastorController;
use App\Http\Controllers\DistritoController;
use App\Http\Controllers\IglesiaController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\BautisosController;
use App\Http\Controllers\VisitasController;
use App\Http\Controllers\EstudiantesController;
use App\Http\Controllers\InstructoresController;
use App\Http\Controllers\DesafioMensualController;
use App\Http\Controllers\RemesaController;
use App\Http\Controllers\RemesaExcelController;
use App\Http\Controllers\PendientesController;
use App\Http\Controllers\PuntualidadController;
use App\Http\Controllers\RemesasDashboardController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/template', function () {
    return view('template');
});

Route::get('/panel', function () {
    return view('panel/index');
});

Route::get('/tablas', function () {
    return view('pruebas/table');
});
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


Route::get('remesas/distrital/dashboard', [RemesasDashboardController::class, 'index'])
    ->name('remesas.distrital.dashboard');

Route::get('remesas/distrital/dash', [RemesasDashboardController::class, 'index_distrital'])
    ->name('remesas.distrital.dash');


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

/*______________*/ 
Route::get('bautisos/dashboard', [BautisosController::class, 'dashboard'])
    ->name('bautiso.dashboard');


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


Route::get('visitas/dashboard', [VisitasController::class, 'dashboard'])
    ->name('visitas.dashboard');
    Route::get('estudiantes/dashboard', [EstudiantesController::class, 'dashboard'])
    ->name('estudiantes.dashboard');
    Route::get('instructores/dashboard', [InstructoresController::class, 'dashboard'])
    ->name('instructores.dashboard');


Route::get('iglesias/dashboard_general', [IglesiaController::class, 'dashboard_general'])
    ->name('iglesias.dashboard_general');
Route::post('iglesias/reactive/{id}', [IglesiaController::class, 'reactive'])
    ->name('iglesias.reactive');





    /*ruta extra para iglesias*/ 
Route::get('iglesias/indexdelete', [IglesiaController::class, 'index_eliminado'])
    ->name('iglesias.indexdelete');
Route::get('iglesias/asignaciones', [IglesiaController::class, 'index_asignaciones'])
    ->name('iglesias.asignaciones');
    
Route::get('desafios/mesual', [DesafioMensualController::class, 'index_mes'])
    ->name('desafios.mes');

Route::post('/desafios/mes/store', [DesafioMensualController::class, 'storeMes'])->name('desafios.store_mes');

Route::get('/desafios/mes/{mes}/{anio}', [DesafioMensualController::class, 'show_mes'])
    ->name('desafios.show_mes');

//pruebas
Route::post('/iglesias/{id}/asignar-distrito', [IglesiaController::class, 'asignarDistrito'])->name('iglesias.asignar');
Route::post('/iglesias/{id}/cambiar-distrito', [IglesiaController::class, 'cambiarDistrito'])->name('iglesias.cambiar');
Route::post('/iglesias/{id}/liberar', [IglesiaController::class, 'liberar'])->name('iglesias.liberar');





/*RUTAS EXTRAS PARA EL PASTOR */
Route::get('/pastor_perfil/{id_pastor}', [PastorController::class, 'perfil_pastor'])
    ->name('pastor.perfil');


Route::resource('pastores', PastorController::class);
Route::resource('distritos', DistritoController::class);
Route::resource('iglesias', IglesiaController::class);
Route::resource('grupo', GrupoController::class);
Route::resource('bautisos', BautisosController::class);
Route::resource('visitas', VisitasController::class);
Route::resource('estudiantes', EstudiantesController::class);
Route::resource('instructores', InstructoresController::class);
Route::resource('desafios', DesafioMensualController::class);
Route::resource('remesas', RemesaController::class);
