<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pacientes extends Model
{
    use HasFactory;
    protected $table = 'pacientes';
    protected $primaryKey = 'ID_Paciente';

    protected $fillable = [
        'Nombre',
        'ApePaterno',
        'ApeMaterno',
        'FechaNac',
        'Sexo',
        'Direccion',
        'NumeroExterior',
        'NumeroInterior',
        'CodigoPostal',
        'Pais',
        'TipoPaciente',
        'Foto_Paciente',
        'ID_Estado',
        'ID_Municipio',
        'Status'
    ];
    public $timestamps = true;

    public function expedientes()
    {
        return $this->hasMany(Expediente::class, 'ID_Paciente');
    }

    public function estado()
    {
        return $this->belongsTo(Estados::class, 'ID_Estado');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipios::class, 'ID_Municipio');
    }
}
