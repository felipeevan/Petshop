<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PetsController;
use App\Http\Controllers\API\AccessController;
use App\Http\Controllers\API\ClientesController;
use App\Http\Controllers\API\ServicosController;
use App\Http\Controllers\API\AtendimentosController;
use App\Http\Controllers\API\FuncionariosController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/funcionario/list', [FuncionariosController::class, 'list']);
    Route::post('/funcionario/add', [FuncionariosController::class, 'add']);
    Route::get('/funcionario/delete', [FuncionariosController::class, 'delete']);
    Route::post('/funcionario/update', [FuncionariosController::class, 'update']);

    Route::get('/cliente/list', [ClientesController::class, 'list']);
    Route::post('/cliente/add', [ClientesController::class, 'add']);
    Route::get('/cliente/delete', [ClientesController::class, 'delete']);
    Route::post('/cliente/update', [ClientesController::class, 'update']);

    Route::get('/servico/list', [ServicosController::class, 'list']);
    Route::post('/servico/add', [ServicosController::class, 'add']);
    Route::get('/servico/delete', [ServicosController::class, 'delete']);
    Route::post('/servico/update', [ServicosController::class, 'update']);

    Route::get('/pet/list', [PetsController::class, 'list']);
    Route::get('/pet/listB', [PetsController::class, 'listBest']);
    Route::post('/pet/add', [PetsController::class, 'add']);
    Route::get('/pet/delete', [PetsController::class, 'delete']);
    Route::post('/pet/update', [PetsController::class, 'update']);
    Route::get('/pet/listclientespets', [PetsController::class, 'listClientesPets']);

    Route::get('/atendimento/list', [AtendimentosController::class, 'list']);
    Route::get('/atendimento/listB', [AtendimentosController::class, 'listB']);
    Route::post('/atendimento/add', [AtendimentosController::class, 'add']);
    Route::get('/atendimento/delete', [AtendimentosController::class, 'delete']);
    
});

Route::post('/login', [AccessController::class, 'login']);
Route::post('/register', [AccessController::class, 'register']);

