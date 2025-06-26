<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Consentimiento;
use Illuminate\Support\Carbon;

class ConsentimientoSeeder extends Seeder
{
    public function run(): void
    {
        Consentimiento::create([
            'ID_Alumno' => '100009', // Matrícula válida en tu tabla alumnos
            'ID_Paciente' => 2, // Asegúrate de tener este paciente
            'ID_Expediente' => 1, // Expediente existente
            'fecha' => Carbon::now()->toDateString(),

            'declaracion' => 'Estoy de acuerdo con el tratamiento propuesto.',
            'descripcion_tratamiento' => 'Extracción de muela del juicio con anestesia local.',
            'alumno_tratante' => 'Juan Pérez Gómez',
            'docentes' => 'Dr. Roberto Márquez, Dra. Lucía Torres',

            // Firmas vacías
            'firma_paciente' => null,
            'firma_alumno' => null,
            'firma_docentes' => null,
            'firma_testigo' => null,
        ]);
    }
}
