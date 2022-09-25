<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

//Serviços
Route::get('/servicos/index', 'App\Http\Controllers\ServicosController@index')->name('listar_servicos');
Route::post('/servicos/index', 'App\Http\Controllers\ServicosController@store')->name('registrar_servico');
Route::get('/servicos/ver/{id}', 'App\Http\Controllers\ServicosController@show')->name('ver_servico');
Route::post('/servicos/editar', 'App\Http\Controllers\ServicosController@update')->name('alterar_servico');
Route::post('/servicos/excluir', 'App\Http\Controllers\ServicosController@destroy')->name('excluir_servico');

//Agendamentos
Route::get('/agendamentos/index', 'App\Http\Controllers\AgendamentosController@index')->name('listar_agendamentos');
Route::post('/agendamentos/index', 'App\Http\Controllers\AgendamentosController@storeData')->name('registrar_data');
Route::post('/agendamentos/agendamento', 'App\Http\Controllers\AgendamentosController@store')->name('registrar_agendamento');
Route::post('/agendamentos/editar', 'App\Http\Controllers\AgendamentosController@update')->name('alterar_agendamento');
Route::get('/agendamentos/relatorioCondicionalPDF', 'App\Http\Controllers\AgendamentosController@relatorioCondicionalPDF')->name('relatorioCondicionalPDF_agendamentos');
