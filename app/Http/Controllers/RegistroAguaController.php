<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroAgua;
use App\Models\Usuario;

class RegistroAguaController extends Controller
{
    public function index()
    {
        $registros = RegistroAgua::with('usuario')->orderBy('data_registro', 'desc')->get();
        return view('registros_agua.index', compact('registros'));
    }

    public function create()
    {
        $usuarios = Usuario::orderBy('nome')->get();
        return view('registros_agua.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'quantidade_ml' => 'required|integer|min:1'
        ]);

        RegistroAgua::create([
            'usuario_id' => $request->usuario_id,
            'quantidade_ml' => $request->quantidade_ml,
            'data_registro' => $request->data_registro ?? now()
        ]);

        return redirect()->route('registros-agua.index');
    }

    public function show(RegistroAgua $registros_agua)
    {
        return view('registros_agua.show', ['registro' => $registros_agua]);
    }

    public function edit(RegistroAgua $registros_agua)
    {
        $usuarios = Usuario::orderBy('nome')->get();
        return view('registros_agua.edit', [
            'registro' => $registros_agua,
            'usuarios' => $usuarios
        ]);
    }

    public function update(Request $request, RegistroAgua $registros_agua)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'quantidade_ml' => 'required|integer|min:1'
        ]);

        $registros_agua->update([
            'usuario_id' => $request->usuario_id,
            'quantidade_ml' => $request->quantidade_ml,
            'data_registro' => $request->data_registro ?? $registros_agua->data_registro
        ]);

        return redirect()->route('registros-agua.index');
    }

    public function destroy(RegistroAgua $registros_agua)
    {
        $registros_agua->delete();
        return redirect()->route('registros-agua.index');
    }
}
