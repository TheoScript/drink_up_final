<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'sexo',
        'idade',
        'peso',
        'meta_diaria',
    ];

    protected $hidden = ['senha'];

    public function registrosAgua()
    {
        return $this->hasMany(RegistroAgua::class, 'usuario_id');
    }
}
