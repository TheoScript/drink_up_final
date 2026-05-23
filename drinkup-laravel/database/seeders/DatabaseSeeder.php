<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'nome' => 'Administrador',
                'senha' => Hash::make('123'),
                'sexo' => null,
                'idade' => null,
                'peso' => null,
                'meta_diaria' => 2000,
                'nivel' => 'admin'
            ]
        );
    }
}
