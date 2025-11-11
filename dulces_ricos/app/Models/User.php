<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo',
        'role',
        'departamento', // agregado
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
];


    /**
     *  Devuelve la URL pública de la foto de perfil
     * o una imagen por defecto si no hay ninguna guardada.
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo && Storage::exists('public/profile_pictures/' . $this->profile_photo)) {
            return asset('storage/profile_pictures/' . $this->profile_photo);
        }

        // Imagen por defecto si el usuario no tiene foto
        return asset('images/default-profile.png');
    }
}
