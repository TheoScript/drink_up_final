<?php

use Illuminate\Support\Facades\Route;

// --- IMPORTAÇÕES DOS CONTROLLERS DO USUÁRIO ---
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RegistroAguaController;

// --- IMPORTAÇÕES DOS CONTROLLERS DO ADMIN ---
use App\Http\Middleware\AdminAutenticado;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PerfilAdminController;
use App\Http\Controllers\Admin\ConfiguracaoController;
use App\Http\Controllers\Admin\RelatorioController;
use App\Http\Controllers\Admin\UsuarioAdminController;


// =======================================================
// ROTAS DO USUÁRIO COMUM (SEU APP ORIGINAL)
// =======================================================

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

// Resources do Usuário Comum
Route::resource('usuarios', UsuarioController::class);
Route::resource('registros-agua', RegistroAguaController::class);


// =======================================================
// ROTAS DO PAINEL ADMINISTRATIVO (DRINKUP ADMIN)
// =======================================================

// Área Deslogada do Admin (Login)
Route::prefix('admin')->controller(AdminLoginController::class)->group(function () {
    Route::get('/login', 'mostrarLogin')->name('admin.login');
    Route::post('/login', 'logar')->name('admin.login.submit');
    Route::post('/logout', 'logout')->name('admin.logout');
});

// Área Logada e Protegida do Admin (Exige o Middleware)
Route::prefix('admin')->middleware([AdminAutenticado::class])->name('admin.')->group(function () {
    
    // Dashboard Principal (/admin)
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Gerenciamento de Perfil do Admin (/admin/perfil)
    Route::controller(PerfilAdminController::class)->group(function () {
        Route::get('/perfil', 'index')->name('perfil');
        Route::put('/perfil/salvar', 'salvar')->name('perfil.salvar');
        Route::put('/perfil/senha', 'alterarSenha')->name('perfil.senha');
    });

    // Configurações Gerais (/admin/configuracoes)
    Route::get('/configuracoes', [ConfiguracaoController::class, 'index'])->name('configuracoes');

    // Relatórios (/admin/relatorios)
    Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios');

    // ROTA PARA O GERENCIAMENTO DE USUÁRIOS
    Route::get('usuario', [UsuarioAdminController::class, 'index'])->name('usuario');

});
