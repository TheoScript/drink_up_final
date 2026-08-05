<?php

namespace App\Http\Controllers\Admin\Usuarios;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EdicaoUsuariosController extends Controller
{
    /**
     * Exibe a tela de edição do usuário
     */
    public function edit($id)
    {
        // Busca o usuário no banco pelo ID ou retorna erro 404
        $usuario = Usuario::findOrFail($id);

        // Retorna a view de edição passando os dados do usuário
        return view('admin.usuarios.edicao', compact('usuario'));
    }

    /**
     * Recebe os dados do formulário e atualiza no banco
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        // 1. Validação dos Dados
        $request->validate([
            'nome'     => 'required|string|max:255',
            'nivel'    => 'required|in:admin,usuario',
            // O RFID é opcional, mas se informado, deve ser único (ignorando o usuário atual)
            'rfid_uid' => 'nullable|string|max:50|unique:usuarios,rfid_uid,' . $usuario->id,
        ]);

        // 2. Atribuição dos Dados Básicos
        $usuario->nome     = $request->nome;
        $usuario->nivel    = $request->nivel;
        $usuario->rfid_uid = $request->rfid_uid;

        // 3. Atualização Segura da Senha
        // Só atualiza o hash caso o campo de senha tenha sido preenchido
        if ($request->filled('senha')) {
            $usuario->senha = Hash::make($request->senha);
        }

        // 4. Recursos Futuros (Descomente caso crie as colunas de bloqueio na migration)
        // $usuario->is_blocked = $request->input('is_blocked', 0);
        // $usuario->login_bloqueado = $request->input('login_bloqueado', 0);

        // 5. Persistência no Banco de Dados
        $usuario->save();

        // 6. Redirecionamento com Mensagem de Sucesso
        return redirect()->route('admin.usuario')
                         ->with('success', 'Usuário atualizado com sucesso!');
    }
}