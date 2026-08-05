<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario; 
use Illuminate\Http\Request;

class UsuarioAdminController extends Controller
{
    /**
     * Exibe a listagem de usuários e as métricas da tela (Módulo Admin).
     */
    public function index(Request $request)
    {
        $query = Usuario::query();

        // Lógica de pesquisa por Nome ou ID
        if ($request->filled('search')) {
            $search = $request->input('search');
    
            $query->where(function($q) use ($search) {
                // 1. Sempre pesquisa pelo nome
                $q->where('nome', 'like', "%{$search}%");
            
                // 2. Só tenta pesquisar pelo ID se o texto digitado for um número
                if (is_numeric($search)) {
                    $q->orWhere('id', $search);
                }
            });
        }

        // Paginação mantendo os parâmetros da URL
        $usuarios = $query->orderBy('created_at', 'desc')->paginate(10);
        $usuarios->appends($request->all());

        // Métricas para os Cards Superiores
        $dadosUsuarios = [
            'cadastrados_hoje' => Usuario::where('created_at', '>=', now()->startOfDay())->count(),
            'acessos_iot' => Usuario::whereNotNull('rfid_uid')->count(),
        ];

        // Retorna para a view do admin
        return view('admin.usuariosAdmin', compact('usuarios', 'dadosUsuarios'));
    }

    /**
     * Mostra o formulário de criação de um novo usuário.
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Salva o novo usuário no banco de dados.
     */
    public function store(Request $request)
    {
        // 1. Validação corrigida para a tabela 'usuarios'
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email',
            'senha' => 'required|string|min:8',
            'rfid_uid' => 'nullable|string|max:50|unique:usuarios,rfid_uid',
        ]);

        // 2. Criptografa a senha corretamente
        $validated['senha'] = bcrypt($validated['senha']);

        // 3. Salva no banco de dados
        Usuario::create($validated);

        // 4. Redireciona para a rota no singular ('admin.usuario')
        return redirect()->route('admin.usuario')
                         ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Exibe os detalhes de um usuário específico (Opcional).
     */
    public function show($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('admin.usuarios.show', compact('usuario'));
    }

    /**
     * Remove o usuário do banco de dados.
     */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return redirect()->route('admin.usuario')
                         ->with('success', 'Usuário excluído com sucesso!');
    }
}
