<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Tickets - {{ $periodo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ec4899;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #ec4899;
            margin: 0;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .estadisticas {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .estadistica-box {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
        }
        .estadistica-box h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #666;
        }
        .estadistica-box .numero {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #fce7f3;
            color: #ec4899;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-alta { background-color: #fee2e2; color: #991b1b; }
        .badge-media { background-color: #fef3c7; color: #92400e; }
        .badge-baja { background-color: #d1fae5; color: #065f46; }
        .badge-abierto { background-color: #dbeafe; color: #1e40af; }
        .badge-progreso { background-color: #fed7aa; color: #9a3412; }
        .badge-cerrado { background-color: #d1fae5; color: #065f46; }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Tickets</h1>
        <p>{{ $periodo }}</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="estadisticas">
        <div class="estadistica-box">
            <h3>Total</h3>
            <div class="numero">{{ $estadisticas['total'] }}</div>
        </div>
        <div class="estadistica-box">
            <h3>Abiertos</h3>
            <div class="numero">{{ $estadisticas['abierto'] }}</div>
        </div>
        <div class="estadistica-box">
            <h3>En Progreso</h3>
            <div class="numero">{{ $estadisticas['en_progreso'] }}</div>
        </div>
        <div class="estadistica-box">
            <h3>Cerrados</h3>
            <div class="numero">{{ $estadisticas['cerrado'] }}</div>
        </div>
    </div>

    <div class="estadisticas">
        <div class="estadistica-box">
            <h3>Prioridad Alta</h3>
            <div class="numero">{{ $estadisticas['alta'] }}</div>
        </div>
        <div class="estadistica-box">
            <h3>Prioridad Media</h3>
            <div class="numero">{{ $estadisticas['media'] }}</div>
        </div>
        <div class="estadistica-box">
            <h3>Prioridad Baja</h3>
            <div class="numero">{{ $estadisticas['baja'] }}</div>
        </div>
        <div class="estadistica-box">
            <h3>Tasa de Cierre</h3>
            <div class="numero">
                {{ $estadisticas['total'] > 0 ? number_format(($estadisticas['cerrado'] / $estadisticas['total']) * 100, 1) : 0 }}%
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Título</th>
                <th>Creado por</th>
                <th>Asignado a</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Fecha Creación</th>
                <th>Fecha Finalización</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $ticket->titulo }}</td>
                    <td>{{ $ticket->usuario ? $ticket->usuario->name : 'N/A' }}</td>
                    <td>{{ $ticket->auxiliar ? $ticket->auxiliar->name : 'Sin asignar' }}</td>
                    <td>
                        <span class="badge badge-{{ $ticket->prioridad }}">
                            {{ ucfirst($ticket->prioridad) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ str_replace(' ', '', $ticket->estado) }}">
                            {{ ucfirst($ticket->estado) }}
                        </span>
                    </td>
                    <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $ticket->fecha_finalizacion ? $ticket->fecha_finalizacion->format('d/m/Y H:i') : 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">No hay tickets en este período</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Sistema de Gestión de Tickets - Dulces Ricos</p>
    </div>
</body>
</html>

