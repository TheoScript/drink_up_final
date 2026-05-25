<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    /**
     * Exibe o painel de relatórios analíticos
     */
    public function index()
    {
        // 1. Dados dos cartões de métricas superiores
        $metricas = [
            'total_consumido' => '6.320 L',
            'gastos'          => 'R$ 1.892',
            'acionamentos'    => '14.382',
            'tempo_medio'     => '11.6 s'
        ];

        // 2. Estruturação dos dados enviados aos gráficos (utiliza diretiva @json na view)
        $graficos = [
            'linhas' => [
                'meses'   => ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun"],
                'valores' => [4200, 4600, 5100, 5400, 5980, 6320]
            ],
            'pizza' => [
                'dados' => [132, 38, 16] // Filtros: Bom, Médio, Ruim
            ],
            'barras' => [
                'labels'  => ["B1", "B2", "B3", "B4"],
                'valores' => [184, 220, 142, 305]
            ]
        ];

        // 3. Dados que alimentarão a tabela de dispositivos através do @foreach
        $dispositivos = [
            ['id' => 'B-001', 'local' => 'Sede central', 'litros' => 184, 'acionamentos' => '412', 'tempo' => '10.2s', 'status' => 'OK'],
            ['id' => 'B-002', 'local' => 'Sede pátio', 'litros' => 220, 'acionamentos' => '518', 'tempo' => '12.1s', 'status' => 'OK'],
            ['id' => 'B-004', 'local' => 'Almoxarifado', 'litros' => 305, 'acionamentos' => '890', 'tempo' => '11.4s', 'status' => 'Manutenção'],
        ];

        return view('admin.relatoriosAdmin', compact('metricas', 'graficos', 'dispositivos'));
    }
}