<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'identidad',
        'nombre',
        'apellido',
        'cargo',
        'salario',
        'codigo_empleado',
        'fecha_ingreso',
    ];
}