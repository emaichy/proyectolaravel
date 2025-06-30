<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;
    protected $table = 'citas';
    protected $primaryKey = 'ID_Cita';
    protected $fillable = [
        'ID_Paciente',
        'ID_Alumno',
        'Fecha',
        'Hora',
        'Estado'
    ];

    public function paciente()
    {
        return $this->belongsTo(Pacientes::class, 'ID_Paciente', 'ID_Paciente');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumnos::class, 'ID_Alumno', 'Matricula');
    }
}