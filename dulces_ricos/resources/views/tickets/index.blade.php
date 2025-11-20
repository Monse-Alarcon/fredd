<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
             Lista de Tickets
        </h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto">
        <!-- Mensaje de éxito -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Estadísticas solo para jefe -->
        @if (Auth::user()->role === 'jefe' && isset($estadisticas))
            <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white shadow-md rounded-lg p-4 border-l-4 border-blue-500">
                    <div class="text-sm text-gray-600">Total de Tickets</div>
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
        @endif

        <!-- Botón para crear nuevo ticket (solo para usuario y auxiliar) -->
        @if(Auth::user()->role !== 'jefe')
            <div class="mb-4 text-right">
                <a href="{{ route('tickets.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded shadow">
                     Nuevo Ticket
                </a>
            </div>
        @endif

        <!-- Tabla de tickets -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-pink-100 text-pink-700">
                    <tr>
                        <th class="py-3 px-4 border-b">#</th>
                        <th class="py-3 px-4 border-b">Título</th>
                        <th class="py-3 px-4 border-b">Descripción</th>
                        <th class="py-3 px-4 border-b">Prioridad</th>
                        <th class="py-3 px-4 border-b">Estado</th>
                        <th class="py-3 px-4 border-b text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr class="hover:bg-pink-50">
                            <td class="py-2 px-4 border-b">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4 border-b">{{ $ticket->titulo }}</td>
                            <td class="py-2 px-4 border-b">{{ $ticket->descripcion }}</td>
                            <td class="py-2 px-4 border-b">{{ ucfirst($ticket->prioridad) }}</td>
                            <td class="py-2 px-4 border-b">{{ ucfirst($ticket->estado) }}</td>
                            <td class="py-2 px-4 border-b text-center">
                                @if($ticket->estado != 'en progreso')
                                    <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="inline-block" onsubmit="return confirmDelete(event)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow">
                                            Eliminar
                                        </button>
                                    </form>
                                @else
                                    <button type="button" disabled class="bg-gray-400 text-gray-600 px-3 py-1 rounded shadow cursor-not-allowed" title="No se puede eliminar un ticket en proceso">
                                        Eliminar
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-3 px-4 text-center text-gray-500">No hay tickets aún 😅</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!--  Script SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(event) {
            event.preventDefault(); // Evita envío inmediato

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
