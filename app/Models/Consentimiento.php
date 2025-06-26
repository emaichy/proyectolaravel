<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consentimiento extends Model
{
    use HasFactory;

    protected $table = 'consentimientos';

   protected $fillable = [
    'ID_Alumno', 
    'ID_Paciente', 
    'ID_Expediente', 
    'ID_Grupo', 
    'ID_Semestre',
    'fecha', 
    'declaracion',
    'descripcion_tratamiento', 
    'alumno_tratante', 
    'docentes',
    'firma_paciente', 
    'nombre_paciente',
    'firma_alumno', 
    'nombre_alumno',
    'firma_docentes',
     'nombre_docentes',
    'firma_testigo', 
    'nombre_testigo',
    'pdf_document',
];


    // Relaciones
    public function alumno()
    {
        return $this->belongsTo(Alumnos::class, 'ID_Alumno', 'Matricula');
    }

    public function paciente()
    {
        return $this->belongsTo(Pacientes::class, 'ID_Paciente', 'ID_Paciente');
    }

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'ID_Expediente', 'ID_Expediente');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupos::class, 'ID_Grupo', 'ID_Grupo');
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'ID_Semestre', 'ID_Semestre');
    }
}
