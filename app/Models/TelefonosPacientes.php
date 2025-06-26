<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelefonosPacientes extends Model
{
    use HasFactory;
    protected $table = 'telefonos_pacientes';
    protected $primaryKey = 'ID_TelefonoPaciente';
    protected $fillable = ['Telefono', 'Tipo', 'ID_Paciente', 'Status'];
    public $timestamps = true;
    
    public function paciente()
    {
        return $this->belongsTo(Pacientes::class, 'ID_Paciente');
    }
}
