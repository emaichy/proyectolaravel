<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClinicasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clinicas = [
            [
                'NombreClinica' => 'Clínica 1',
                'Ubicacion' => 'Edifico México',
                'Status' => 1
            ],
            [
                'NombreClinica' => 'Clínica 2',
                'Ubicacion' => 'A un costado de coordinación',
                'Status' => 1
            ],
            [
                'NombreClinica' => 'Clínica 3',
                'Ubicacion' => 'Atrás de edificio México',
                'Status' => 1
            ],
            [
                'NombreClinica' => 'Clínica 4',
                'Ubicacion' => 'Edifico México Piso 1',
                'Status' => 1
            ],
            [
                'NombreClinica' => 'Clínica 5',
                'Ubicacion' => 'Edifico México Piso 2',
                'Status' => 1
            ],
        ];

        DB::table('clinicas')->insert($clinicas);
    }
}
