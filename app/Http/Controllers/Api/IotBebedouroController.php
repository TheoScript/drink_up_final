<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bebedouro;
use App\Models\Usuario;
use App\Models\RegistroAgua;

class IotBebedouroController extends Controller
{
    /**
     * Recebe os dados de consumo de água (Gatilho da bomba)
     */
    public function receberConsumo(Request $request)
    {
        $request->validate([
        'mac_address' => 'required|string',
        'volume'      => 'required|numeric|min:1',
        'rfid'        => 'required|string'
        ]);

        $mac = $request->input('mac_address');
        $volume = $request->input('volume');
        $rfid = $request->input('rfid');

        // 1. Busca o bebedouro no banco
        $bebedouro = Bebedouro::where('mac_address', $mac)->first();
        if (!$bebedouro) {
            return response()->json(['erro' => 'Máquina não reconhecida'], 404);
        }

        // Já atualiza que a máquina está online, pois acabou de se comunicar
        $bebedouro->status_online = true;
        $bebedouro->save();

        // 2. Busca o usuário dono do cartão RFID
        $usuario = Usuario::where('rfid_uid', $rfid)->first();
        if (!$usuario) {
            return response()->json(['erro' => 'Cartão RFID não cadastrado no sistema'], 404);
        }

        // 3. Registra a água no diário do usuário
        RegistroAgua::create([
            'usuario_id' => $usuario->id,
            'bebedouro_id' => $bebedouro->id,
            'quantidade_ml' => $volume,
            'origem' => 'iot_bebedouro', // Para você saber que veio da máquina e não do site
            'data_registro' => now(),
        ]);

        return response()->json(['sucesso' => 'Água registrada com sucesso!'], 201);
    }

    /**
     * Recebe o sinal de vida (Heartbeat de 5 em 5 minutos)
     */
    public function ping(Request $request)
    {
        $mac = $request->input('mac_address');

        $bebedouro = Bebedouro::where('mac_address', $mac)->first();

        if ($bebedouro) {
            // Se o bebedouro já existe, apenas atualizamos o status
            $bebedouro->status_online = true;
            $bebedouro->save();
            
            return response()->json(['sucesso' => 'Ping atualizado']);
        }

        // AUTO-CADASTRO: Se a máquina não existe no banco, nós a criamos automaticamente!
        // Isso facilita muito quando você for instalar o bebedouro nº 2, 3, 4...
        Bebedouro::create([
            'mac_address' => $mac,
            'nome' => 'Bebedouro Novo (' . substr($mac, -5) . ')', // Ex: Bebedouro Novo (A1:B2)
            'status_online' => true
        ]);

        return response()->json(['sucesso' => 'Novo bebedouro detectado e cadastrado!'], 201);
    }
}