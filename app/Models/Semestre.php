<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Semestre extends Model
{
    use HasFactory;
    protected $table = 'semestres';
    protected $primaryKey = 'ID_Semestre';
    protected $fillable = ['Semestre', 'Status'];
    public $timestamps = true;
    public function grupos()
    {
        return $this->hasMany(Grupos::class, 'ID_Semestre');
    }
}

