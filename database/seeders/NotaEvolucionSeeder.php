<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotaEvolucion;
use Illuminate\Support\Carbon;

class NotaEvolucionSeeder extends Seeder
{
    public function run(): void
    {
        NotaEvolucion::create([
            'ID_Alumno' => '100009', // Usa una matrícula real de prueba
            'ID_Paciente' => 2, // Asegúrate que el paciente exista
            'ID_Expediente' => 1,
            'ID_Semestre' => 1,
            'ID_Grupo' => 1,
            'fecha' => Carbon::now()->toDateString(),

            'presion_arterial' => '120/80',
            'frecuencia_cardiaca' => '75',
            'frecuencia_respiratoria' => '18',
            'temperatura' => '36.5',
            'oximetria' => '98',

            'tratamiento_realizado' => 'Limpieza dental general',
            'descripcion_tratamiento' => 'Se realizó limpieza con ultrasonido y pulido con pasta profiláctica.',

            'firma_alumno' => null,
            'firma_paciente' => null,
            'firma_catedratico' => null,
        ]);
    }
}
