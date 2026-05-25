<?php

// IMPORTAÇÕES DOS CONTROLLERS DO USUÁRIO
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RegistroAguaController;

// IMPORTAÇÕES DOS CONTROLLERS DO ADMIN 
use App\Http\Middleware\AdminAutenticado;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdmindashboardController;
use App\Http\Controllers\Admin\PerfilAdminController;
use App\Http\Controllers\Admin\ConfiguracaoController;
use App\Http\Controllers\Admin\RelatorioController;
use App\Http\Controllers\Admin\UsuariosController as AdminUsuariosController;


// --- ROTAS DO USUÁRIO COMUM (SEU APP ORIGINAL) ---

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

// --- ROTAS DO PAINEL ADMINISTRATIVO (DRINKUP ADMIN) ---

// Rotas de autenticação do Admin (Abertas)
Route::get('/admin/login', [LoginController::class, 'mostrarLogin'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'logar'])->name('admin.login.submit');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

// --- PAINEL DO ADMIN PROTEGIDO (Com Middleware) ---
// Login/Logout do Administrador (Fora do Middleware de Proteção)
Route::prefix('admin')->controller(LoginController::class)->group(function () {
    Route::get('/login', 'mostrarLogin')->name('admin.login');
    Route::post('/login', 'logar')->name('admin.login.submit');
    Route::post('/logout', 'logout')->name('admin.logout');
});

// Área Logada e Protegida do Admin (Compartilha Prefixo 'admin' e Middleware de Autenticação)
Route::prefix('admin')->middleware([AdminAutenticado::class])->group(function () {
    
    // Index/Dashboard Principal
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Gerenciamento de Perfil do Admin
    Route::controller(PerfilAdminController::class)->group(function () {
        Route::get('/perfil', 'index')->name('admin.perfil');
        Route::put('/perfil/salvar', 'salvar')->name('admin.perfil.salvar');
        Route::put('/perfil/senha', 'alterarSenha')->name('admin.perfil.senha');
    });

    // Configurações Gerais do Painel
    Route::get('/configuracoes', [ConfiguracaoController::class, 'index'])->name('admin.configuracoes');

    // Relatórios Analíticos (Consumo/Dispositivos)
    Route::get('/relatorios', [RelatorioController::class, 'index'])->name('admin.relatorios');
});
