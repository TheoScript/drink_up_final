<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroAgua extends Model
{
    protected $table = 'registros_agua';

    protected $fillable = [
        'usuario_id',
        'quantidade_ml',
        'data_registro',
    ];

    protected $casts = [
        'data_registro' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
