<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function mostrarLogin()
    {
        return view('login');
    }

    public function mostrarCadastro()
    {
        return view('cadastro');
    }

    public function cadastro(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email',
            'senha' => 'required|min:3',
            'sexo' => 'nullable|string|max:20',
            'idade' => 'nullable|integer|min:0',
            'peso' => 'nullable|numeric|min:0'
        ]);

        $peso = $request->peso ?? 0;
        $metaDiaria = $peso > 0 ? $peso * 35 : 2000;

        $usuario = Usuario::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => Hash::make($request->senha),
            'sexo' => $request->sexo,
            'idade' => $request->idade,
            'peso' => $peso,
            'meta_diaria' => $metaDiaria,
            'nivel' => 'cliente'
        ]);

        session([
            'usuario_id' => $usuario->id,
            'usuario_nome' => $usuario->nome,
            'usuario_nivel' => $usuario->nivel
        ]);

        return redirect()->route('dashboard');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required'
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if ($usuario && Hash::check($request->senha, $usuario->senha)) {

            session([
                'usuario_id' => $usuario->id,
                'usuario_nome' => $usuario->nome,
                'usuario_nivel' => $usuario->nivel ?? 'cliente'
            ]);

            return redirect()->route('dashboard');
        }

        return back()->with('erro', 'E-mail ou senha incorretos.');
    }

    public function sair()
    {
        session()->flush();

        return redirect()->route('login');
    }
}