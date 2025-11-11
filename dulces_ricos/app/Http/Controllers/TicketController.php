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
        return view('tickets.index', compact('tickets'));
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
        ]);

        return redirect()->route('tickets.index')->with('success', ' Ticket creado correctamente.');
    }

    //  Eliminar ticket
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', ' Ticket eliminado correctamente.');
    }
}
