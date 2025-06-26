<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinicas extends Model
{
    use HasFactory;
    protected $table = 'clinicas';
    protected $primaryKey = 'ID_Clinica';
    protected $fillable = ['NombreClinica', 'Ubicacion', 'Status'];
    public $timestamps = true;
}
