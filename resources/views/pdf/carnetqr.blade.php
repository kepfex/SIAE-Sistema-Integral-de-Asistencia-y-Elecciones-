<!DOCTYPE html>
<html lang="eS">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Carnets QR - Estudiantes</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

</head>
<body>
    <section class="flex flex-wrap justify-center  gap-x-[2px] gap-y-[4px]">
        @foreach ($matriculas as $index => $matricula)
        <div class="w-[375px] h-[225px] border border-blue-600 rounded-md overflow-hidden flex flex-col relative">
            <div class="absolute bottom-1 right-2 flex gap-1 items-end text-sm text-gray-500">
                
                <svg  xmlns="http://www.w3.org/2000/svg"  width="18"  height="18"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-whatsapp"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" />
            </svg>
                <span class="font-medium leading-none">{{ $matricula->alumno->celular ? $matricula->alumno->celular : '---' }}</span>
            </div>
            <div class="bg-blue-600 text-center relative py-2">
                <p class="text-white font-medium text-sm leading-none">C.N.E. "Aurelio Cárdenas Pachas"</p>
                <img class="w-10 absolute left-2 top-2" src="{{ public_path('img/insignia.png') }}" alt="insignia ACP">
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
                        <img class="w-28 h-32 overflow-hidden rounded-xl object-cover object-top border-2 border-green-600"
                            src="{{ $rutaImagen }}" alt="foto alumno(a)">
                        <div class="relative text-gray-800">
                            <div class="absolute -left-[1px]">
                                <div class="mt-2">
                                    <span class="text-sm ml-1">Grado:</span>
                                    <p class="text-base py-[2px] px-1 font-semibold border border-green-600 bg-green-100 rounded-r-md leading-none">
                                        {{ $matricula->grado->nombre }}
                                    </p>
                                </div>
                                <div class="mt-1">
                                    <span class="text-sm ml-1 block">Sección:</span>
                                    <p class="text-base font-semibold border border-green-600 bg-green-100 rounded-r-md py-[2px] pl-1 pr-2 inline-block leading-none">
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
                    <img class="w-[140px]"
                        src="{{ public_path('storage/qrcodes/' . $matricula->alumno->codigo_qr) . '.png' }}"
                        alt="imagen-qr">
                </div>
            </div>
        </div>
            {{-- Condición para insertar un salto de página después de cada 4 filas --}}
            @if (($index + 1) % 8 === 0)
                <div style="page-break-after: always;"></div>
            @endif
        @endforeach
    </section>
</body>
<!-- <script>
    window.addEventListener('open-new-tab', event => {
        window.open(event.detail.url, '_blank');
    });
</script> -->

</html>