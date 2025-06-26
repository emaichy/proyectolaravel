<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentosPacientes extends Model
{
    use HasFactory;
    protected $table = 'documentos_pacientes';
    protected $primaryKey = 'ID_DocumentoPaciente';
    protected $fillable = ['RutaArchivo', 'Tipo', 'ID_Paciente', 'Status'];
    public $timestamps = true;
    public function paciente()
    {
        return $this->belongsTo(Pacientes::class, 'ID_Paciente');
    }
}
