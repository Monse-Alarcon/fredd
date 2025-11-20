<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Generar Reporte PDF') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="mb-4">
                <a href="{{ route('dashboard') }}" class="text-pink-600 hover:underline">← Volver al dashboard</a>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-6">Seleccione el período para el reporte</h3>

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('reportes.generar-pdf') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Reporte</label>
                    <select name="tipo" id="tipo" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                        <option value="">Seleccione un tipo</option>
                        <option value="semanal" {{ old('tipo') === 'semanal' ? 'selected' : '' }}>Semanal</option>
                        <option value="mensual" {{ old('tipo') === 'mensual' ? 'selected' : '' }}>Mensual</option>
                        <option value="bimestral" {{ old('tipo') === 'bimestral' ? 'selected' : '' }}>Bimestral</option>
                        <option value="rango" {{ old('tipo') === 'rango' ? 'selected' : '' }}>Rango de fechas</option>
                    </select>
                </div>

                <div id="rango-fechas" class="mb-6 {{ old('tipo') === 'rango' ? '' : 'hidden' }}">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                            <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 justify-end">
                    <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Descargar PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tipoSelect = document.getElementById('tipo');
            const rangoFechas = document.getElementById('rango-fechas');

            const toggleRango = () => {
                if (tipoSelect.value === 'rango') {
                    rangoFechas.classList.remove('hidden');
                    rangoFechas.querySelectorAll('input').forEach(input => input.setAttribute('required', 'required'));
                } else {
                    rangoFechas.classList.add('hidden');
                    rangoFechas.querySelectorAll('input').forEach(input => input.removeAttribute('required'));
                }
            };

            tipoSelect.addEventListener('change', toggleRango);
            toggleRango();
        });
    </script>
</x-app-layout>

