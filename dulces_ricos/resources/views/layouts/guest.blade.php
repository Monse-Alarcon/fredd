<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dulces Ricos </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-pink-100 via-rose-100 to-amber-100 relative">
    {{-- fondo decorativo --}}
    <div class="absolute inset-0 bg-[url('{{ asset('images/fondo-dulces.png') }}')] bg-center bg-cover opacity-10"></div>

    <main class="relative z-10 bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl p-8 w-full max-w-md border border-pink-200">
      <div class="flex flex-col items-center mb-6">
        <img src="{{ asset('images/logo-dulces.png') }}" alt="Logo Dulces Ricos" class="w-20 h-20 rounded-full shadow-md mb-2">
        <h1 class="text-3xl font-bold text-pink-600 tracking-wide">Dulces Ricos</h1>
        <p class="text-sm text-gray-500">Sistema de Gestión de Tickets</p>
      </div>

      {{ $slot }}
    </main>
  </body>
</html>
