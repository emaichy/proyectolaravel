<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pacientes;
use App\Models\Municipios; // Asegúrate de usar el modelo correcto
use Faker\Factory as Faker;

class PacientesSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_MX');
        for ($i = 0; $i < 10; $i++) {
            $estadoId = rand(1, 32);
            $municipios = Municipios::where('ID_Estado', $estadoId)->pluck('ID_Municipio')->toArray();
            if (empty($municipios)) {
                continue;
            }
            $municipioId = $faker->randomElement($municipios);
            $nombreCalle = 'Calle ' . $faker->lastName . ' ' . $faker->randomElement([
                'Zapata',
                'Juárez',
                'Hidalgo',
                'Morelos',
                'Reforma',
                'Guerrero',
                'Madero',
                'Allende',
                'Matamoros',
                'Independencia',
                'García',
                'Obregón',
                'Galeana',
                'Victoria',
                'Bravo'
            ]);
            $numeroExterior = $faker->numberBetween(1, 200);
            $numeroInterior = $faker->numberBetween(1, 20);
            $codigoPostal = $faker->numberBetween(10000, 99999);
            $fechaNacimiento = $faker->dateTimeBetween('1930-01-01', '2020-12-31')->format('Y-m-d');
            $anioNacimiento = (int)substr($fechaNacimiento, 0, 4);
            $tipoPaciente = ($anioNacimiento > 2010) ? 'Pediátrico' : 'Adulto';

            Pacientes::create([
                'Nombre'         => $faker->firstName,
                'ApePaterno'     => $faker->lastName,
                'ApeMaterno'     => $faker->lastName,
                'FechaNac'       => $fechaNacimiento,
                'Sexo'           => $faker->randomElement(['Masculino', 'Femenino']),
                'Direccion'      => $nombreCalle,
                'NumeroExterior' => $numeroExterior,
                'NumeroInterior' => $numeroInterior,
                'CodigoPostal'   => $codigoPostal,
                'Pais'           => 'México',
                'TipoPaciente'   => $tipoPaciente,
                'Foto_Paciente'  => null,
                'ID_Estado'      => $estadoId,
                'ID_Municipio'   => $municipioId,
            ]);
        }
    }
}
