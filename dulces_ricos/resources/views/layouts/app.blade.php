<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Dulces Ricos') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Fondo principal */
        body {
            background: linear-gradient(135deg, #f9a8d4, #fde68a);
            min-height: 100vh;
        }

        /*  Logo Dulces Ricos */
        nav img {
            max-height: 55px;
            width: auto;
            transition: transform 0.3s ease;
        }
        nav img:hover {
            transform: scale(1.07);
        }

        /*  Header */
        header {
            background-color: #fff;
            border-bottom: 4px solid #db2777;
            box-shadow: 0 2px 6px rgba(219, 39, 119, 0.2);
        }

        /*  Botones estilo Dulces Ricos (color más fuerte) */
        .btn-dulce {
            background: linear-gradient(90deg, #db2777, #be185d);
            color: white;
            font-weight: 700;
            border-radius: 0.5rem;
            padding: 0.6rem 1.2rem;
            text-align: center;
            box-shadow: 0 4px 10px rgba(219, 39, 119, 0.4);
            transition: all 0.3s ease;
        }

        .btn-dulce:hover {
            background: linear-gradient(90deg, #be185d, #9d174d);
            box-shadow: 0 6px 14px rgba(219, 39, 119, 0.6);
            transform: translateY(-1px);
        }

        /*  Contenido principal */
        main {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="font-sans antialiased">

    <div class="min-h-screen">
        <!--  Barra de navegación -->
        @include('layouts.navigation')

        <!-- Encabezado -->
        @isset($header)
            <header>
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Contenido -->
        <main class="max-w-7xl mx-auto mt-6">
            {{ $slot }}
        </main>
    </div>

</body>
</html>
