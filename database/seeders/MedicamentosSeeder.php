<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicamentos = [
            [
                'NombreMedicamento' => 'Lidocaína',
                'Descripcion' => 'Anestésico local utilizado para procedimientos dentales.',
                'ViaAdministracion' => 'Infiltración',
                'Status' => '1'
            ],
            [
                'NombreMedicamento' => 'Amoxicilina',
                'Descripcion' => 'Antibiótico de amplio espectro para infecciones orales.',
                'ViaAdministracion' => 'Oral',
                'Status' => '1'
            ],
            [
                'NombreMedicamento' => 'Ibuprofeno',
                'Descripcion' => 'Antiinflamatorio no esteroideo para el manejo del dolor.',
                'ViaAdministracion' => 'Oral',
                'Status' => '1'
            ],
            [
                'NombreMedicamento' => 'Paracetamol',
                'Descripcion' => 'Analgésico y antipirético para el control del dolor leve a moderado.',
                'ViaAdministracion' => 'Oral',
                'Status' => '1'
            ],
            [
                'NombreMedicamento' => 'Metronidazol',
                'Descripcion' => 'Antibiótico para infecciones bacterianas anaerobias en la cavidad oral.',
                'ViaAdministracion' => 'Oral',
                'Status' => '1'
            ],
            [
                'NombreMedicamento' => 'Clindamicina',
                'Descripcion' => 'Antibiótico utilizado en infecciones bacterianas graves.',
                'ViaAdministracion' => 'Oral',
                'Status' => '1'
            ],
            [
                'NombreMedicamento' => 'Diclofenaco',
                'Descripcion' => 'Antiinflamatorio no esteroideo para el alivio del dolor y la inflamación.',
                'ViaAdministracion' => 'Oral',
                'Status' => '1'
            ],
            [
                'NombreMedicamento' => 'Ketorolaco',
                'Descripcion' => 'Analgésico potente para el manejo del dolor agudo.',
                'ViaAdministracion' => 'Oral',
                'Status' => '1'
            ],
            [
                'NombreMedicamento' => 'Naproxeno',
                'Descripcion' => 'Antiinflamatorio no esteroideo para el tratamiento del dolor y la inflamación.',
                'ViaAdministracion' => 'Oral',
                'Status' => '1'
            ],
            [
                'NombreMedicamento' => 'Dexametasona',
                'Descripcion' => 'Corticosteroide para reducir la inflamación y reacciones alérgicas.',
                'ViaAdministracion' => 'Oral',
                'Status' => '1'
            ],
            [
                'NombreMedicamento' => 'Clorhexidina',
                'Descripcion' => 'Antiséptico bucal para la desinfección de la cavidad oral.',
                'ViaAdministracion' => 'Tópica',
                'Status' => '1'
            ],
            [
                'NombreMedicamento' => 'Azitromicina',
                'Descripcion' => 'Antibiótico de amplio espectro para infecciones bacterianas.',
                'ViaAdministracion' => 'Oral',
                'Status' => '1'
            ],
            [
                'NombreMedicamento' => 'Miconazol',
                'Descripcion' => 'Antifúngico utilizado para tratar infecciones por hongos en la boca.',
                'ViaAdministracion' => 'Tópica',
                'Status' => '1'
            ],
            [
                'NombreMedicamento' => 'Benzocaína',
                'Descripcion' => 'Anestésico local para aliviar el dolor en la mucosa oral.',
                'ViaAdministracion' => 'Tópica',
                'Status' => '1'
            ],
            [
                'NombreMedicamento' => 'Prednisona',
                'Descripcion' => 'Corticosteroide para el tratamiento de inflamaciones severas.',
                'ViaAdministracion' => 'Oral',
                'Status' => '1'
            ]
        ];

        DB::table('medicamentos')->insert($medicamentos);
    }
}
