<!DOCTYPE html>
<html lang="eS">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Carnets QR - Estudiantes</title>

    <!-- <link rel="stylesheet" href="{{ public_path('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ public_path('css/pdf/carnet-qr.css') }}"> -->
    <!-- <link rel="stylesheet" href="{{ public_path('build/assets/app-SxQOaE6u.css') }}"> -->

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

    <!-- @stack('styles')  {{-- Agregado --}} -->
</head>

<!-- <style>
    @page {
        margin-top: 5mm;
        /* Margen superior */
        margin-bottom: 5mm;
        /* Margen inferior */
    }
</style> -->

<body>
    <section class="flex flex-wrap justify-center  gap-[2px]">
        @foreach ($matriculas as $index => $matricula)
        <div class="w-[340px] h-52 border border-blue-600 rounded-md overflow-hidden flex flex-col relative">
            <div class="absolute bottom-1 right-2 flex gap-1 items-end text-sm text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-phone-call">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                    <path d="M15 7a2 2 0 0 1 2 2" />
                    <path d="M15 3a6 6 0 0 1 6 6" />
                </svg>
                <span class="font-medium leading-none">{{ $matricula->alumno->celular ? $matricula->alumno->celular : '---' }}</span>
            </div>
            <div class="bg-blue-600 text-center relative py-2">
                <p class="text-white font-medium text-sm leading-none">C.N.E. "Aurelio Cárdenas Pachas"</p>
                <img class="w-9 absolute left-2 top-2" src="{{ public_path('img/insignia.png') }}" alt="insignia ACP">
            </div>
            <div class="flex gap-1 justify-between flex-1 px-2">
                <div class="flex-1">
                    <div class="mt-2 ml-2 flex ">
                        @php
                        $rutaImagen = public_path('storage/' . $matricula->alumno->imagen_url);
                        $rutaImagen = (!empty($matricula->alumno->imagen_url) && file_exists($rutaImagen))
                        ? $rutaImagen
                        : public_path('img/usuario.svg');
                        @endphp
                        <img class="w-24 h-28 overflow-hidden rounded-xl object-cover object-top border-2 border-green-600"
                            src="{{ $rutaImagen }}" alt="foto alumno(a)">
                        <div class="relative text-gray-800">
                            <div class="absolute -left-[1px]">
                                <div class="mt-2">
                                    <span class="text-xs ml-1">Grado:</span>
                                    <p class="text-[13px] py-[2px] px-1 font-semibold border border-green-600 bg-green-100 rounded-r-md leading-none">
                                        {{ $matricula->grado->nombre }}
                                    </p>
                                </div>
                                <div class="mt-1">
                                    <span class="text-xs ml-1 block">Sección:</span>
                                    <p class="text-[13px] font-semibold border border-green-600 bg-green-100 rounded-r-md py-[2px] pl-1 pr-2 inline-block leading-none">
                                        "{{ $matricula->seccion->nombre }}"
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-[190px] text-indigo-600">
                        <p class="text-lg font-bold leading-normal whitespace-nowrap overflow-hidden text-ellipsis">
                            {{ $matricula->alumno->nombres }}
                        </p>
                        <p class="font-semibold leading-tight whitespace-nowrap overflow-hidden text-ellipsis">
                            {{ $matricula->alumno->apellido_paterno }} {{ $matricula->alumno->apellido_materno }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-col justify-center items-center">
                    <img class="w-32"
                        src="{{ public_path('storage/qrcodes/' . $matricula->alumno->codigo_qr) . '.png' }}"
                        alt="imagen-qr">
                </div>
            </div>
        </div>
        @endforeach
    </section>
</body>
<!-- <script>
    window.addEventListener('open-new-tab', event => {
        window.open(event.detail.url, '_blank');
    });
</script> -->

</html>