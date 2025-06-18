<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ACP - Registro de Asistencias</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')  {{-- Agregado --}}
    @livewireStyles
</head>

<body>
    {{ $slot }}

    @livewireScripts
    @stack('scripts') {{-- Agregado --}}
    <!-- <script src="{{ asset('js/html5-qrcode.min.js') }}"></script> -->
</body>

</html>