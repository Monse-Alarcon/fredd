<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo',
        'role',
        'departamento_id',
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
        // Si no hay foto guardada en la base de datos, devolver la imagen por defecto
        if (empty($this->profile_photo) || trim($this->profile_photo) === '') {
            return asset('images/default-profile.png');
        }
        
        // Si hay una ruta guardada, generar la URL directamente
        try {
            // Verificar que el archivo existe
            $fileExists = Storage::disk('public')->exists($this->profile_photo);
            
            if (!$fileExists) {
                Log::warning('Archivo de foto no existe físicamente', [
                    'user_id' => $this->id,
                    'path' => $this->profile_photo
                ]);
                return asset('images/default-profile.png');
            }
            
            $url = Storage::disk('public')->url($this->profile_photo);
            
            // Agregar timestamp para evitar caché del navegador
            $timestamp = $this->updated_at ? $this->updated_at->timestamp : time();
            
            // Si la URL ya tiene parámetros, agregar con &, si no con ?
            $separator = strpos($url, '?') !== false ? '&' : '?';
            
            $finalUrl = $url . $separator . 'v=' . $timestamp;
            
            return $finalUrl;
        } catch (\Exception $e) {
            // Si hay un error al generar la URL, loguear y devolver la imagen por defecto
            Log::error('Error al generar URL de foto de perfil', [
                'user_id' => $this->id,
                'path' => $this->profile_photo,
                'error' => $e->getMessage()
            ]);
            
            return asset('images/default-profile.png');
        }
    }

    // Relación con departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
}
