<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MateriaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('materias')->insert([
            [
                'id' => (string) Str::uuid(),
                'titulo' => 'Primeira Matéria',
                'descricao' => 'Descrição da primeira matéria.',
                'imagem' => 'storage/imagens/image_one.jpg',
                'texto_completo' => 'Texto completo da primeira matéria.',
                'data_de_publicacao' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'titulo' => 'Segunda Matéria',
                'descricao' => 'Descrição da segunda matéria.',
                'imagem' => 'storage/imagens/image_two.jpg',
                'texto_completo' => 'Texto completo da segunda matéria.',
                'data_de_publicacao' => now(),
            ]
        ]);
    }
}
