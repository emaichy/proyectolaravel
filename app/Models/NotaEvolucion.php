<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
    use App\Models\GrupoMaestro;
use App\Models\Maestros;

class NotaEvolucion extends Model
{
    use HasFactory;
    protected $table = 'notas_evolucion'; 
    protected $primaryKey = 'ID_Nota';    
    public $timestamps = true;
    protected $fillable = [
        'ID_Alumno',
        'ID_Paciente',
        'ID_Expediente',
        'ID_Semestre',
        'ID_Grupo',
        'fecha',
        'presion_arterial',
        'frecuencia_cardiaca',
        'frecuencia_respiratoria',
        'temperatura',
        'oximetria',
        'tratamiento_realizado',
        'descripcion_tratamiento',
        'firma_catedratico',
        'firma_alumno',
        'firma_paciente',
        'pdf_document',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumnos::class, 'ID_Alumno', 'Matricula');
    }

    public function paciente()
    {
        return $this->belongsTo(Pacientes::class, 'ID_Paciente', 'ID_Paciente');
    }

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'ID_Expediente', 'ID_Expediente');
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'ID_Semestre', 'ID_Semestre');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupos::class, 'ID_Grupo', 'ID_Grupo');

    }

public function grupoMaestro()
{
    return $this->hasOne(GrupoMaestro::class, 'ID_Grupo', 'ID_Grupo')
                ->where('Status', 1);
}

public function maestro()
{
    return $this->grupoMaestro ? $this->grupoMaestro->maestro : null;
}
}