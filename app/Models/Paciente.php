<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'fecha_nacimiento',
        'telefono',
        'email',
        'direccion',
        'observaciones'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date'
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function getEdadAttribute()
    {
        return $this->fecha_nacimiento->age;
    }
}