<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

//  Agrupamos todo dentro del middleware 'web'
Route::middleware(['web'])->group(function () {

    // Página principal
    Route::get('/', function () {
        return view('welcome');
    });

    // Dashboard según rol
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

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
        Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
        Route::patch('/tickets/{id}/status', [TicketController::class, 'updateStatus'])->name('tickets.update-status');
        Route::post('/tickets/{id}/asignar', [TicketController::class, 'asignar'])->name('tickets.asignar');
        Route::delete('/tickets/{id}', [TicketController::class, 'destroy'])->name('tickets.destroy');

        // Reportes (validación dentro del controlador)
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::post('/reportes/generar-pdf', [ReporteController::class, 'generarPDF'])->name('reportes.generar-pdf');
    });

    //  Autenticación (login, registro, etc.)
    require __DIR__.'/auth.php';
});
