<!DOCTYPE html>
<html lang="eS">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Carnets de Estudiantes</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <title>Carnets QR - Estudiantes</title>

    <link rel="stylesheet" href="{{ public_path('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ public_path('css/pdf/carnet-qr.css') }}">
</head>

<body>
    <h2>Lista de Carnets Generados</h2>

    <table>
        <tr>
            @foreach ($matriculas as $index => $matricula)
                @if ($index % 2 == 0) </tr><tr> @endif
                <td>
                    <div class="card">
                        <img class="card__perfil" src="{{$matricula->alumno->imagen_url ? 'Tiene imagen': public_path('img/usuario.svg') }}" alt="Imagen de Perfil - {{ $matricula->alumno->nombres }}">
                        <h2>{{ $matricula->alumno->nombres }} {{ $matricula->alumno->apellidos }}</h2>
                        <p>Grado: {{ $matricula->grado->nombre }}</p>
                        <p>Sección: {{ $matricula->seccion->nombre }}</p>
                        <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(100)->generate($matricula->alumno->dni)) }}" alt="QR">
                        </div>
                    <!-- Generar el QR con el DNI -->
                    
                    {{$matricula->alumno->dni}}

                    </td>
            @endforeach
        </tr>
    </table>

</body>

</html>