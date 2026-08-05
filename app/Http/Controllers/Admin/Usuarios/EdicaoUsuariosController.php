<?php

namespace App\Http\Controllers;

use App\Models\Usuario; // Certifique-se de que o Model está importado
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Necessário para criptografar a senha

class EdicaoUsuariosController extends Controller
{
    // ... (suas outras funções como index, create, etc.)

    /**
     * Exibe a tela de edição do usuário
     */
    public function edit($id)
    {
        // Busca o usuário no banco pelo ID. Se não achar, devolve erro 404.
        $usuario = Usuario::findOrFail($id);

        // Retorna a view seguindo a boa prática de pastas que conversamos
        return view('admin.usuarios.edicao', compact('usuario'));
    }

    /**
     * Recebe os dados do formulário e atualiza no banco
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        // 1. Validação de Segurança
        // Garante que o sistema não quebre com dados inválidos
        $request->validate([
            'nome' => 'required|string|max:255',
            'nivel' => 'required|in:admin,usuario',
            // O RFID não é obrigatório, mas se for preenchido, deve ser único (ignorando o dono atual)
            'rfid_uid' => 'nullable|string|max:50|unique:usuarios,rfid_uid,' . $usuario->id,
        ]);

        // 2. Atualiza os dados básicos
        $usuario->nome = $request->nome;
        $usuario->nivel = $request->nivel;
        $usuario->rfid_uid = $request->rfid_uid;

        // 3. Regra Inteligente da Senha
        // Só criptografa e salva se o admin digitou algo no campo
        if ($request->filled('senha')) {
            // ATENÇÃO: Se a sua coluna no banco se chamar 'password', troque 'senha' por 'password' abaixo
            $usuario->senha = Hash::make($request->senha); 
        }

        // 4. Preparação para o Futuro (Bloqueios)
        // Como conversamos que isso será implementado depois, se as colunas 'is_blocked' 
        // e 'login_bloqueado' ainda não existirem no seu banco de dados, 
        // adicione duas barras "//" no início destas duas linhas abaixo para não dar erro:
        //$usuario->is_blocked = $request->input('is_blocked', 0);
        //$usuario->login_bloqueado = $request->input('login_bloqueado', 0);

        // 5. Salva tudo no banco de dados
        $usuario->save();

        // 6. Redireciona de volta para a tabela com uma mensagem de sucesso
        return redirect()->route('admin.usuario')
                         ->with('success', 'Usuário atualizado com sucesso!');
    }
}