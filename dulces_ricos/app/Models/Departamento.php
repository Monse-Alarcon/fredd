<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Relación con usuarios
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Relación con tickets
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
