<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Jefe ve todos los tickets y estadísticas
        $tickets = Ticket::with(['usuario', 'auxiliar'])->orderBy('created_at', 'desc')->get();
        
        $estadisticas = [
            'total' => Ticket::count(),
            'abierto' => Ticket::where('estado', 'abierto')->count(),
            'en_progreso' => Ticket::where('estado', 'en progreso')->count(),
            'cerrado' => Ticket::where('estado', 'cerrado')->count(),
            'alta' => Ticket::where('prioridad', 'alta')->count(),
            'media' => Ticket::where('prioridad', 'media')->count(),
            'baja' => Ticket::where('prioridad', 'baja')->count(),
            'sin_asignar' => Ticket::whereNull('auxiliar_id')->where('estado', '!=', 'cerrado')->count(),
            'asignados' => Ticket::whereNotNull('auxiliar_id')->count(),
        ];

        $usuarios = User::all();
        $auxiliares = User::where('role', 'auxiliar')->get();

        return view('dashboard-jefe', compact('tickets', 'estadisticas', 'usuarios', 'auxiliares'));
    }
}

