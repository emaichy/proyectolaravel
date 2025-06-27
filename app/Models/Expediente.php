<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    use HasFactory;
    protected $table = 'expedientes';
    protected $primaryKey = 'ID_Expediente';
    protected $fillable = ['ID_Paciente', 'TipoExpediente', 'Status'];
    public $timestamps = true;

    public function anexos()
    {
        return $this->hasMany(AnexosExpediente::class, 'ID_Expediente', 'ID_Expediente');
    }

    public function paciente()
    {
        return $this->belongsTo(Pacientes::class, 'ID_Paciente', 'ID_Paciente');
    }

    public function alumnos()
    {
        return $this->belongsToMany(Alumnos::class, 'asignacion_expediente_alumno', 'ID_Expediente', 'ID_Alumno')
            ->withTimestamps();
    }
}