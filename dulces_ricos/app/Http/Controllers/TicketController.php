<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // Mostrar lista de tickets
    public function index()
    {
        $tickets = Ticket::all();
        
        // Estadísticas solo para jefe
        $estadisticas = null;
        if (auth()->user()->role === 'jefe') {
            $estadisticas = [
                'abierto' => Ticket::where('estado', 'abierto')->count(),
                'en_progreso' => Ticket::where('estado', 'en progreso')->count(),
                'cerrado' => Ticket::where('estado', 'cerrado')->count(),
                'total' => Ticket::count(),
            ];
        }
        
        return view('tickets.index', compact('tickets', 'estadisticas'));
    }

    // Mostrar formulario para crear un nuevo ticket
    public function create()
    {
        return view('tickets.create');
    }

    // Guardar ticket nuevo
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'prioridad' => 'required|string',
        ]);

        Ticket::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'prioridad' => $request->prioridad,
            'estado' => 'abierto',
            'usuario_id' => auth()->id(), // Asignar el usuario que crea el ticket
        ]);

        return redirect()->route('dashboard')->with('success', ' Ticket creado correctamente.');
    }

    // Ver ticket individual
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }

    // Actualizar estatus del ticket (para auxiliar)
    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        $request->validate([
            'estado' => 'required|in:abierto,en progreso,cerrado',
        ]);

        $ticket->estado = $request->estado;
        
        // Si el estado cambia a "en progreso" y no tiene auxiliar asignado, asignar al auxiliar actual
        if ($request->estado === 'en progreso' && !$ticket->auxiliar_id) {
            $ticket->auxiliar_id = auth()->id();
            $ticket->fecha_asignacion = now();
        }
        
        // Si el estado cambia a "cerrado", guardar fecha de finalización
        if ($request->estado === 'cerrado') {
            $ticket->fecha_finalizacion = now();
        }
        
        $ticket->save();

        return redirect()->route('dashboard')->with('success', 'Estado del ticket actualizado correctamente.');
    }

    // Asignar ticket a auxiliar (para jefe)
    public function asignar(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        $request->validate([
            'auxiliar_id' => 'required|exists:users,id',
        ]);

        $auxiliar = User::findOrFail($request->auxiliar_id);
        
        if ($auxiliar->role !== 'auxiliar') {
            return redirect()->back()->withErrors(['auxiliar_id' => 'El usuario seleccionado no es un auxiliar.']);
        }

        $ticket->auxiliar_id = $request->auxiliar_id;
        $ticket->fecha_asignacion = now();
        $ticket->save();

        return redirect()->route('dashboard')->with('success', 'Ticket asignado correctamente al auxiliar.');
    }

    //  Eliminar ticket
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('dashboard')->with('success', ' Ticket eliminado correctamente.');
    }
}
