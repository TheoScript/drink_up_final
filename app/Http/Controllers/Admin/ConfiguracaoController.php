<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfiguracaoController extends Controller
{
    /**
     * Exibe a página de configurações gerais do painel
     */
    public function index()
    {
        // Informações estáticas sobre o ecossistema do servidor
        $infoSistema = [
            'versao' => 'DrinkUp Admin v3.4.2',
            'ultima_atualizacao' => '03/05/2026 14:21',
            'ambiente' => 'Produção (XAMPP/Laragon)',
            'regiao' => 'South America (São Paulo)',
        ];

        // Simulando a listagem de equipamentos IoT/Bebedouros integrados.
        // Futuramente você poderá puxar isso dinamicamente usando: Equipment::count() ou semelhante.
        $integracoes = [
            ['name' => 'Bebedouros AquaIoT v2', 'status' => 'online', 'count' => 142],
            ['name' => 'Sensores Hydra Flow', 'status' => 'online', 'count' => 38],
            ['name' => 'Filtros PureFlow Carbon', 'status' => 'offline', 'count' => 6]
        ];

        return view('admin.configuracoes', compact('infoSistema', 'integracoes'));
    }
}