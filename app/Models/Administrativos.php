<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrativos extends Model
{
    use HasFactory;
    protected $table = 'administrativos';
    protected $primaryKey = 'ID_Admin';
    protected $fillable = ['Nombre', 'ApePaterno', 'ApeMaterno', 'Firma', 'FechaNac', 'Sexo', 'Direccion', 'NumeroExterior', 'NumeroInterior', 'CodigoPostal', 'Pais', 'Telefono', 'ID_Estado', 'ID_Municipio', 'ID_Usuario', 'Status'];
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
}

