<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Detalle del Ticket') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="mb-4">
                    <a href="{{ route('dashboard') }}" class="text-pink-600 hover:underline">← Volver al dashboard</a>
                </div>

                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $ticket->titulo }}</h1>
                    <div class="flex gap-2 mb-4">
                        <span class="px-3 py-1 rounded text-sm font-semibold
                            @if($ticket->prioridad == 'alta') bg-red-100 text-red-800
                            @elseif($ticket->prioridad == 'media') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800
                            @endif">
                            Prioridad: {{ ucfirst($ticket->prioridad) }}
                        </span>
                        <span class="px-3 py-1 rounded text-sm font-semibold
                            @if($ticket->estado == 'abierto') bg-blue-100 text-blue-800
                            @elseif($ticket->estado == 'en progreso') bg-orange-100 text-orange-800
                            @else bg-green-100 text-green-800
                            @endif">
                            Estado: {{ ucfirst($ticket->estado) }}
                        </span>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Descripción del Problema</h3>
                    <div class="bg-gray-50 p-4 rounded-lg border">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->descripcion }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6 text-sm text-gray-600">
                    <div>
                        <span class="font-semibold">Creado:</span> {{ $ticket->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div>
                        <span class="font-semibold">Última actualización:</span> {{ $ticket->updated_at->format('d/m/Y H:i') }}
                    </div>
                    @if($ticket->fecha_asignacion)
                        <div>
                            <span class="font-semibold">Fecha de asignación:</span> {{ $ticket->fecha_asignacion->format('d/m/Y H:i') }}
                        </div>
                    @endif
                    @if($ticket->fecha_finalizacion)
                        <div>
                            <span class="font-semibold">Fecha de finalización:</span> {{ $ticket->fecha_finalizacion->format('d/m/Y H:i') }}
                        </div>
                    @endif
                    @if($ticket->auxiliar)
                        <div>
                            <span class="font-semibold">Asignado a:</span> {{ $ticket->auxiliar->name }}
                        </div>
                    @endif
                    @if($ticket->usuario)
                        <div>
                            <span class="font-semibold">Creado por:</span> {{ $ticket->usuario->name }}
                        </div>
                    @endif
                </div>

                @if(Auth::user()->role === 'auxiliar' && $ticket->estado != 'cerrado')
                    <div class="border-t pt-4">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">Cambiar Estado</h3>
                        <form action="{{ route('tickets.update-status', $ticket->id) }}" method="POST" class="flex gap-2">
                            @csrf
                            @method('PATCH')
                            @if($ticket->estado == 'abierto')
                                <input type="hidden" name="estado" value="en progreso">
                                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded shadow">
                                    Marcar como En Proceso
                                </button>
                            @elseif($ticket->estado == 'en progreso')
                                <input type="hidden" name="estado" value="cerrado">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
                                    Finalizar Ticket
                                </button>
                            @endif
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

