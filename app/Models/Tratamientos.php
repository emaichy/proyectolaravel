<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tratamientos extends Model
{
    use HasFactory;
    protected $table = 'tratamientos';
    protected $primaryKey = 'ID_Tratamiento';
    protected $fillable = ['NombreTratamiento', 'Descripcion', 'Status'];
    public $timestamps = true;
}
