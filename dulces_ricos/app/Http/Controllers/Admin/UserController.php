<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            abort_unless(Auth::user()?->role === 'jefe', 403);
            return $next($request);
        });
    }

    public function index()
    {
        $usuarios = User::with('departamento')->get();
        return view('admin.users.index', compact('usuarios'));
    }

    public function create()
    {
        $departamentos = Departamento::all();
        return view('admin.users.create', compact('departamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:usuario,auxiliar,jefe',
            'departamento_id' => 'nullable|exists:departamentos,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'departamento_id' => $request->departamento_id,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $departamentos = Departamento::all();
        return view('admin.users.edit', compact('usuario', 'departamentos'));
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:usuario,auxiliar,jefe',
            'departamento_id' => 'nullable|exists:departamentos,id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'departamento_id' => $request->departamento_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        
        // No permitir eliminar al propio usuario jefe
        if ($usuario->id === Auth::id()) {
            return redirect()->back()
                ->withErrors(['error' => 'No puedes eliminar tu propio usuario.']);
        }

        $usuario->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
