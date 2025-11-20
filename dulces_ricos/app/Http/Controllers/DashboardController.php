<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        // Redirigir según el rol
        switch ($role) {
            case 'usuario':
                return $this->dashboardUsuario();
            case 'auxiliar':
                return $this->dashboardAuxiliar();
            case 'jefe':
                return $this->dashboardJefe();
            default:
                return redirect()->route('tickets.index');
        }
    }

    private function dashboardUsuario()
    {
        // Usuario ve solo sus tickets
        $tickets = Ticket::where('usuario_id', Auth::id())->get();
        
        $estadisticas = [
            'total' => $tickets->count(),
            'abierto' => $tickets->where('estado', 'abierto')->count(),
            'en_progreso' => $tickets->where('estado', 'en progreso')->count(),
            'cerrado' => $tickets->where('estado', 'cerrado')->count(),
        ];

        return view('dashboard-usuario', compact('tickets', 'estadisticas'));
    }

    private function dashboardAuxiliar()
    {
        // Auxiliar ve SOLO tickets asignados a él
        $auxiliarId = Auth::id();
        $ticketsAsignados = Ticket::where('auxiliar_id', $auxiliarId)
            ->with(['usuario'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $estadisticas = [
            'asignados' => Ticket::where('auxiliar_id', $auxiliarId)->count(),
            'en_progreso' => Ticket::where('auxiliar_id', $auxiliarId)->where('estado', 'en progreso')->count(),
            'abierto' => Ticket::where('auxiliar_id', $auxiliarId)->where('estado', 'abierto')->count(),
            'cerrado' => Ticket::where('auxiliar_id', $auxiliarId)->where('estado', 'cerrado')->count(),
        ];

        return view('dashboard-auxiliar', compact('ticketsAsignados', 'estadisticas'));
    }

    private function dashboardJefe()
    {
        // Obtener filtros de fecha
        $filtroTipo = request()->get('filtro_tipo', 'todos');
        $fechaInicio = request()->get('fecha_inicio');
        $fechaFin = request()->get('fecha_fin');

        // Construir query base
        $query = Ticket::with(['usuario', 'auxiliar', 'departamento']);

        // Aplicar filtros de fecha
        if ($filtroTipo === 'semana') {
            $query->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]);
        } elseif ($filtroTipo === 'mes') {
            $query->whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ]);
        } elseif ($filtroTipo === 'rango' && $fechaInicio && $fechaFin) {
            $query->whereBetween('created_at', [
                Carbon::parse($fechaInicio)->startOfDay(),
                Carbon::parse($fechaFin)->endOfDay()
            ]);
        }

        $tickets = $query->orderBy('created_at', 'desc')->get();
        
        // Estadísticas generales (sin filtros)
        $estadisticas = [
            'total' => Ticket::count(),
            'abierto' => Ticket::where('estado', 'abierto')->count(),
            'en_progreso' => Ticket::where('estado', 'en progreso')->count(),
            'cerrado' => Ticket::where('estado', 'cerrado')->count(),
            'rechazado' => Ticket::where('estado', 'rechazado')->count() ?? 0,
            'alta' => Ticket::where('prioridad', 'alta')->count(),
            'media' => Ticket::where('prioridad', 'media')->count(),
            'baja' => Ticket::where('prioridad', 'baja')->count(),
            'sin_asignar' => Ticket::whereNull('auxiliar_id')->whereNull('departamento_id')->where('estado', '!=', 'cerrado')->count(),
            'asignados' => Ticket::where(function($q) {
                $q->whereNotNull('auxiliar_id')->orWhereNotNull('departamento_id');
            })->count(),
        ];

        // Datos para gráficas
        $datosGraficas = [
            'atendidos' => Ticket::where('estado', 'cerrado')->count(),
            'rechazados' => Ticket::where('estado', 'rechazado')->count() ?? 0,
            'pendientes' => Ticket::where('estado', 'abierto')->count(),
            'en_proceso' => Ticket::where('estado', 'en progreso')->count(),
        ];

        $usuarios = User::all();
        $auxiliares = User::where('role', 'auxiliar')->get();
        $departamentos = Departamento::all();

        return view('dashboard-jefe', compact('tickets', 'estadisticas', 'usuarios', 'auxiliares', 'departamentos', 'datosGraficas', 'filtroTipo', 'fechaInicio', 'fechaFin'));
    }
}

