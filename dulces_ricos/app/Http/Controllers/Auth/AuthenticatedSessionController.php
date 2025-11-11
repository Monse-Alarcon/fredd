<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use Illuminate\Support\Str;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
{
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
        return back()->withErrors([
            'email' => 'Las credenciales son incorrectas.',
        ]);
    }

    $request->session()->regenerate();

    //  Generar token único para esta sesión
    $user = Auth::user();
    $user->session_token = Str::random(60);
    $user->save();

    // Guardar token en la sesión actual
    session(['session_token' => $user->session_token]);

    return redirect()->intended('dashboard'); // o a donde rediriges tras login
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
