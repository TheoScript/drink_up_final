<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\RegistroAgua;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. MÉTRICAS DOS CARDS SUPERIORES
        
        // Ajustado para usar a sua coluna 'quantidade_ml' e filtrar pela sua 'data_registro'
        $totalMililitrosHoje = RegistroAgua::whereDate('data_registro', Carbon::today())->sum('quantidade_ml');
        $litrosHoje = number_format($totalMililitrosHoje / 1000, 3, ',', '.'); // Ex: 2,620
        
        // Conta usuários comuns
        $usuariosAtivos = Usuario::where('nivel', 'comum')->count();

        $dadosCards = [
            'litros_hoje'      => $litrosHoje,
            'litros_delta'     => "+12,4%",
            'usuarios_ativos'  => $usuariosAtivos,
            'usuarios_delta'   => "+5,1%",
            'bebedouros_on'    => 186,
            'bebedouros_total' => 192,
            'bebedouros_delta' => "-1,2%",
            'alertas_qtde'     => 7,
            'alertas_delta'    => "+3"
        ];

        // 2. DADOS PARA OS GRÁFICOS (CHART.JS)
        
        // Gráfico de Linha: Consumo dos últimos 7 dias (usando suas colunas)
        $dadosGraficoLinha = [];
        for ($i = 6; $i >= 0; $i--) {
            $data = Carbon::today()->subDays($i);
            // Buscando pela 'data_registro' e somando 'quantidade_ml'
            $somaDia = RegistroAgua::whereDate('data_registro', $data)->sum('quantidade_ml') / 1000;
            $dadosGraficoLinha[] = $somaDia;
        }

        $dadosGraficoBarra = [45, 78, 120, 95, 110, 64, 28];

        // 3. ATIVIDADES RECENTES
        // Ordena pela sua coluna de data para pegar os últimos registros
        $atividadesBanco = RegistroAgua::with('usuario')
            ->orderBy('data_registro', 'desc')
            ->take(3)
            ->get();

        $atividadesRecentes = [];
        foreach ($atividadesBanco as $registro) {
            $atividadesRecentes[] = [
                // Ajustado para 'quantidade_ml' e usando o Carbon (graças ao seu cast!) para formatar o tempo
                'texto' => ($registro->usuario->nome ?? 'Usuário') . " bebeu " . $registro->quantidade_ml . "ml",
                'tempo' => $registro->data_registro->diffForHumans() 
            ];
        }

        if (empty($atividadesRecentes)) {
            $atividadesRecentes = [
                ['texto' => 'Filtro médio (#B-204)', 'tempo' => '4 min'],
                ['texto' => 'Novo usuário cadastrado', 'tempo' => '12 min'],
                ['texto' => 'Bebedouro online (#B-118)', 'tempo' => '38 min'],
            ];
        }

        $saudeFrota = [
            'filtros_bons' => 74,
            'manutencao'   => 22,
            'pressao_ok'   => 92,
            'falhas'       => 6
        ];

        return view('admin.dashboardAdmin', compact('dadosCards', 'dadosGraficoLinha', 'dadosGraficoBarra', 'atividadesRecentes', 'saudeFrota'));
    }
}