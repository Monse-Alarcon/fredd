<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Mi Dashboard - Usuario') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto">
        <!-- Mensaje de éxito -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Estadísticas del usuario -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white shadow-md rounded-lg p-4 border-l-4 border-blue-500">
                <div class="text-sm text-gray-600">Mis Tickets</div>
                <div class="text-2xl font-bold text-gray-800">{{ $estadisticas['total'] }}</div>
            </div>
            <div class="bg-white shadow-md rounded-lg p-4 border-l-4 border-yellow-500">
                <div class="text-sm text-gray-600">Abiertos</div>
                <div class="text-2xl font-bold text-gray-800">{{ $estadisticas['abierto'] }}</div>
            </div>
            <div class="bg-white shadow-md rounded-lg p-4 border-l-4 border-orange-500">
                <div class="text-sm text-gray-600">En Progreso</div>
                <div class="text-2xl font-bold text-gray-800">{{ $estadisticas['en_progreso'] }}</div>
            </div>
            <div class="bg-white shadow-md rounded-lg p-4 border-l-4 border-green-500">
                <div class="text-sm text-gray-600">Cerrados</div>
                <div class="text-2xl font-bold text-gray-800">{{ $estadisticas['cerrado'] }}</div>
            </div>
        </div>

        <!-- Botón para crear nuevo ticket -->
        <div class="mb-4 text-right">
            <a href="{{ route('tickets.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded shadow">
                + Nuevo Ticket
            </a>
        </div>

        <!-- Tabla de mis tickets -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-4 bg-pink-100 border-b">
                <h3 class="text-lg font-semibold text-pink-700">Mis Tickets</h3>
            </div>
            <table class="w-full text-left border-collapse">
                <thead class="bg-pink-100 text-pink-700">
                    <tr>
                        <th class="py-3 px-4 border-b">#</th>
                        <th class="py-3 px-4 border-b">Título</th>
                        <th class="py-3 px-4 border-b">Descripción</th>
                        <th class="py-3 px-4 border-b">Prioridad</th>
                        <th class="py-3 px-4 border-b">Estado</th>
                        <th class="py-3 px-4 border-b">Fecha</th>
                        <th class="py-3 px-4 border-b text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr class="hover:bg-pink-50">
                            <td class="py-2 px-4 border-b">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4 border-b font-medium">{{ $ticket->titulo }}</td>
                            <td class="py-2 px-4 border-b">{{ Str::limit($ticket->descripcion, 50) }}</td>
                            <td class="py-2 px-4 border-b">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    @if($ticket->prioridad == 'alta') bg-red-100 text-red-800
                                    @elseif($ticket->prioridad == 'media') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($ticket->prioridad) }}
                                </span>
                            </td>
                            <td class="py-2 px-4 border-b">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    @if($ticket->estado == 'abierto') bg-blue-100 text-blue-800
                                    @elseif($ticket->estado == 'en progreso') bg-orange-100 text-orange-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($ticket->estado) }}
                                </span>
                            </td>
                            <td class="py-2 px-4 border-b text-sm text-gray-600">
                                {{ $ticket->created_at->format('d/m/Y') }}
                            </td>
                            <td class="py-2 px-4 border-b text-center">
                                @if($ticket->estado != 'en progreso')
                                    <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="inline-block" onsubmit="return confirmDelete(event)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow text-sm">
                                            Eliminar
                                        </button>
                                    </form>
                                @else
                                    <button type="button" disabled class="bg-gray-400 text-gray-600 px-3 py-1 rounded shadow text-sm cursor-not-allowed" title="No se puede eliminar un ticket en proceso">
                                        Eliminar
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-500">
                                <p class="mb-2">No tienes tickets aún 😅</p>
                                <a href="{{ route('tickets.create') }}" class="text-pink-600 hover:underline">Crear mi primer ticket</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(event) {
            event.preventDefault();
            Swal.fire({
                title: '¿Eliminar este ticket?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e53e3e',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            });
        }
    </script>
</x-app-layout>

