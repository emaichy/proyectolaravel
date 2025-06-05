<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Padecimientos extends Model
{
    use HasFactory;
    protected $table = 'padecimientos';
    protected $primaryKey = 'ID_Padecimiento';
    protected $fillable = ['NombrePadecimiento', 'Descripcion', 'Status'];
    public $timestamps = true;
}
