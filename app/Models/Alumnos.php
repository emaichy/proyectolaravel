<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumnos extends Model
{
    use HasFactory;
    protected $table = 'alumnos';
    protected $primaryKey = 'Matricula';
    protected $fillable = ['Nombre', 'ApePaterno', 'ApeMaterno', 'Foto_Alumno', 'Firma', 'FechaNac', 'Sexo', 'Direccion', 'NumeroExterior', 'NumeroInterior', 'CodigoPostal', 'Pais', 'TipoAlumno', 'Telefono', 'Curp', 'ID_Grupo', 'ID_Usuario', 'ID_Estado', 'ID_Municipio', 'Status'];
    public $timestamps = true;

    public function grupo()
    {
        return $this->belongsTo(Grupos::class, 'ID_Grupo');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'ID_Usuario');
    }

    public function estado()
    {
        return $this->belongsTo(Estados::class, 'ID_Estado');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipios::class, 'ID_Municipio');
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionPacientesAlumnos::class, 'ID_Alumno');
    }
}

