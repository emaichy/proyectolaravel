<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Usuarios extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'ID_Usuario';
    protected $fillable = ['Correo', 'Contrasena', 'Rol', 'Status'];
    public $timestamps = true;
    public function administradores()
    {
        return $this->hasMany(Administrativos::class, 'ID_Usuario');
    }

    public function maestros()
    {
        return $this->hasMany(Maestros::class, 'ID_Usuario');
    }

    public function alumnos()
    {
        return $this->hasMany(Alumnos::class, 'ID_Usuario');
    }
}
