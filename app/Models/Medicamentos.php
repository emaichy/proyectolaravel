<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicamentos extends Model
{
    use HasFactory;
    protected $table = 'medicamentos';
    protected $primaryKey = 'ID_Medicamento';
    protected $fillable = ['NombreMedicamento', 'Descripcion', 'ViaAdministracion', 'Status'];
    public $timestamps = true;
}
