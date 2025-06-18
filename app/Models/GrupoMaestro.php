<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoMaestro extends Model
{
    use HasFactory;
    protected $table = 'grupo_maestro';
    protected $primaryKey = 'ID_Asignacion';
    protected $fillable = ['ID_Grupo', 'ID_Maestro', 'Status'];
    public $timestamps = true;

    public function grupo()
    {
        return $this->belongsTo(Grupos::class, 'ID_Grupo');
    }

    public function maestro()
    {
        return $this->belongsTo(Maestros::class, 'ID_Maestro');
    }
}
