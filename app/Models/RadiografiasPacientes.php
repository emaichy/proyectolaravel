<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadiografiasPacientes extends Model
{
    use HasFactory;
    protected $table = 'radiografias_pacientes';
    protected $primaryKey = 'ID_Radiografia';
    protected $fillable = ['RutaArchivo', 'Tipo', 'ID_Paciente', 'Status'];
    public $timestamps = true;
    public function paciente()
    {
        return $this->belongsTo(Pacientes::class, 'ID_Paciente');
    }
}
