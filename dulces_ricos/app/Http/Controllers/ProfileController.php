<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Mostrar formulario de perfil
    public function edit()
    {
        return view('profile');
    }

    // Actualizar perfil
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validar campos
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Actualizar nombre
        $user->name = $request->name;

        // Actualizar contraseña si se ingresó
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Si hay nueva foto, la guardamos
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');

            // Guarda en disco "public" dentro de carpeta profile_pictures
            $path = $file->store('profile_pictures', 'public');

            // Elimina la foto anterior (si existía)
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Guarda la ruta relativa (profile_pictures/archivo.jpg)
            $user->profile_photo = $path;
        }

        // Guarda todos los cambios
        $user->save();

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
