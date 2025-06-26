<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TratamientosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tratamientos=[
            [
                'NombreTratamiento' => 'Limpieza Dental',
                'Descripcion' => 'Eliminación de placa y sarro para prevenir enfermedades bucales.',
                'Status' => '1'
            ],
            [
                'NombreTratamiento' => 'Extracción Dental',
                'Descripcion' => 'Remoción de piezas dentales dañadas o problemáticas.',
                'Status' => '1'
            ],
            [
                'NombreTratamiento' => 'Obturación Dental',
                'Descripcion' => 'Restauración de dientes afectados por caries mediante resinas o amalgamas.',
                'Status' => '1'
            ],
            [
                'NombreTratamiento' => 'Endodoncia',
                'Descripcion' => 'Tratamiento de conductos radiculares para salvar dientes infectados.',
                'Status' => '1'
            ],
            [
                'NombreTratamiento' => 'Colocación de Coronas',
                'Descripcion' => 'Rehabilitación de dientes dañados mediante coronas dentales.',
                'Status' => '1'
            ],
            [
                'NombreTratamiento' => 'Puentes Dentales',
                'Descripcion' => 'Sustitución de dientes faltantes mediante estructuras fijas.',
                'Status' => '1'
            ],
            [
                'NombreTratamiento' => 'Implantes Dentales',
                'Descripcion' => 'Colocación de raíces artificiales para soportar prótesis dentales.',
                'Status' => '1'
            ],
            [
                'NombreTratamiento' => 'Blanqueamiento Dental',
                'Descripcion' => 'Procedimiento estético para aclarar el color de los dientes.',
                'Status' => '1'
            ],
            [
                'NombreTratamiento' => 'Ortodoncia',
                'Descripcion' => 'Corrección de la posición de los dientes mediante brackets o alineadores.',
                'Status' => '1'
            ],
            [
                'NombreTratamiento' => 'Profilaxis Dental',
                'Descripcion' => 'Limpieza profesional para prevenir enfermedades periodontales.',
                'Status' => '1'
            ],
            [
                'NombreTratamiento' => 'Cirugía de Cordales',
                'Descripcion' => 'Extracción quirúrgica de muelas del juicio.',
                'Status' => '1'
            ],
            [
                'NombreTratamiento' => 'Tratamiento de Encías',
                'Descripcion' => 'Manejo de enfermedades periodontales como gingivitis y periodontitis.',
                'Status' => '1'
            ],
            [
                'NombreTratamiento' => 'Selladores Dentales',
                'Descripcion' => 'Aplicación de resinas para prevenir caries en molares.',
                'Status' => '1'
            ],
            [
                'NombreTratamiento' => 'Reparación de Fracturas Dentales',
                'Descripcion' => 'Restauración de dientes fracturados por traumatismos.',
                'Status' => '1'
            ],
            [
                'NombreTratamiento' => 'Prótesis Parcial Removible',
                'Descripcion' => 'Reemplazo de dientes faltantes mediante prótesis removibles.',
                'Status' => '1'
            ]
        ];
        DB::table('tratamientos')->insert($tratamientos);
    }
}
