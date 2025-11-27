<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            Perfil de Usuario
        </h2>
    </x-slot>

    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
        @if (session('success'))
            <div class="mb-4 text-green-600 font-semibold">{{ session('success') }}</div>
            @if(session('photo_updated'))
                <script>
                    // Si se actualizó la foto, recargar la página después de un breve delay
                    setTimeout(function() {
                        // Limpiar caché y recargar con timestamp único
                        var url = window.location.href.split('?')[0];
                        window.location.href = url + '?t=' + new Date().getTime() + '&nocache=' + Math.random();
                    }, 1000);
                </script>
            @endif
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Foto actual -->
            @php
                // Obtener el usuario autenticado y recargar desde BD
                $user = Auth::user();
                $user->refresh();

                $defaultPhoto = asset('images/default-profile.png');

                // Preferir copia pública en public/images/profile_pictures si existe
                $photoPath = $user->profile_photo ?? null;
                $publicFilename = $photoPath ? basename($photoPath) : null;
                $publicFilePath = $publicFilename ? public_path('images/profile_pictures/' . $publicFilename) : null;

                if ($publicFilePath && file_exists($publicFilePath)) {
                    // Hay copia pública, usarla (con cache-busting)
                    $photoUrlWithCache = asset('images/profile_pictures/' . $publicFilename) . '?t=' . ($user->updated_at ? $user->updated_at->timestamp : time());
                } else {
                    // No hay copia pública: usar el accessor que devuelve url de storage o default
                    $hasPhoto = !empty($user->profile_photo) && trim($user->profile_photo) !== '';
                    if ($hasPhoto) {
                        $photoUrl = $user->profile_photo_url;
                        // Si la URL contiene 'default-profile', no hay foto real
                        if (strpos($photoUrl, 'default-profile.png') !== false) {
                            $photoUrlWithCache = $defaultPhoto . '?t=' . time();
                        } else {
                            // Hay foto personalizada, agregar timestamp para evitar caché
                            $separator = strpos($photoUrl, '?') !== false ? '&' : '?';
                            $photoUrlWithCache = $photoUrl . $separator . 't=' . time() . '&nocache=' . uniqid();
                        }
                    } else {
                        // No hay foto, usar imagen por defecto
                        $photoUrlWithCache = $defaultPhoto . '?t=' . time();
                    }
                }
            @endphp

            <div class="mb-4 text-center">
                <img id="preview-image"
                     src="{{ $photoUrlWithCache }}"
                     alt="Foto de perfil"
                     class="w-32 h-32 rounded-full object-cover mx-auto border-2 border-pink-400"
                     style="max-width: 128px; max-height: 128px; background-color: #f3f4f6; min-width: 128px; min-height: 128px;"
                     onerror="console.error('Error al cargar imagen:', this.src); var defaultImg = '{{ $defaultPhoto }}?t=' + new Date().getTime(); if (!this.src.includes('default-profile')) { this.src = defaultImg; }">
            </div>

            <!-- Subir nueva imagen -->
            <div class="mb-4">
                <label for="profile_photo" class="block text-sm font-medium text-gray-700">Cambiar foto</label>
                <input type="file" name="profile_photo" id="profile_photo"
                       accept="image/*"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @error('profile_photo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nombre -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!--  Rol del usuario -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Rol en el sistema</label>
                <input type="text"
                       value="{{ ucfirst(Auth::user()->role ?? 'Empleado') }}"
                       readonly
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-700 font-semibold">
            </div>

           <!-- Departamento (solo visual) -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Departamento</label>
                <p class="mt-1 p-2 bg-gray-100 rounded-md text-gray-800">
                    {{ $user->departamento ? $user->departamento->nombre : 'Sin asignar' }}
                </p>
            </div>



            <!-- Contraseña -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Nueva contraseña (opcional)</label>
                <input type="password" name="password" id="password"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <input type="password" name="password_confirmation"
                       placeholder="Confirmar contraseña"
                       class="mt-2 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <!-- Botón guardar -->
            <button type="submit"
                    class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded w-full">
                Guardar cambios
            </button>
        </form>

        <!--  Botón de cerrar sesión -->
        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit"
                    class="bg-gray-200 hover:bg-red-500 hover:text-white text-gray-700 font-semibold py-2 px-4 rounded w-full transition">
                 Cerrar sesión
            </button>
        </form>
    </div>

    {{-- Script para vista previa instantánea --}}
    <script>
        const profilePhotoInput = document.getElementById('profile_photo');
        if (profilePhotoInput) {
            profilePhotoInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('preview-image').src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
</x-app-layout>
