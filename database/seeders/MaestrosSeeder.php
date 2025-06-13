<?php

namespace Database\Seeders;

use App\Models\Maestros;
use App\Models\Usuarios;
use App\Models\Municipios;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class MaestrosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('es_ES');

        for ($i = 0; $i < 10; $i++) {
            $idEstado = rand(1, 32);
            $municipios = Municipios::where('ID_Estado', $idEstado)->pluck('ID_Estado')->toArray();
            if (empty($municipios)) {
                continue;
            }

            $usuario = Usuarios::create([
                'Correo' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'),
                'Rol' => 'Maestro',
            ]);

            Maestros::create([
                'Nombre' => $faker->firstName,
                'ApePaterno' => $faker->lastName,
                'ApeMaestro' => $faker->lastName,
                'Especialidad' => $faker->randomElement([
                    'Cirujano Dentista',
                    'Cirujano Dentista Infantil',
                    'Cirujano Dentista Maxilofacial',
                    'Ortodoncia y Cirugía Maxilofacial',
                    'Periodoncia y Cirugía Oral',
                ]),
                'Firma' => null,
                'FechaNac' => $faker->date('Y-m-d', '1970-01-01'),
                'Sexo' => $faker->randomElement(['Masculino', 'Femenino']),
                'Direccion' => $faker->streetAddress,
                'NumeroExterior' => $faker->buildingNumber,
                'NumeroInterior' => $faker->optional()->buildingNumber,
                'CodigoPostal' => $faker->postcode,
                'Pais' => 'México',
                'Telefono' => $faker->phoneNumber,
                'CedulaProfesional' => $faker->numerify('##########'),
                'ID_Estado' => $idEstado,
                'ID_Municipio' => $faker->randomElement($municipios),
                'ID_Usuario' => $usuario->ID_Usuario,
            ]);
        }
    }
}
