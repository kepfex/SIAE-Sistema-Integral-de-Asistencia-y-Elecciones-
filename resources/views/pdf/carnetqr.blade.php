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

<style>
    @page {
        margin-top: 5mm;    /* Margen superior */
        margin-bottom: 5mm; /* Margen inferior */
    }
</style>

<body>
    <table width="100%">
        @foreach ($matriculas as $index => $matricula)
        @if ($index % 2 == 0)
        <tr> <!-- Nueva fila cada 2 tarjetas -->
            @endif
            <td>
                <div class="card" style="width: {{ count($matriculas) == 1 ? '50%' : '100%' }};">
                    <p class="nombre_ie">C.N.E. "Aurelio Cárdenas Pachas"</p>

                    <table width="100%">
                        <tr>
                            <!-- Columna 1: Datos del Estudiante -->
                            <td width="50%">
                                <div class="datos_estudiante">
                                    <img class="card__perfil" src="{{ $matricula->alumno->imagen_url ? 'Tiene imagen': public_path('img/usuario.svg') }}" alt="Imagen de Perfil - {{ $matricula->alumno->nombres }}">
                                    <p class="estudiante_nombres">
                                        {{ $matricula->alumno->nombres }} <br>
                                        <span class="estudiante_apellidos">
                                            {{ $matricula->alumno->apellido_paterno }} {{ $matricula->alumno->apellido_materno }}
                                        </span>
                                    </p>

                                    <p style="color: blue;">Grado: <b style="font-size: 14px;">{{ $matricula->grado->nombre }}</b></p>
                                    <p style="color: blue;">Sección: <b>{{ $matricula->seccion->nombre }}</b></p>
                                </div>
                            </td>

                            <!-- Columna 2: Código QR -->
                            <td width="50%">
                                <div class="qr_content">
                                    <img class="qr_image" src="{{ public_path('storage/qrcodes/' . $matricula->alumno->codigo_qr) . '.png' }}" alt="imagen qr">
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            @if ($index % 2 == 1 || $index == count($matriculas) - 1)
        </tr> <!-- Cerrar fila después de 2 tarjetas o si es el último elemento -->
        @endif
        @endforeach
    </table>

</body>

</html>