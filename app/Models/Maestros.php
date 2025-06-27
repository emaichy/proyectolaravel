<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Maestros extends Model
{
    use HasFactory;
    protected $table = 'maestros';
    protected $primaryKey = 'ID_Maestro';
    protected $fillable = ['Nombre', 'ApePaterno', 'ApeMaestro', 'Especialidad', 'Foto_Maestro', 'Firma', 'FechaNac', 'Sexo', 'Direccion', 'NumeroExterior', 'NumeroInterior', 'CodigoPostal', 'Pais', 'Telefono', 'CedulaProfesional', 'ID_Estado', 'ID_Municipio', 'ID_Usuario', 'Status'];
    public $timestamps = true;

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

    public function grupos()
    {
        return $this->belongsToMany(Grupos::class, 'grupo_maestros', 'ID_Maestro', 'ID_Grupo')
            ->withPivot('ID_Asignacion', 'Status')
            ->withTimestamps();
    }

    public function alumnos()
    {
        return $this->hasManyThrough(
            Alumnos::class,
            Grupos::class,
            'ID_Maestro',
            'ID_Grupo',
            'ID_Maestro',
            'ID_Grupo'
        );
    }
}
