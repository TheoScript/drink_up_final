<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IotBebedouroController;

// Rota para salvar a água bebida
Route::post('/bebedouro/consumo', [IotBebedouroController::class, 'receberConsumo']);

// Rota para avisar que a máquina está ligada (Heartbeat)
Route::post('/bebedouro/ping', [IotBebedouroController::class, 'ping']);