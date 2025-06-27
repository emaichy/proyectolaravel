<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionExpedienteAlumno extends Model
{
    use HasFactory;
    protected $table = 'asignacion_expediente_alumno';
    protected $primaryKey = 'ID_Asignacion';
    protected $fillable = ['ID_Alumno', 'ID_Expediente', 'Status'];
    public $timestamps = true;

    public function alumno()
    {
        return $this->belongsTo(Alumnos::class, 'ID_Alumno', 'ID_Alumno');
    }

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'ID_Expediente', 'ID_Expediente');
    }
}