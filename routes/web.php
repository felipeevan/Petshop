<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FuncionariosControler;
use App\Http\Controllers\ClientesControler;
use App\Http\Controllers\ServicosControler;
use App\Http\Controllers\AtendimentosControler;

Route::get('/', function () {
    if(Auth::check()){
        return redirect()->route('home');
    }else{
        return redirect()->route('login');
    }
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', function () {
        return Inertia::render('Home');
    })->name('home');

    Route::get('/funcionarios', [FuncionariosControler::class, 'list'])
        ->name('funcionarios.list');

    Route::get('/clientes', [ClientesControler::class, 'list'])
        ->name('clientes.list');


    Route::get('/servicos', [ServicosControler::class, 'list'])
        ->name('servicos.list');


    Route::get('/atendimentos', [AtendimentosControler::class, 'list'])
        ->name('atendimentos.list');
        
});