<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Dashboard - Jefe') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto">
        <!-- Mensaje de éxito -->
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

        <!-- Estadísticas del jefe -->
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

        <!-- Estadísticas adicionales -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="bg-white shadow-md rounded-lg p-4 border-l-4 border-red-500">
                <div class="text-sm text-gray-600">Prioridad Alta</div>
                <div class="text-2xl font-bold text-gray-800">{{ $estadisticas['alta'] }}</div>
            </div>
            <div class="bg-white shadow-md rounded-lg p-4 border-l-4 border-yellow-500">
                <div class="text-sm text-gray-600">Prioridad Media</div>
                <div class="text-2xl font-bold text-gray-800">{{ $estadisticas['media'] }}</div>
            </div>
            <div class="bg-white shadow-md rounded-lg p-4 border-l-4 border-green-500">
                <div class="text-sm text-gray-600">Prioridad Baja</div>
                <div class="text-2xl font-bold text-gray-800">{{ $estadisticas['baja'] }}</div>
            </div>
            <div class="bg-white shadow-md rounded-lg p-4 border-l-4 border-purple-500">
                <div class="text-sm text-gray-600">Sin Asignar</div>
                <div class="text-2xl font-bold text-gray-800">{{ $estadisticas['sin_asignar'] }}</div>
            </div>
            <div class="bg-white shadow-md rounded-lg p-4 border-l-4 border-indigo-500">
                <div class="text-sm text-gray-600">Asignados</div>
                <div class="text-2xl font-bold text-gray-800">{{ $estadisticas['asignados'] }}</div>
            </div>
        </div>

        <!-- Botón para generar PDF -->
        <div class="mb-6">
            <a href="{{ route('reportes.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow font-semibold inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Generar Reporte PDF
            </a>
        </div>

        <!-- Resumen de usuarios -->
        <div class="mb-6 bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-4 bg-pink-100 border-b">
                <h3 class="text-lg font-semibold text-pink-700">Resumen del Sistema</h3>
            </div>
            <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded">
                    <div class="text-2xl font-bold text-gray-800">{{ $usuarios->count() }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Usuarios</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded">
                    <div class="text-2xl font-bold text-gray-800">{{ $auxiliares->count() }}</div>
                    <div class="text-sm text-gray-600 mt-1">Auxiliares</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded">
                    <div class="text-2xl font-bold text-gray-800">{{ $usuarios->where('role', 'usuario')->count() }}</div>
                    <div class="text-sm text-gray-600 mt-1">Usuarios</div>
                </div>
            </div>
        </div>

        <!-- Tabla de todos los tickets con asignación -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-4 bg-pink-100 border-b">
                <h3 class="text-lg font-semibold text-pink-700">Todos los Tickets</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-pink-100 text-pink-700">
                        <tr>
                            <th class="py-3 px-4 border-b">#</th>
                            <th class="py-3 px-4 border-b">Título</th>
                            <th class="py-3 px-4 border-b">Creado por</th>
                            <th class="py-3 px-4 border-b">Prioridad</th>
                            <th class="py-3 px-4 border-b">Estado</th>
                            <th class="py-3 px-4 border-b">Asignado a</th>
                            <th class="py-3 px-4 border-b">Fecha</th>
                            <th class="py-3 px-4 border-b text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tickets as $ticket)
                            <tr class="hover:bg-pink-50">
                                <td class="py-2 px-4 border-b">{{ $loop->iteration }}</td>
                                <td class="py-2 px-4 border-b font-medium">{{ $ticket->titulo }}</td>
                                <td class="py-2 px-4 border-b">
                                    {{ $ticket->usuario ? $ticket->usuario->name : 'N/A' }}
                                </td>
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
                                <td class="py-2 px-4 border-b">
                                    @if($ticket->auxiliar)
                                        <span class="text-sm">{{ $ticket->auxiliar->name }}</span>
                                    @else
                                        <span class="text-sm text-gray-400">Sin asignar</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">
                                    {{ $ticket->created_at->format('d/m/Y') }}
                                </td>
                                <td class="py-2 px-4 border-b">
                                    <div class="flex gap-2 justify-center">
                                        <a href="{{ route('tickets.show', $ticket->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded shadow text-sm">
                                            Ver
                                        </a>
                                        @if(!$ticket->auxiliar_id && $ticket->estado != 'cerrado')
                                            <button onclick="mostrarModalAsignar({{ $ticket->id }})" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded shadow text-sm">
                                                Asignar
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-gray-500">
                                    No hay tickets en el sistema
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para asignar ticket -->
    <div id="modalAsignar" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Asignar Ticket a Auxiliar</h3>
                <form id="formAsignar" method="POST" action="">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Auxiliar</label>
                        <select name="auxiliar_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                            <option value="">Seleccione un auxiliar</option>
                            @foreach($auxiliares as $auxiliar)
                                <option value="{{ $auxiliar->id }}">{{ $auxiliar->name }} ({{ $auxiliar->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2 justify-end">
                        <button type="button" onclick="cerrarModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                            Cancelar
                        </button>
                        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded">
                            Asignar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function mostrarModalAsignar(ticketId) {
            const modal = document.getElementById('modalAsignar');
            const form = document.getElementById('formAsignar');
            form.action = `/tickets/${ticketId}/asignar`;
            modal.classList.remove('hidden');
        }

        function cerrarModal() {
            const modal = document.getElementById('modalAsignar');
            modal.classList.add('hidden');
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modal = document.getElementById('modalAsignar');
            if (event.target == modal) {
                cerrarModal();
            }
        }
    </script>
</x-app-layout>
