<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $administradores = [
            [
                'Correo' => 'jared.go@gmail.com',
                'password' => Hash::make('aleort09'),
                'Rol' => 'Administrativo',
            ]
        ];
        DB::table('usuarios')->insert($administradores);
    }
}
