<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            Perfil de Usuario
        </h2>
    </x-slot>

    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
        @if (session('success'))
            <div class="mb-4 text-green-600 font-semibold">{{ session('success') }}</div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Foto actual -->
            @php
                $user = Auth::user();
                $photoUrl = $user->profile_photo
                    ? Storage::url($user->profile_photo)
                    : asset('img/user-placeholder.png');
            @endphp

            <div class="mb-4 text-center">
                <img id="preview-image"
                     src="{{ $photoUrl }}"
                     alt="Foto de perfil"
                     class="w-32 h-32 rounded-full object-cover mx-auto border-2 border-pink-400">
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
                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
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
                    {{ Auth::user()->departamento ?? 'Ventas' }}
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
        document.getElementById('profile_photo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>
