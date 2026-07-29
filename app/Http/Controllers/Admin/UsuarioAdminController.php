<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario; 
use Illuminate\Http\Request;
use Carbon\Carbon;

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
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        // Paginação mantendo os parâmetros da URL (para a busca não se perder ao trocar de página)
        $usuarios = $query->orderBy('created_at', 'desc')->paginate(10);
        $usuarios->appends($request->all());

        // Métricas para os Cards Superiores
       $dadosUsuarios = [
        // Pega todos os usuários criados a partir das 00:00:00 de hoje no fuso de SP
        'cadastrados_hoje' => Usuario::where('created_at', '>=', now()->startOfDay())->count(),
        'acessos_iot'      => Usuario::where('acesso_iot', true)->count(),
];

        // Retorna para a view do admin (Ajuste o caminho se sua pasta de views for diferente)
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
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'senha' => 'required|string|min:8',
            'acesso_iot' => 'boolean'
        ]);

        // Define acesso_iot como false caso não seja enviado no formulário
        $validated['acesso_iot'] = $request->has('acesso_iot');
        $validated['password'] = bcrypt($validated['password']);

        Usuario::create($validated);

        return redirect()->route('admin.usuarios')
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
     * Mostra o formulário de edição do usuário.
     */
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('admin.usuarios.edit', compact('usuario'));
    }

    /**
     * Atualiza os dados de um usuário existente.
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            'acesso_iot' => 'boolean'
        ]);

        $validated['acesso_iot'] = $request->has('acesso_iot');

        // Se a senha for preenchida, atualiza. Caso contrário, mantém a atual.
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        $usuario->update($validated);

        return redirect()->route('admin.usuarios')
                         ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove o usuário do banco de dados.
     */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return redirect()->route('admin.usuarios')
                         ->with('success', 'Usuário excluído com sucesso!');
    }
}