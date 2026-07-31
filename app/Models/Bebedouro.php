<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bebedouro extends Model
{
    // Informa explicitamente o nome da tabela no banco
    protected $table = 'bebedouros';

    // Libera essas colunas para serem salvas via API
    protected $fillable = [
        'usuario_id',
        'mac_address',
        'nome',
        'status_online'
    ];

    // Diz que este bebedouro pertence a um usuário
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Diz que este bebedouro tem vários registros de água
    public function registros()
    {
        return $this->hasMany(RegistroAgua::class, 'bebedouro_id');
    }
}