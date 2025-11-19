<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReporteController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()?->role === 'jefe', 403);

        return view('reportes.index');
    }

    public function generarPDF(Request $request)
    {
        abort_unless(Auth::user()?->role === 'jefe', 403);

        $request->validate([
            'tipo' => 'required|in:semanal,mensual,bimestral,rango',
            'fecha_inicio' => 'required_if:tipo,rango|date',
            'fecha_fin' => 'required_if:tipo,rango|date|after_or_equal:fecha_inicio',
        ]);

        $tickets = $this->obtenerTicketsPorPeriodo($request);

        $estadisticas = [
            'total' => $tickets->count(),
            'abierto' => $tickets->where('estado', 'abierto')->count(),
            'en_progreso' => $tickets->where('estado', 'en progreso')->count(),
            'cerrado' => $tickets->where('estado', 'cerrado')->count(),
            'alta' => $tickets->where('prioridad', 'alta')->count(),
            'media' => $tickets->where('prioridad', 'media')->count(),
            'baja' => $tickets->where('prioridad', 'baja')->count(),
        ];

        $periodo = $this->obtenerPeriodoTexto($request);

        $pdf = Pdf::loadView('reportes.pdf', compact('tickets', 'estadisticas', 'periodo'));
        
        $nombreArchivo = 'reporte_tickets_' . $request->tipo . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($nombreArchivo);
    }

    private function obtenerTicketsPorPeriodo(Request $request)
    {
        $tipo = $request->tipo;
        $query = Ticket::query();

        switch ($tipo) {
            case 'semanal':
                $inicio = Carbon::now()->startOfWeek();
                $fin = Carbon::now()->endOfWeek();
                break;
            case 'mensual':
                $inicio = Carbon::now()->startOfMonth();
                $fin = Carbon::now()->endOfMonth();
                break;
            case 'bimestral':
                $inicio = Carbon::now()->subMonths(2)->startOfMonth();
                $fin = Carbon::now()->endOfMonth();
                break;
            case 'rango':
                $inicio = Carbon::parse($request->fecha_inicio)->startOfDay();
                $fin = Carbon::parse($request->fecha_fin)->endOfDay();
                break;
            default:
                $inicio = Carbon::now()->startOfMonth();
                $fin = Carbon::now()->endOfMonth();
        }

        return $query->whereBetween('created_at', [$inicio, $fin])
            ->with(['usuario', 'auxiliar'])
            ->get();
    }

    private function obtenerPeriodoTexto(Request $request)
    {
        switch ($request->tipo) {
            case 'semanal':
                return 'Semanal (' . Carbon::now()->startOfWeek()->format('d/m/Y') . ' - ' . Carbon::now()->endOfWeek()->format('d/m/Y') . ')';
            case 'mensual':
                return 'Mensual (' . Carbon::now()->format('F Y') . ')';
            case 'bimestral':
                return 'Bimestral (' . Carbon::now()->subMonths(2)->format('F Y') . ' - ' . Carbon::now()->format('F Y') . ')';
            case 'rango':
                return 'Rango (' . Carbon::parse($request->fecha_inicio)->format('d/m/Y') . ' - ' . Carbon::parse($request->fecha_fin)->format('d/m/Y') . ')';
            default:
                return 'Período no especificado';
        }
    }
}

