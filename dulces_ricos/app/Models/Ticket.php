<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'prioridad',
        'estado',
        'usuario_id',
        'auxiliar_id',
        'departamento_id',
        'fecha_asignacion',
        'fecha_finalizacion',
    ];

    protected $casts = [
        'fecha_asignacion' => 'datetime',
        'fecha_finalizacion' => 'datetime',
    ];

    // Relación con el usuario que creó el ticket
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación con el auxiliar asignado
    public function auxiliar()
    {
        return $this->belongsTo(User::class, 'auxiliar_id');
    }

    // Relación con departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
}
