<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PerfilAdminController extends Controller
{
    // Exibe a página com os dados do administrador logado
    public function index()
    {
        // NOTA: Como você ainda não implementou o Auth do Laravel, simularemos pegando o primeiro Admin do banco.
        // Assim que colocar o sistema de login, mude para: $admin = auth()->user();
        $admin = Usuario::where('nivel', '>', 1)->first();

        if (!$admin) {
            abort(404, 'Nenhum usuário administrador localizado no banco de dados.');
        }

        return view('admin.perfilAdmin', compact('admin'));
    }

    // Salva as alterações de nome, email, idade e peso
    public function salvar(Request $request)
    {
        $admin = Usuario::where('nivel', '>', 1)->first(); // Simulado

        $request->validate([
            'nome'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuarios,email,' . $admin->id,
            'idade' => 'nullable|integer|min:0',
            'peso'  => 'nullable|numeric|min:0',
        ], [
            'email.unique' => 'Este endereço de e-mail já está sendo utilizado por outro usuário.',
            'nome.required' => 'O campo nome é obrigatório.'
        ]);

        $admin->update([
            'nome'  => $request->nome,
            'email' => $request->email,
            'idade' => $request->idade,
            'peso'  => $request->peso,
        ]);

        return redirect()->route('admin.perfil')->with('sucesso', 'Informações pessoais atualizadas com sucesso!');
    }

    // Valida e altera a senha de acesso
    public function alterarSenha(Request $request)
    {
        $admin = Usuario::where('nivel', '>', 1)->first(); // Simulado

        $request->validate([
            'senha_atual' => 'required',
            'nova_senha'  => 'required|min:6|confirmed', // 'confirmed' exige o campo 'nova_senha_confirmation'
        ], [
            'nova_senha.min' => 'A nova senha precisa conter no mínimo 6 caracteres.',
            'nova_senha.confirmed' => 'A confirmação de senha não confere.'
        ]);

        // Verifica se a senha antiga confere com a salva (criptografada) no banco
        if (!Hash::check($request->senha_atual, $admin->senha)) {
            return redirect()->back()->withErrors(['senha_atual' => 'A senha atual digitada está incorreta.']);
        }

        // Atualiza a senha gerando o hash seguro de criptografia
        $admin->update([
            'senha' => Hash::make($request->nova_senha)
        ]);

        return redirect()->route('admin.perfil')->with('sucesso', 'Senha modificada com sucesso!');
    }
}