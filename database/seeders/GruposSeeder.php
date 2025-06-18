<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GruposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 11; $i++) {
            if ($i > 1 && $i < 5) {
                for ($j = 1; $j <= 5; $j++) {
                    DB::table('grupos')->insert([
                        'NombreGrupo' => "Grupo {$i}°{$j}",
                        'ID_Semestre' => $i
                    ]);
                }
            } else {
                for ($j = 1; $j <= 3; $j++) {
                    DB::table('grupos')->insert([
                        'NombreGrupo' => "Grupo {$i}°{$j}",
                        'ID_Semestre' => $i
                    ]);
                }
            }
        }
    }
}
