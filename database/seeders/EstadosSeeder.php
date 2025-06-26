<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $estados = [
        ['NombreEstado' => 'Aguascalientes', 'Status' => 1],
        ['NombreEstado' => 'Baja California', 'Status' => 1],
        ['NombreEstado' => 'Baja California Sur', 'Status' => 1],
        ['NombreEstado' => 'Campeche', 'Status' => 1],
        ['NombreEstado' => 'Chiapas', 'Status' => 1],
        ['NombreEstado' => 'Chihuahua', 'Status' => 1],
        ['NombreEstado' => 'Coahuila de Zaragoza', 'Status' => 1],
        ['NombreEstado' => 'Colima', 'Status' => 1],
        ['NombreEstado' => 'Ciudad de México', 'Status' => 1],
        ['NombreEstado' => 'Durango', 'Status' => 1],
        ['NombreEstado' => 'Guanajuato', 'Status' => 1],
        ['NombreEstado' => 'Guerrero', 'Status' => 1],
        ['NombreEstado' => 'Hidalgo', 'Status' => 1],
        ['NombreEstado' => 'Jalisco', 'Status' => 1],
        ['NombreEstado' => 'México', 'Status' => 1],
        ['NombreEstado' => 'Michoacán de Ocampo', 'Status' => 1],
        ['NombreEstado' => 'Morelos', 'Status' => 1],
        ['NombreEstado' => 'Nayarit', 'Status' => 1],
        ['NombreEstado' => 'Nuevo León', 'Status' => 1],
        ['NombreEstado' => 'Oaxaca', 'Status' => 1],
        ['NombreEstado' => 'Puebla', 'Status' => 1],
        ['NombreEstado' => 'Querétaro', 'Status' => 1],
        ['NombreEstado' => 'Quintana Roo', 'Status' => 1],
        ['NombreEstado' => 'San Luis Potosí', 'Status' => 1],
        ['NombreEstado' => 'Sinaloa', 'Status' => 1],
        ['NombreEstado' => 'Sonora', 'Status' => 1],
        ['NombreEstado' => 'Tabasco', 'Status' => 1],
        ['NombreEstado' => 'Tamaulipas', 'Status' => 1],
        ['NombreEstado' => 'Tlaxcala', 'Status' => 1],
        ['NombreEstado' => 'Veracruz', 'Status' => 1],
        ['NombreEstado' => 'Yucatán', 'Status' => 1],
        ['NombreEstado' => 'Zacatecas', 'Status' => 1],
    ];
    DB::table('estados')->insert($estados);
}
}
