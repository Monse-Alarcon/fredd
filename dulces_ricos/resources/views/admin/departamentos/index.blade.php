<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Gestión de Departamentos') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('admin.departamentos.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded shadow">
                + Crear Departamento
            </a>
            <a href="{{ route('dashboard') }}" class="ml-2 text-pink-600 hover:underline">← Volver al dashboard</a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-4 bg-pink-100 border-b">
                <h3 class="text-lg font-semibold text-pink-700">Lista de Departamentos</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-pink-100 text-pink-700">
                        <tr>
                            <th class="py-3 px-4 border-b">Nombre</th>
                            <th class="py-3 px-4 border-b">Descripción</th>
                            <th class="py-3 px-4 border-b">Usuarios</th>
                            <th class="py-3 px-4 border-b text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departamentos as $departamento)
                            <tr class="hover:bg-pink-50">
                                <td class="py-2 px-4 border-b font-medium">{{ $departamento->nombre }}</td>
                                <td class="py-2 px-4 border-b">{{ $departamento->descripcion ?? 'Sin descripción' }}</td>
                                <td class="py-2 px-4 border-b">{{ $departamento->usuarios_count }}</td>
                                <td class="py-2 px-4 border-b text-center">
                                    <div class="flex gap-2 justify-center">
                                        <a href="{{ route('admin.departamentos.edit', $departamento->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded shadow text-sm">
                                            Editar
                                        </a>
                                        <form action="{{ route('admin.departamentos.destroy', $departamento->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este departamento?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow text-sm">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-500">
                                    No hay departamentos registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

