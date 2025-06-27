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
    protected $fillable = ['Correo', 'password', 'Rol', 'Status'];
    public $timestamps = true;
    public function administradores()
    {
        return $this->hasOne(Administrativos::class, 'ID_Usuario');
    }

    public function maestro()
    {
        return $this->hasOne(Maestros::class, 'ID_Usuario', 'ID_Usuario');
    }

    public function alumno()
    {
        return $this->hasOne(Alumnos::class, 'ID_Usuario', 'ID_Usuario');
    }
}
