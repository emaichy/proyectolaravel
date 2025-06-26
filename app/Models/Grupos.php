<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupos extends Model
{
    use HasFactory;
    protected $table = 'grupos';
    protected $primaryKey = 'ID_Grupo';
    protected $fillable = ['NombreGrupo', 'ID_Semestre', 'Status'];
    public $timestamps = true;

    public function maestros()
    {
        return $this->belongsToMany(Maestros::class, 'grupo_maestros', 'ID_Grupo', 'ID_Maestro')
            ->withPivot('ID_Asignacion', 'Status')
            ->withTimestamps()
            ->orderBy('Nombre', 'asc');
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'ID_Semestre');
    }

    public function alumnos()
    {
        return $this->hasMany(Alumnos::class, 'ID_Grupo', 'ID_Grupo')
            ->orderBy('Nombre', 'asc');
    }
}
