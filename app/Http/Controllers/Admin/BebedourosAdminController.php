<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Bebedouro;
// use App\Models\Consumo;
use Carbon\Carbon;

class BebedourosAdminController extends Controller
{
    public function index(Request $request)
    {
        // Exemplo de busca
        $search = $request->input('search');

        // Lógica de busca fictícia (Adapte para o seu Model)
        /*
        $bebedouros = Bebedouro::when($search, function($query, $search) {
            return $query->where('nome', 'like', "%{$search}%")
                         ->orWhere('esp_id', 'like', "%{$search}%");
        })->paginate(10);
        */

        // Dados simulados para a View (Substitua pela query acima)
        $bebedouros = collect([
            (object)[
                'id' => 1,
                'nome' => 'Refeitório Principal',
                'esp_id' => 'ESP-32-A1B2',
                'status' => 'online',
                'sinal_wifi' => -55, // dBm
                'ultima_comunicacao' => Carbon::now()->subMinutes(2),
                'consumo_hoje' => 45.5 // Litros
            ],
            (object)[
                'id' => 2,
                'nome' => 'Corredor Bloco B',
                'esp_id' => 'ESP-8266-C3D4',
                'status' => 'offline',
                'sinal_wifi' => -85,
                'ultima_comunicacao' => Carbon::now()->subHours(4),
                'consumo_hoje' => 12.0
            ]
        ]);

        // Dados para os cards superiores
        $dadosBebedouros = [
            'total_ativos' => 15,
            'alertas_conexao' => 1,
            'consumo_total_hoje' => 158.5 // Litros
        ];

        // Dados para o Gráfico (Exemplo dos últimos 7 dias)
        $chartData = [
            'labels' => ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
            'data' => [120, 150, 140, 180, 160, 90, 80]
        ];

        return view('admin.bebedourosAdmin', compact('bebedouros', 'dadosBebedouros', 'chartData'));
    }
}