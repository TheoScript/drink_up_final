<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    // Exibe a tela de login
    public function mostrarLogin()
    {
        // Se o admin já estiver logado na sessão, joga ele direto para a dashboard
        if (Session::has('admin_logado')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    // Processa o formulário de login
    public function logar(Request $request)
    {
        // Validação básica dos campos enviados
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required',
        ]);

        // Busca o usuário pelo e-mail
        $usuario = Usuario::where('email', $request->email)->first();

        // Verifica se o usuário existe, se a senha está correta e se ele é ADMIN
        if ($usuario && Hash::check($request->senha, $usuario->senha)) {
            if ($usuario->nivel === 'admin') {
                
                // Salva o ID e o nome dele na sessão para indicar que está autenticado
                Session::put('admin_logado', true);
                Session::put('admin_id', $usuario->id);
                Session::put('admin_nome', $usuario->nome);

                return redirect()->route('admin.dashboard');
            }
        }

        // Se falhar, volta para a tela de login com uma mensagem de erro
        return redirect()->back()
            ->withInput($request->only('email')) // Mantém o e-mail preenchido
            ->withErrors(['erro' => 'E-mail, senha incorretos ou acesso não autorizado.']);
    }

    // Remove a sessão e desloga o administrador
    public function logout()
    {
        Session::forget(['admin_logado', 'admin_id', 'admin_nome']);
        return redirect()->route('admin.login');
    }
}