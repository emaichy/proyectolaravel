<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionPacientesAlumnos extends Model
{
    use HasFactory;
    protected $table = 'asignacionpacientesalumnos';
    protected $primaryKey = 'ID_Asignacion';
    protected $fillable = ['ID_Alumno', 'ID_Paciente', 'Status'];
    public $timestamps = true;
    
    public function alumno()
    {
        return $this->belongsTo(Alumnos::class, 'ID_Alumno');
    }

    public function paciente()
    {
        return $this->belongsTo(Pacientes::class, 'ID_Paciente');
    }
}

