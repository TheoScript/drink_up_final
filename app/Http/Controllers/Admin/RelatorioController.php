<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegistroAgua; // Importando a Model
use Carbon\Carbon; // Importando o Carbon para manipular datas

class RelatorioController extends Controller
{
    /**
     * Exibe o painel de relatórios analíticos
     */
    public function index()
    {
        // 1. Dados dos cartões de métricas superiores (ainda estáticos no exemplo)
        $metricas = [
            'total_consumido' => '6.320 L',
            'gastos'          => 'R$ 1.892',
            'acionamentos'    => '14.382',
            'tempo_medio'     => '11.6 s'
        ];

        // =========================================================
        // LÓGICA DINÂMICA: GRÁFICO DE CONSUMO MENSAL (LINHAS)
        // =========================================================
        
        $mesesGrafico = [];
        $valoresGrafico = [];

        $nomesMeses = [
            1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr', 5 => 'Mai', 6 => 'Jun',
            7 => 'Jul', 8 => 'Ago', 9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'
        ];

        // Loop para pegar os últimos 6 meses (de trás para frente para o gráfico ficar na ordem correta)
        for ($i = 5; $i >= 0; $i--) {
            $data = Carbon::now()->subMonths($i);
            $mesAtual = $data->format('n');
            $anoAtual = $data->format('Y');

            // Usa a Model para somar os ML do mês e ano correspondentes
            $totalMesMl = RegistroAgua::whereMonth('data_registro', $mesAtual)
                                      ->whereYear('data_registro', $anoAtual)
                                      ->sum('quantidade_ml');

            // Converção de ML para Litros
            $totalMesLitros = $totalMesMl > 0 ? round($totalMesMl / 1000, 2) : 0;

            $mesesGrafico[] = $nomesMeses[$mesAtual];
            $valoresGrafico[] = $totalMesLitros;
        }

        // =========================================================
        // 2. Estruturação dos dados enviados aos gráficos
        // =========================================================
        $graficos = [
            'linhas' => [
                'meses'   => $mesesGrafico,   // Ex: ['Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul']
                'valores' => $valoresGrafico  // Ex: [150.5, 200.0, 180.2, 0, 320.1, 400.0] (Em Litros)
            ],
            'pizza' => [
                'dados' => [132, 38, 16]
            ],
            'barras' => [
                'labels'  => ["B1", "B2", "B3", "B4"],
                'valores' => [184, 220, 142, 305]
            ]
        ];

        // 3. Dados da tabela
        $dispositivos = [
            ['id' => 'B-001', 'local' => 'Sede central', 'litros' => 184, 'acionamentos' => '412', 'tempo' => '10.2s', 'status' => 'OK'],
            ['id' => 'B-002', 'local' => 'Sede pátio', 'litros' => 220, 'acionamentos' => '518', 'tempo' => '12.1s', 'status' => 'OK'],
            ['id' => 'B-004', 'local' => 'Almoxarifado', 'litros' => 305, 'acionamentos' => '890', 'tempo' => '11.4s', 'status' => 'Manutenção'],
        ];

        return view('admin.relatoriosAdmin', compact('metricas', 'graficos', 'dispositivos'));
    }
}