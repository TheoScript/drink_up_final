<?php

namespace App\Http\Controllers;

use App\Models\RegistroAgua;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

        $total_ml = (int) $registrosHoje->sum('quantidade_ml');
        $frequencia_hoje = $registrosHoje->count();
        $media_volume_ingestao = (int) round(
            $registrosHoje->avg('quantidade_ml') ?? 0
        );

        $falta_ml = max($meta_diaria_ml - $total_ml, 0);

        $eficiencia_percentual = 0;

        if ($meta_diaria_ml > 0) {
            $eficiencia_percentual = ($total_ml / $meta_diaria_ml) * 100;
        }

        $eficiencia_percentual = min($eficiencia_percentual, 100);
        $porcentagem_faltante = max(100 - $eficiencia_percentual, 0);

        $historico = RegistroAgua::where('usuario_id', $usuario->id)
            ->orderByDesc('data_registro')
            ->get();

        $ultimoRegistro = RegistroAgua::where('usuario_id', $usuario->id)
            ->orderByDesc('data_registro')
            ->first();

        $tempo_inatividade_min = 0;
        $tempo_inatividade_formatado = 'Nenhum registro encontrado';

        if ($ultimoRegistro) {
            $dataUltimoRegistro = Carbon::parse(
                $ultimoRegistro->data_registro
            );

            $tempo_inatividade_min = (int) floor(
                $dataUltimoRegistro->diffInMinutes(now())
            );

            if ($tempo_inatividade_min < 1) {
                $tempo_inatividade_formatado = 'Agora mesmo';
            } elseif ($tempo_inatividade_min < 60) {
                $tempo_inatividade_formatado =
                    $tempo_inatividade_min .
                    ($tempo_inatividade_min === 1
                        ? ' minuto'
                        : ' minutos');
            } elseif ($tempo_inatividade_min < 1440) {
                $horas = intdiv($tempo_inatividade_min, 60);
                $minutos = $tempo_inatividade_min % 60;

                $tempo_inatividade_formatado =
                    $horas .
                    ($horas === 1
                        ? ' hora'
                        : ' horas');

                if ($minutos > 0) {
                    $tempo_inatividade_formatado .=
                        ' e ' .
                        $minutos .
                        ($minutos === 1
                            ? ' minuto'
                            : ' minutos');
                }
            } else {
                $dias = intdiv($tempo_inatividade_min, 1440);
                $minutosRestantes = $tempo_inatividade_min % 1440;
                $horas = intdiv($minutosRestantes, 60);

                $tempo_inatividade_formatado =
                    $dias .
                    ($dias === 1
                        ? ' dia'
                        : ' dias');

                if ($horas > 0) {
                    $tempo_inatividade_formatado .=
                        ' e ' .
                        $horas .
                        ($horas === 1
                            ? ' hora'
                            : ' horas');
                }
            }
        }

        $total_usuarios = Usuario::count();

        $ranking_engajamento = RegistroAgua::select(
            'usuario_id',
            DB::raw('SUM(quantidade_ml) as volume_total')
        )
            ->whereDate('data_registro', $hoje)
            ->groupBy('usuario_id')
            ->orderByDesc('volume_total')
            ->with('usuario')
            ->get();

        $labels_grafico = [];
        $dados_grafico = [];

        for ($hora = 8; $hora <= 20; $hora += 2) {
            $labels_grafico[] =
                str_pad($hora, 2, '0', STR_PAD_LEFT) . 'h';

            $inicio = Carbon::today()->setTime($hora, 0, 0);
            $fim = Carbon::today()->setTime($hora + 1, 59, 59);

            $totalHora = RegistroAgua::where(
                'usuario_id',
                $usuario->id
            )
                ->whereBetween('data_registro', [$inicio, $fim])
                ->sum('quantidade_ml');

            $dados_grafico[] = (int) $totalHora;
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
            'tempo_inatividade_formatado',
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

        $dadosValidados = $request->validate([
            'quantidade_ml' => [
                'required',
                'integer',
                'min:1',
                'max:10000',
            ],
        ]);

        RegistroAgua::create([
            'usuario_id' => $usuario->id,
            'quantidade_ml' => $dadosValidados['quantidade_ml'],
            'data_registro' => now(),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('sucesso', 'Água registrada com sucesso!');
    }

    public function excluirAgua(Request $request)
    {
        $usuario = $this->usuarioLogado();

        if (!$usuario) {
            return redirect()->route('login');
        }

        $dadosValidados = $request->validate([
            'id_registro' => [
                'required',
                'integer',
            ],
        ]);

        RegistroAgua::where('id', $dadosValidados['id_registro'])
            ->where('usuario_id', $usuario->id)
            ->delete();

        return redirect()
            ->route('dashboard')
            ->with('sucesso', 'Registro de água excluído com sucesso!');
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

        $dadosValidados = $request->validate([
            'nova_meta' => [
                'required',
                'integer',
                'min:500',
                'max:10000',
            ],
        ]);

        $usuario->meta_diaria = $dadosValidados['nova_meta'];
        $usuario->save();

        return redirect()
            ->route('dashboard')
            ->with('sucesso', 'Meta atualizada com sucesso.');
    }

    public function atualizarPerfil(Request $request)
    {
        $usuario = $this->usuarioLogado();

        if (!$usuario) {
            return redirect()->route('login');
        }

        $dadosValidados = $request->validate([
            'nome' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'peso' => [
                'required',
                'numeric',
                'min:20',
                'max:300',
            ],
            'senha' => [
                'nullable',
                'string',
                'min:4',
            ],
        ]);

        $usuario->nome = $dadosValidados['nome'];
        $usuario->email = $dadosValidados['email'];
        $usuario->peso = $dadosValidados['peso'];
        $usuario->meta_diaria = round(
            $dadosValidados['peso'] * 35
        );

        if (!empty($dadosValidados['senha'])) {
            $usuario->senha = Hash::make(
                $dadosValidados['senha']
            );
        }

        $usuario->save();

        return redirect()
            ->route('dashboard')
            ->with('sucesso', 'Perfil atualizado com sucesso.');
    }
}