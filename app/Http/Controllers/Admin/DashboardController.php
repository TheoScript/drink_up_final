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
        // Define o idioma das datas para Português
        Carbon::setLocale('pt_BR');

        // 1. MÉTRICAS DOS CARDS SUPERIORES
        // --- A. Consumo de Litros (Hoje e Delta) ---
        
        $totalMililitrosHoje = RegistroAgua::whereDate('data_registro', Carbon::today())->sum('quantidade_ml');
        $litrosHoje = number_format($totalMililitrosHoje / 1000, 3, ',', '.'); 

        $totalMililitrosOntem = RegistroAgua::whereDate('data_registro', Carbon::yesterday())->sum('quantidade_ml');

        if ($totalMililitrosOntem > 0) {
            $variacaoLitros = (($totalMililitrosHoje - $totalMililitrosOntem) / $totalMililitrosOntem) * 100;
                $litrosDelta = ($variacaoLitros >= 0 ? '+' : '') . number_format($variacaoLitros, 1, ',', '.') . '%';
        } else {
            $litrosDelta = $totalMililitrosHoje > 0 ? '+100%' : '0%';
        }
        
        $usuariosAtivos = Usuario::where('nivel', 'comum')->count();

        $dadosCards = [
            'litros_hoje'      => $litrosHoje,
            'litros_delta'     => $litrosDelta,
            'usuarios_ativos'  => $usuariosAtivos,
            'usuarios_delta'   => "+5,1%",
            'bebedouros_on'    => 186,
            'bebedouros_total' => 192,
            'bebedouros_delta' => "-1,2%",
            'alertas_qtde'     => 7,
            'alertas_delta'    => "+3"
        ];

        // 2. DADOS PARA OS GRÁFICOS (CHART.JS)
        
        // --- Gráfico de Linha: Consumo dos últimos 7 dias ---
        $labelsGraficoLinha = [];
        $dadosGraficoLinha = [];
        
        for ($i = 6; $i >= 0; $i--) {
            // Pega o dia exato subtraindo $i dias de hoje
            $data = Carbon::today()->subDays($i);
            
            // Cria o rótulo do eixo X (Ex: "Seg", "Ter") com a primeira letra maiúscula
            $labelsGraficoLinha[] = ucfirst($data->translatedFormat('D'));
            
            // Faz a soma de mililitros no banco para esta data específica
            $somaDia = RegistroAgua::whereDate('data_registro', $data)->sum('quantidade_ml') / 1000;
            $dadosGraficoLinha[] = $somaDia;
        }

        // --- Gráfico de Barra: Horários de pico de HOJE (De 2 em 2 horas) ---
$labelsGraficoBarra = [];
        $dadosGraficoBarra = [];
        
        // varre o dia todo: de 00:00 até as 22:00 (saltando de 2 em 2)
        for ($hora = 0; $hora <= 23; $hora += 2) {
            // Formata o rótulo (Ex: "00h", "02h", ..., "22h")
            $horaFormatada = str_pad($hora, 2, '0', STR_PAD_LEFT);
            $labelsGraficoBarra[] = $horaFormatada . 'h';

            // Cria uma "janela de tempo" para a consulta (Ex: de 08:00:00 até 09:59:59)
            $inicioJanela = Carbon::today()->setHour($hora)->setMinute(0)->setSecond(0);
            $fimJanela = Carbon::today()->setHour($hora + 1)->setMinute(59)->setSecond(59);

            // Conta quantos registros ocorreram dentro dessa janela de tempo
            $qtdeRegistros = RegistroAgua::whereBetween('data_registro', [$inicioJanela, $fimJanela])->count();
            
            $dadosGraficoBarra[] = $qtdeRegistros;
        }

        // 3. ATIVIDADES RECENTES
        $atividadesBanco = RegistroAgua::orderBy('data_registro', 'desc')
            ->take(3)
            ->get();

        $atividadesRecentes = [];
        foreach ($atividadesBanco as $registro) {
            $atividadesRecentes[] = [
                'texto' => "Acionamento realizado (" . $registro->quantidade_ml . "ml)",
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

        // Retornamos TODAS as variáveis para a view (incluindo as novas labels)
        return view('admin.dashboardAdmin', compact(
            'dadosCards', 
            'labelsGraficoLinha', // Nova variável
            'dadosGraficoLinha', 
            'labelsGraficoBarra', // Nova variável
            'dadosGraficoBarra', 
            'atividadesRecentes', 
            'saudeFrota'
        ));
    }
}