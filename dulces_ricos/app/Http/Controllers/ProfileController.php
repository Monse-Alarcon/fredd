<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

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
        $validationRules = [
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ];

        $request->validate($validationRules);

        $photoUpdated = false;
        $newPhotoPath = null;

        // Si hay nueva foto, procesarla (todos los roles pueden subir foto)
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');

            // Validar que el archivo sea válido
            if ($file->isValid()) {
                try {
                    // Generar nombre único para el archivo
                    $filename = time() . '_' . uniqid() . '_' . $user->id . '.' . $file->getClientOriginalExtension();

                    // Elimina la foto anterior (si existía) ANTES de guardar la nueva
                    if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                        Storage::disk('public')->delete($user->profile_photo);
                    }

                    // Guarda en disco "public" dentro de carpeta profile_pictures
                    $path = $file->storeAs('profile_pictures', $filename, 'public');

                    // Copiar también a `public/images/profile_pictures` para acceso directo
                    try {
                        $publicImagesDir = public_path('images/profile_pictures');
                        if (!File::exists($publicImagesDir)) {
                            File::makeDirectory($publicImagesDir, 0755, true);
                        }

                        $source = storage_path('app/public/' . $path);
                        $destination = $publicImagesDir . DIRECTORY_SEPARATOR . $filename;

                        if (File::exists($source)) {
                            File::copy($source, $destination);
                            Log::info('Copia pública de foto creada', [
                                'user_id' => $user->id,
                                'source' => $source,
                                'destination' => $destination,
                            ]);
                        } else {
                            Log::warning('No se encontró el archivo fuente para copiar a public/images', [
                                'user_id' => $user->id,
                                'expected_source' => $source,
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Error al copiar imagen a public/images', ['error' => $e->getMessage()]);
                    }

                    // Verificar que el archivo se guardó correctamente
                    if ($path && Storage::disk('public')->exists($path)) {
                        $newPhotoPath = $path;
                        $photoUpdated = true;
                        
                        Log::info('Foto de perfil procesada correctamente', [
                            'user_id' => $user->id,
                            'new_path' => $path,
                            'file_exists' => Storage::disk('public')->exists($path)
                        ]);
                    } else {
                        Log::error('Error: El archivo no se guardó correctamente', [
                            'path' => $path,
                            'exists' => $path ? Storage::disk('public')->exists($path) : false
                        ]);
                        return back()->withErrors(['profile_photo' => 'Error al guardar la imagen. Por favor, intenta de nuevo.']);
                    }
                } catch (\Exception $e) {
                    Log::error('Error al guardar foto de perfil', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    return back()->withErrors(['profile_photo' => 'Error al procesar la imagen: ' . $e->getMessage()]);
                }
            } else {
                return back()->withErrors(['profile_photo' => 'El archivo de imagen no es válido.']);
            }
        }

        // Actualizar nombre
        $user->name = $request->name;
        
        // Actualizar foto de perfil si se subió una nueva
        if ($photoUpdated && $newPhotoPath) {
            // Guardar la ruta en la base de datos
            $user->profile_photo = $newPhotoPath;
            Log::info('Guardando foto en BD', [
                'user_id' => $user->id,
                'path' => $newPhotoPath
            ]);
        }
        
        // Actualizar contraseña si se ingresó
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        // Guardar todos los cambios
        $saved = $user->save();
        
        if (!$saved) {
            Log::error('Error al guardar usuario en BD', ['user_id' => $user->id]);
            return back()->withErrors(['error' => 'Error al guardar los cambios.']);
        }
        
        // Recargar el usuario desde la base de datos para obtener los datos más recientes
        $user = $user->fresh();
        
        // Actualizar el usuario en la sesión para que se refleje inmediatamente
        Auth::login($user);
        
        // Verificar que la foto se guardó correctamente
        if ($photoUpdated && $newPhotoPath) {
            // Verificar que el archivo existe físicamente
            $fileExists = Storage::disk('public')->exists($newPhotoPath);
            
            // Verificar que se guardó en la base de datos
            $dbHasPhoto = !empty($user->profile_photo) && $user->profile_photo === $newPhotoPath;
            
            Log::info('Verificación después de guardar', [
                'user_id' => $user->id,
                'profile_photo_in_db' => $user->profile_photo,
                'expected_path' => $newPhotoPath,
                'file_exists' => $fileExists,
                'db_has_photo' => $dbHasPhoto,
                'photo_url' => $user->profile_photo_url
            ]);
            
            if (!$dbHasPhoto) {
                Log::error('Error: La foto no se guardó en la BD', [
                    'expected' => $newPhotoPath,
                    'actual' => $user->profile_photo
                ]);
                return back()->withErrors(['profile_photo' => 'Error al guardar la imagen en la base de datos.']);
            }
            
            if (!$fileExists) {
                Log::error('Error: El archivo no existe físicamente', [
                    'path' => $newPhotoPath
                ]);
                return back()->withErrors(['profile_photo' => 'Error: El archivo no se guardó correctamente.']);
            }
        }

        return redirect()->route('profile.edit')
                    ->with('success', 'Perfil actualizado correctamente.')
                    ->with('photo_updated', $photoUpdated);
    }
}
