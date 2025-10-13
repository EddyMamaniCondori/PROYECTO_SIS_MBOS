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

Route::get('bautisos/dashboard', [BautisosController::class, 'dashboard'])
    ->name('bautiso.dashboard');
/*ruta extra para Distritos*/ 
Route::get('distritos/asignaciones', [DistritoController::class, 'index_asignaciones'])
    ->name('distritos.asignaciones');

Route::get('distritos/historiales', [DistritoController::class, 'index_historial'])
    ->name('distritos.historiales');

Route::get('/distritos/historial/{id_distrito}', [DistritoController::class, 'historial'])
    ->name('distritos.historial');

Route::get('/distritos/copiar-diriges', [DistritoController::class, 'copiarADiriges'])
    ->name('distritos.copiar.diriges');

    Route::get('/distritos/finalizar_asignaciones', [DistritoController::class, 'Finalizar_Asignaciones'])
    ->name('distritos.finalizar_asignaciones');
 /*ruta para signaciones */
Route::get('/distritos/asignacion/mantener/{id_distrito}', [DistritoController::class, 'mantenerAsignacion'])->name('distritos.mantener');
Route::get('/distritos/asignaciones/liberar/{id_distrito}', [DistritoController::class, 'liberarAsignacion'])->name('distritos.liberar');
Route::post('/distritos/asignar/cambiar/{id_distrito}', [DistritoController::class, 'cambiarAsignacion'])->name('distritos.cambiar');
   


Route::get('visitas/dashboard', [VisitasController::class, 'dashboard'])
    ->name('visitas.dashboard');
    Route::get('estudiantes/dashboard', [EstudiantesController::class, 'dashboard'])
    ->name('estudiantes.dashboard');
    Route::get('instructores/dashboard', [InstructoresController::class, 'dashboard'])
    ->name('instructores.dashboard');


Route::get('iglesias/dashboard_general', [IglesiaController::class, 'dashboard_general'])
    ->name('iglesias.dashboard_general');
/*ruta extra para iglesias*/ 


    
Route::get('desafios/mesual', [DesafioMensualController::class, 'index_mes'])
    ->name('desafios.mes');

Route::post('/desafios/mes/store', [DesafioMensualController::class, 'storeMes'])->name('desafios.store_mes');

Route::get('/desafios/mes/{mes}/{anio}', [DesafioMensualController::class, 'show_mes'])
    ->name('desafios.show_mes');

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
