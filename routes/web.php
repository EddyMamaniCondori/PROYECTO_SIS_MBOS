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
/*ruta extra para Bautizo*/ 
Route::get('bautisos/dashboard', [BautisosController::class, 'dashboard'])
    ->name('bautiso.dashboard');

Route::get('visitas/dashboard', [VisitasController::class, 'dashboard'])
    ->name('visitas.dashboard');
    Route::get('estudiantes/dashboard', [EstudiantesController::class, 'dashboard'])
    ->name('estudiantes.dashboard');
    Route::get('instructores/dashboard', [InstructoresController::class, 'dashboard'])
    ->name('instructores.dashboard');


Route::get('iglesias/dashboard_general', [IglesiaController::class, 'dashboard_general'])
    ->name('iglesias.dashboard_general');
/*ruta extra para iglesias*/ 

Route::get('iglesias/asignaciones', [IglesiaController::class, 'indexfull'])
    ->name('iglesias.indexasignar');
    
Route::get('desafios/mesual', [DesafioMensualController::class, 'index_mes'])
    ->name('desafios.mes');

Route::post('/desafios/mes/store', [DesafioMensualController::class, 'storeMes'])->name('desafios.store_mes');

Route::get('/desafios/mes/{mes}/{anio}', [DesafioMensualController::class, 'show_mes'])
    ->name('desafios.show_mes');



Route::resource('pastores', PastorController::class);
Route::resource('distritos', DistritoController::class);
Route::resource('iglesias', IglesiaController::class);
Route::resource('grupo', GrupoController::class);
Route::resource('bautisos', BautisosController::class);
Route::resource('visitas', VisitasController::class);
Route::resource('estudiantes', EstudiantesController::class);
Route::resource('instructores', InstructoresController::class);
Route::resource('desafios', DesafioMensualController::class);
