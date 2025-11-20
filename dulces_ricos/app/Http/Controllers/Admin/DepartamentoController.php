<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartamentoController extends Controller
{
    /**
     * Ensure only bosses can access department management.
     */
    private function ensureBoss(): void
    {
        abort_unless(Auth::user()?->role === 'jefe', 403);
    }

    public function index()
    {
        $this->ensureBoss();
        $departamentos = Departamento::withCount('usuarios')->get();
        return view('admin.departamentos.index', compact('departamentos'));
    }

    public function create()
    {
        $this->ensureBoss();
        return view('admin.departamentos.create');
    }

    public function store(Request $request)
    {
        $this->ensureBoss();
        $request->validate([
            'nombre' => 'required|string|max:255|unique:departamentos,nombre',
            'descripcion' => 'nullable|string',
        ]);

        Departamento::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('admin.departamentos.index')
            ->with('success', 'Departamento creado correctamente.');
    }

    public function edit($id)
    {
        $this->ensureBoss();
        $departamento = Departamento::findOrFail($id);
        return view('admin.departamentos.edit', compact('departamento'));
    }

    public function update(Request $request, $id)
    {
        $this->ensureBoss();
        $departamento = Departamento::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255|unique:departamentos,nombre,' . $id,
            'descripcion' => 'nullable|string',
        ]);

        $departamento->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('admin.departamentos.index')
            ->with('success', 'Departamento actualizado correctamente.');
    }

    public function destroy($id)
    {
        $this->ensureBoss();
        $departamento = Departamento::findOrFail($id);
        
        // Verificar si tiene usuarios asignados
        if ($departamento->usuarios()->count() > 0) {
            return redirect()->back()
                ->withErrors(['error' => 'No se puede eliminar el departamento porque tiene usuarios asignados.']);
        }

        $departamento->delete();

        return redirect()->route('admin.departamentos.index')
            ->with('success', 'Departamento eliminado correctamente.');
    }
}
