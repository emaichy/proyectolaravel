<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SemestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $semestres = [
            ['Semestre' => 'Primero', 'Status' => 1],
            ['Semestre' => 'Segundo', 'Status' => 1],
            ['Semestre' => 'Tercero', 'Status' => 1],
            ['Semestre' => 'Cuarto', 'Status' => 1],
            ['Semestre' => 'Quinto', 'Status' => 1],
            ['Semestre' => 'Sexto', 'Status' => 1],
            ['Semestre' => 'Séptimo', 'Status' => 1],
            ['Semestre' => 'Octavo', 'Status' => 1],
            ['Semestre' => 'Noveno', 'Status' => 1],
            ['Semestre' => 'Décimo', 'Status' => 1],
            ['Semestre' => 'Undécimo', 'Status' => 1],
            ['Semestre' => 'Duodécimo', 'Status' => 1]
        ];

        DB::table('semestres')->insert($semestres);
    }
}
