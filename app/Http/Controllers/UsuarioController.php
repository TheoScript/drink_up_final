<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::orderBy('id', 'desc')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email',
            'senha' => 'required|min:3'
        ]);

        Usuario::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => Hash::make($request->senha),
            'sexo' => $request->sexo,
            'idade' => $request->idade,
            'peso' => $request->peso,
            'meta_diaria' => $request->meta_diaria ?? 2000
        ]);

        return redirect()->route('usuarios.index');
    }

    public function show(Usuario $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    public function edit(Usuario $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nome' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email,' . $usuario->id
        ]);

        $dados = $request->only(['nome', 'email', 'sexo', 'idade', 'peso', 'meta_diaria']);

        if ($request->filled('senha')) {
            $dados['senha'] = Hash::make($request->senha);
        }

        $usuario->update($dados);

        return redirect()->route('usuarios.index');
    }

    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return redirect()->route('usuarios.index');
    }
}
