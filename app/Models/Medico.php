<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'especialidad',
        'telefono',
        'email',
        'horarios',
        'consultorio',
        'activo'
    ];

    protected $casts = [
        'horarios' => 'array',
        'activo' => 'boolean'
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }
}