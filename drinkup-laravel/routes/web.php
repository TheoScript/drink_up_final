<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RegistroAguaController;

Route::get('/', [AuthController::class, 'welcome'])->name('welcome');

Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.entrar');

Route::get('/cadastro', [AuthController::class, 'mostrarCadastro'])->name('cadastro');
Route::post('/cadastro', [AuthController::class, 'cadastro'])->name('cadastro.salvar');

Route::post('/sair', [AuthController::class, 'sair'])->name('sair');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/adicionar-agua', [DashboardController::class, 'adicionarAgua'])->name('agua.adicionar');
Route::post('/excluir-agua', [DashboardController::class, 'excluirAgua'])->name('agua.excluir');

Route::get('/configuracao', [DashboardController::class, 'configuracao'])->name('configuracao');
Route::post('/atualizar-meta', [DashboardController::class, 'atualizarMeta'])->name('meta.atualizar');
Route::post('/atualizar-perfil', [DashboardController::class, 'atualizarPerfil'])->name('perfil.atualizar');

// Rota extra para evitar erro caso alguma tela antiga ainda chame configuracao.atualizar
Route::post('/configuracao/atualizar', [DashboardController::class, 'atualizarPerfil'])->name('configuracao.atualizar');

Route::resource('usuarios', UsuarioController::class);
Route::resource('registros-agua', RegistroAguaController::class);
