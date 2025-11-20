<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Editar Departamento') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="mb-4">
                <a href="{{ route('admin.departamentos.index') }}" class="text-pink-600 hover:underline">← Volver a departamentos</a>
            </div>

            <form action="{{ route('admin.departamentos.update', $departamento->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $departamento->nombre) }}" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea name="descripcion" rows="3"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">{{ old('descripcion', $departamento->descripcion) }}</textarea>
                    @error('descripcion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4 justify-end">
                    <a href="{{ route('admin.departamentos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded-lg">
                        Actualizar Departamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

