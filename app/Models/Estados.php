<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estados extends Model
{
    use HasFactory;
    protected $table = 'estados';
    protected $primaryKey = 'ID_Estado';
    protected $fillable = ['NombreEstado', 'Status'];
    public $timestamps = true;

    public function municipios()
    {
        return $this->hasMany(Municipios::class, 'ID_Estado');
    }
    public function pacientes()
    {
        return $this->hasMany(Pacientes::class, 'ID_Estado');
    }
    public function alumnos()
    {
        return $this->hasMany(Alumnos::class, 'ID_Estado');
    }
    public function maestros()
    {
        return $this->hasMany(Maestros::class, 'ID_Estado');
    }
}
