<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    use HasFactory;
    protected $table = 'expedientes';
    protected $primaryKey = 'ID_Expediente';
    protected $fillable = ['ID_Asignacion', 'TipoExpediente', 'Status'];
    public $timestamps = true;

    public function anexos()
    {
        return $this->hasMany(AnexosExpediente::class, 'ID_Expediente', 'ID_Expediente');
    }
    
    public function asignacion()
    {
        return $this->belongsTo(AsignacionPacientesAlumnos::class, 'ID_Asignacion', 'ID_Asignacion');
    }
}
