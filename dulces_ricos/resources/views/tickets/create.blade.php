<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
             Crear Ticket
        </h2>
    </x-slot>

    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('tickets.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Título</label>
                <input type="text" name="titulo" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Descripción</label>
                <textarea name="descripcion" rows="4" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400" required></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Prioridad</label>
                <select name="prioridad" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400" required>
                    <option value="baja">Baja</option>
                    <option value="media" selected>Media</option>
                    <option value="alta">Alta</option>
                </select>
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('tickets.index') }}" class="text-pink-600 font-semibold hover:underline"> Volver</a>
                <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 px-6 rounded shadow">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

