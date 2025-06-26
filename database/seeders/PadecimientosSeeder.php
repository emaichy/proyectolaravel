<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PadecimientosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $padecimientos = [
            [
                'NombrePadecimiento' => 'Caries dental',
                'Descripcion' => 'Destrucción del tejido dental causada por bacterias.',
                'Status' => '1'
            ],
            [
                'NombrePadecimiento' => 'Gingivitis',
                'Descripcion' => 'Inflamación de las encías debido a placa bacteriana.',
                'Status' => '1'
            ],
            [
                'NombrePadecimiento' => 'Periodontitis',
                'Descripcion' => 'Enfermedad avanzada de las encías que afecta el hueso.',
                'Status' => '1'
            ],
            [
                'NombrePadecimiento' => 'Absceso dental',
                'Descripcion' => 'Acumulación de pus por infección bacteriana en el diente.',
                'Status' => '1'
            ],
            [
                'NombrePadecimiento' => 'Maloclusión dental',
                'Descripcion' => 'Alineación incorrecta de los dientes o mordida.',
                'Status' => '1'
            ],
            [
                'NombrePadecimiento' => 'Bruxismo',
                'Descripcion' => 'Rechinamiento o apretamiento involuntario de los dientes.',
                'Status' => '1'
            ],
            [
                'NombrePadecimiento' => 'Pulpitis',
                'Descripcion' => 'Inflamación de la pulpa dental, generalmente por caries.',
                'Status' => '1'
            ],
            [
                'NombrePadecimiento' => 'Quiste dental',
                'Descripcion' => 'Lesión benigna llena de líquido en la mandíbula o encía.',
                'Status' => '1'
            ],
            [
                'NombrePadecimiento' => 'Dientes impactados',
                'Descripcion' => 'Dientes que no erupcionan correctamente, como las muelas del juicio.',
                'Status' => '1'
            ],
            [
                'NombrePadecimiento' => 'Fractura dental',
                'Descripcion' => 'Ruptura o fisura en la estructura del diente.',
                'Status' => '1'
            ],
            [
                'NombrePadecimiento' => 'Estomatitis',
                'Descripcion' => 'Inflamación de la mucosa oral.',
                'Status' => '1'
            ],
            [
                'NombrePadecimiento' => 'Halitosis',
                'Descripcion' => 'Mal aliento persistente de origen bucal.',
                'Status' => '1'
            ],
            [
                'NombrePadecimiento' => 'Leucoplasia oral',
                'Descripcion' => 'Placa blanca en la mucosa bucal, potencialmente precancerosa.',
                'Status' => '1'
            ],
            [
                'NombrePadecimiento' => 'Hipoplasia del esmalte',
                'Descripcion' => 'Defecto en la formación del esmalte dental.',
                'Status' => '1'
            ],
            [
                'NombrePadecimiento' => 'Pericoronitis',
                'Descripcion' => 'Inflamación del tejido alrededor de un diente parcialmente erupcionado.',
                'Status' => '1'
            ]
        ];
        DB::table('padecimientos')->insert($padecimientos);
    }
}
