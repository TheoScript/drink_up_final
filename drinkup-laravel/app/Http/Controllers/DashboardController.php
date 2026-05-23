<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use App\Models\RegistroAgua;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private function usuarioLogado()
    {
        if (!session('usuario_id')) {
            return null;
        }

        return Usuario::find(session('usuario_id'));
    }

    public function index()
    {
        $usuario = $this->usuarioLogado();

        if (!$usuario) {
            session()->flush();
            return redirect()->route('login');
        }

        $peso_usuario = $usuario->peso ?? 70;
        $meta_ideal_recomendada = round($peso_usuario * 35);
        $meta_diaria_ml = $usuario->meta_diaria ?? $meta_ideal_recomendada;

        $hoje = Carbon::today();

        $registrosHoje = RegistroAgua::where('usuario_id', $usuario->id)
            ->whereDate('data_registro', $hoje)
            ->get();

        $total_ml = $registrosHoje->sum('quantidade_ml');
        $frequencia_hoje = $registrosHoje->count();
        $media_volume_ingestao = round($registrosHoje->avg('quantidade_ml') ?? 0);

        $falta_ml = $meta_diaria_ml - $total_ml;

        if ($falta_ml < 0) {
            $falta_ml = 0;
        }

        $eficiencia_percentual = 0;

        if ($meta_diaria_ml > 0) {
            $eficiencia_percentual = ($total_ml / $meta_diaria_ml) * 100;
        }

        if ($eficiencia_percentual > 100) {
            $eficiencia_percentual = 100;
        }

        $porcentagem_faltante = 100 - $eficiencia_percentual;

        if ($porcentagem_faltante < 0) {
            $porcentagem_faltante = 0;
        }

        $historico = RegistroAgua::where('usuario_id', $usuario->id)
            ->orderBy('data_registro', 'desc')
            ->get();

        $ultimoRegistro = RegistroAgua::where('usuario_id', $usuario->id)
            ->orderBy('data_registro', 'desc')
            ->first();

        $tempo_inatividade_min = 0;

        if ($ultimoRegistro) {
            $tempo_inatividade_min = Carbon::parse($ultimoRegistro->data_registro)->diffInMinutes(now());
        }

        $total_usuarios = Usuario::count();

        $ranking_engajamento = RegistroAgua::select('usuario_id', DB::raw('SUM(quantidade_ml) as volume_total'))
            ->whereDate('data_registro', $hoje)
            ->groupBy('usuario_id')
            ->orderByDesc('volume_total')
            ->with('usuario')
            ->get();

        $labels_grafico = [];
        $dados_grafico = [];

        for ($hora = 8; $hora <= 20; $hora += 2) {
            $labels_grafico[] = str_pad($hora, 2, '0', STR_PAD_LEFT) . 'h';

            $inicio = Carbon::today()->setTime($hora, 0, 0);
            $fim = Carbon::today()->setTime($hora + 1, 59, 59);

            $totalHora = RegistroAgua::where('usuario_id', $usuario->id)
                ->whereBetween('data_registro', [$inicio, $fim])
                ->sum('quantidade_ml');

            $dados_grafico[] = $totalHora;
        }

        if ($eficiencia_percentual < 35) {
            $cor_tema = '#ff4757';
            $status_msg = 'Nível Crítico: Ação imediata requerida.';
        } elseif ($eficiencia_percentual < 70) {
            $cor_tema = '#ffa502';
            $status_msg = 'Progresso Estável: Mantenha o ritmo.';
        } else {
            $cor_tema = '#0984e3';
            $status_msg = 'Performance Ideal.';
        }

        return view('dashboard', compact(
            'usuario',
            'meta_diaria_ml',
            'peso_usuario',
            'meta_ideal_recomendada',
            'total_ml',
            'falta_ml',
            'porcentagem_faltante',
            'total_usuarios',
            'frequencia_hoje',
            'media_volume_ingestao',
            'eficiencia_percentual',
            'historico',
            'ranking_engajamento',
            'labels_grafico',
            'dados_grafico',
            'tempo_inatividade_min',
            'cor_tema',
            'status_msg'
        ));
    }

    public function adicionarAgua(Request $request)
    {
        $usuario = $this->usuarioLogado();

        if (!$usuario) {
            return redirect()->route('login');
        }

        RegistroAgua::create([
            'usuario_id' => $usuario->id,
            'quantidade_ml' => $request->quantidade_ml,
            'data_registro' => now()
        ]);

        return redirect()->route('dashboard');
    }

    public function excluirAgua(Request $request)
    {
        $usuario = $this->usuarioLogado();

        if (!$usuario) {
            return redirect()->route('login');
        }

        RegistroAgua::where('id', $request->id_registro)
            ->where('usuario_id', $usuario->id)
            ->delete();

        return redirect()->route('dashboard');
    }

    public function configuracao()
    {
        $usuario = $this->usuarioLogado();

        if (!$usuario) {
            return redirect()->route('login');
        }

        return view('configuracao', compact('usuario'));
    }

    public function atualizarMeta(Request $request)
    {
        $usuario = $this->usuarioLogado();

        if (!$usuario) {
            return redirect()->route('login');
        }

        $request->validate([
            'nova_meta' => 'required|integer|min:500|max:10000'
        ]);

        $usuario->meta_diaria = $request->nova_meta;
        $usuario->save();

        return redirect()->route('dashboard')
            ->with('sucesso', 'Meta atualizada com sucesso.');
    }

    public function atualizarPerfil(Request $request)
    {
        $usuario = $this->usuarioLogado();

        if (!$usuario) {
            return redirect()->route('login');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'peso' => 'required|numeric|min:20|max:300',
            'senha' => 'nullable|min:4'
        ]);

        $usuario->nome = $request->nome;
        $usuario->email = $request->email;
        $usuario->peso = $request->peso;
        $usuario->meta_diaria = round($request->peso * 35);

        if ($request->senha) {
            $usuario->senha = Hash::make($request->senha);
        }

        $usuario->save();

        return redirect()->route('dashboard')
            ->with('sucesso', 'Perfil atualizado com sucesso.');
    }
}
