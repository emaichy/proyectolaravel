<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use App\Models\Alumnos;
use App\Models\Usuarios;
use App\Models\Estados;
use App\Models\Municipios;

class AlumnosSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('es_MX');
        $totalGrupos = 11;
        $matriculaBase = 100000;

        for ($grupo = 1; $grupo <= $totalGrupos; $grupo++) {
            for ($i = 1; $i <= 20; $i++) {
                $idEstado = rand(1, 32);
                $municipios = Municipios::where('ID_Estado', $idEstado)->pluck('ID_Estado')->toArray();
                if (empty($municipios)) {
                    continue;
                }
                $usuario = Usuarios::create([
                    'Correo' => $faker->unique()->safeEmail,
                    'password' => Hash::make('password123')
                ]);
                $prefix = $faker->randomElement(['722', '729']);
                $suffix = '';
                do {
                    $suffix = strval(mt_rand(1000000, 9999999)); // evita secuencias simples y asegura longitud
                } while (preg_match('/(012|123|234|345|456|567|678|789|890|000|111|222|333|444|555|666|777|888|999)/', $suffix));
                $telefono = $prefix . $suffix;
                Alumnos::create([
                    'Matricula' => $matriculaBase++,
                    'Nombre' => $faker->firstName,
                    'ApePaterno' => $faker->lastName,
                    'ApeMaterno' => $faker->lastName,
                    'Firma' => NULL,
                    'FechaNac' => $faker->date('Y-m-d', '1970-01-01'),
                    'Sexo' => $faker->randomElement(['Masculino', 'Femenino']),
                    'Direccion' => $faker->streetAddress,
                    'NumeroExterior' => $faker->buildingNumber,
                    'NumeroInterior' => $faker->buildingNumber,
                    'CodigoPostal' => $faker->postcode(5),
                    'Pais' => 'México',
                    'TipoAlumno' => $faker->randomElement(['Regular', 'Irregular']),
                    'Telefono' => $telefono,
                    'Curp' => strtoupper($faker->bothify('????######??????##')),
                    'ID_Grupo' => $grupo,
                    'ID_Usuario' => $usuario->ID_Usuario,
                    'ID_Estado' => $idEstado,
                    'ID_Municipio' => $faker->randomElement($municipios),
                ]);
            }
        }
    }
}
