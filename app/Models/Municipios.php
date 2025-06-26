<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipios extends Model
{
    use HasFactory;
    protected $table = 'municipios';
    protected $primaryKey = 'ID_Municipio';
    protected $fillable = ['NombreMunicipio', 'ID_Estado', 'Status'];
    public $timestamps = true;

    public function estado()
    {
        return $this->belongsTo(Estados::class, 'ID_Estado');
    }
    
    public function pacientes()
    {
        return $this->hasMany(Pacientes::class, 'ID_Municipio');
    }
    
    public function alumnos()
    {
        return $this->hasMany(Alumnos::class, 'ID_Municipio');
    }
    
    public function maestros()
    {
        return $this->hasMany(Maestros::class, 'ID_Municipio');
    }

}
