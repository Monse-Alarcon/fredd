<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

//  Agrupamos todo dentro del middleware 'web'
Route::middleware(['web'])->group(function () {

    // Página principal
    Route::get('/', function () {
        return view('welcome');
    });

    // Redirigir dashboard a lista de tickets
    Route::get('/dashboard', function () {
        return redirect()->route('tickets.index');
    })->middleware(['auth', 'verified'])->name('dashboard');

    //  Perfil y tickets protegidos por 'auth'
    Route::middleware(['auth'])->group(function () {

        // Perfil de usuario
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Tickets
        Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
        Route::delete('/tickets/{id}', [TicketController::class, 'destroy'])->name('tickets.destroy'); // 👈 Ruta para eliminar ticket
    });

    //  Autenticación (login, registro, etc.)
    require __DIR__.'/auth.php';
});
