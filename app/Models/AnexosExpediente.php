<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnexosExpediente extends Model
{
    use HasFactory;
    protected $table = 'anexos_expedientes';
    protected $primaryKey = 'ID_AnexoExpediente';
    protected $fillable = [
        'Nombre_Anexo',
        'Ruta_Anexo',
        'Tipo_Anexo',
        'ID_Expediente',
        'Status'
    ];
    public $timestamps = true;

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'ID_Expediente', 'ID_Expediente');
    }
}
