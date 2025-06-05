<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupos extends Model
{
    use HasFactory;
    protected $table = 'grupos';
    protected $primaryKey = 'ID_Grupo';
    protected $fillable = ['NombreGrupo', 'ID_Maestro', 'ID_Semestre', 'Status'];
    public $timestamps = true;

    public function maestro()
    {
        return $this->belongsTo(Maestros::class, 'ID_Maestro');
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'ID_Semestre');
    }

    public function alumnos()
    {
        return $this->hasMany(Alumnos::class, 'ID_Grupo');
    }
}

