{{-- registro-asistencia.blade.php --}}

@push('styles')
<link rel="stylesheet" href="{{ asset('css/html5-qrcode/html5-qrcode-custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/reloj/reloj.css') }}">
{{-- Estilos para animaciones --}}
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }

    @keyframes scaleIn {
        from {
            transform: scale(0.95);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes scaleOut {
        from {
            transform: scale(1);
            opacity: 1;
        }

        to {
            transform: scale(0.95);
            opacity: 0;
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out forwards;
    }

    .animate-fadeOut {
        animation: fadeOut 0.3s ease-in forwards;
    }

    .animate-scaleIn {
        animation: scaleIn 0.3s ease-out forwards;
    }

    .animate-scaleOut {
        animation: scaleOut 0.3s ease-in forwards;
    }
</style>
@endpush

<div class="bg-slate-100 h-screen font-sans">
    {{-- ENCABEZADO --}}
    <header class="w-full bg-blue-500 shadow-lg">
        <nav class="w-[95%] md:w-[90%] mx-auto py-3 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img class="w-10" src="{{ asset('img/insignia.png') }}" alt="Insignia">
                <div class="uppercase font-bold text-white">
                    <span class="block text-sm tracking-wide">C.N. Emblemático</span>
                    <span class="text-xs font-light opacity-95">"Aurelio Cárdenas Pachas"</span>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="text-white text-center">
                    <span class="block text-xs leading-none pb-1 uppercase font-light opacity-90">Año Escolar</span>
                    <span class="font-semibold leading-none text-2xl tracking-wider">{{ $ultimoAnioEscolar ? $ultimoAnioEscolar->nombre : '---' }}</span>
                </div>
                {{-- <div class="flex items-center justify-center bg-white h-10 w-10 rounded-full"> --}}
                    {{-- Icono de Router Conectado (Inicialmente visible, color se asigna por JS) --}}
                    {{-- <svg id="router-on-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-router w-6 h-6">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 13m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                        <path d="M17 17l0 .01" />
                        <path d="M13 17l0 .01" />
                        <path d="M15 13l0 -2" />
                        <path d="M11.75 8.75a4 4 0 0 1 6.5 0" />
                        <path d="M8.5 6.5a8 8 0 0 1 13 0" />
                    </svg> --}}

                    {{-- Icono de Router Desconectado (Inicialmente oculto) --}}
                    {{-- <svg id="router-off-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-router-off w-6 h-6 hidden">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M17 13h2a2 2 0 0 1 2 2v2m-.588 3.417c-.362 .36 -.861 .583 -1.412 .583h-14a2 2 0 0 1 -2 -2v-4a2 2 0 0 1 2 -2h8" />
                        <path d="M17 17v.01" />
                        <path d="M13 17v.01" />
                        <path d="M12.226 8.2a4 4 0 0 1 6.024 .55" />
                        <path d="M9.445 5.407a8 8 0 0 1 12.055 1.093" />
                        <path d="M3 3l18 18" />
                    </svg> --}}
                {{-- </div> --}}

            </div>
        </nav>
    </header>

    <main class="w-[95%] md:w-[90%] mx-auto">
        <div class="mb-6 min-[900px]:mb-8 md:flex md:justify-between md:items-center">
            <h2 class="text-center min-[900px]:text-left text-4xl font-bold pt-10 text-slate-700">Control de Asistencia</h2>
            {{-- WIDGET DE RELOJ --}}
            <div id="widget-reloj" class="flex flex-col items-center mt-4" wire:ignore>
                <div id="fecha" class="flex gap-2 text-slate-500 font-medium">
                    <p id="diaSemana"></p>
                    <p id="dia"></p>
                    <p>de</p>
                    <p id="mes"></p>
                    <p>del</p>
                    <p id="year"></p>
                </div>
                <div class="clock" aria-label="00:00:00 AM">
                    <div class="clock__block clock__block--delay2" aria-hidden="true" data-time-group>
                        <div class="clock__digit-group clock__hora">
                            <div class="clock__digits" data-time="a">00</div>
                            <div class="clock__digits" data-time="b">00</div>
                        </div>
                    </div>
                    <div class="clock__colon"></div>
                    <div class="clock__block clock__block--delay1" aria-hidden="true" data-time-group>
                        <div class="clock__digit-group clock__minutos">
                            <div class="clock__digits" data-time="a">00</div>
                            <div class="clock__digits" data-time="b">00</div>
                        </div>
                    </div>
                    <div class="clock__colon"></div>
                    <div class="clock__block" aria-hidden="true" data-time-group>
                        <div class="clock__digit-group">
                            <div class="clock__digits" data-time="a">00</div>
                            <div class="clock__digits" data-time="b">00</div>
                        </div>
                    </div>
                    <div class="clock__block clock__block--delay2 clock__block--small" aria-hidden="true" data-time-group>
                        <div class="clock__digit-group">
                            <div class="clock__digits" data-time="a">PM</div>
                            <div class="clock__digits" data-time="b">AM</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid min-[900px]:grid-cols-5 min-[900px]:gap-6">
            {{-- COLUMNA DEL ESCÁNER QR --}}
            <div class="grid min-[900px]:col-span-2 mb-5 min-[900px]:mb-0">
                <div class="w-full max-w-[410px] mx-auto min-[900px]:m-0 p-4 bg-white rounded-xl shadow-md border border-slate-200" wire:ignore>
                    <div id="qr-reader" class="rounded-lg overflow-hidden"></div>
                    <p class="text-center text-sm text-slate-500 mt-4">Coloque el código QR del estudiante frente a la cámara.</p>
                </div>
            </div>

            {{-- COLUMNA DE LA TABLA DE ASISTENCIAS --}}
            @if ($asistenciasDelDia->isEmpty())
            <div class="my-6 min-[900px]:my-0 grid min-[900px]:col-span-3 justify-center items-center bg-white rounded-xl shadow-md border border-slate-200 p-6">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-base font-semibold text-slate-800">No hay registros</h3>
                    <p class="mt-1 text-sm text-slate-500">Aún no hay asistencias para el día de hoy.</p>
                </div>
            </div>
            @else
            <div class="my-6 min-[900px]:my-0 grid min-[900px]:col-span-3">
                <div class="flex gap-4 items-center mb-4">
                    <div class="flex-grow flex gap-2 items-center">
                        <label for="buscar" class="font-medium text-slate-700">Buscar:</label>
                        <input id="buscar" type="text" placeholder="Escriba un nombre..." class="w-full max-w-xs py-2 px-3 rounded-lg border-slate-300 shadow-sm focus:border-sky-500 focus:ring-2 focus:ring-sky-500/50 transition">
                    </div>
                </div>
                <div class="relative shadow-md sm:rounded-lg overflow-hidden border border-slate-200">
                    <div class="max-h-[410px] overflow-auto">
                        <table class="w-full text-sm text-left text-slate-600">
                            <thead class="text-xs text-white uppercase bg-sky-600">
                                <tr>
                                    <th scope="col" class="px-6 py-3 sticky top-0 bg-sky-600">Nombres</th>
                                    <th scope="col" class="px-6 py-3 sticky top-0 bg-sky-600">Apellidos</th>
                                    <th scope="col" class="px-6 py-3 sticky top-0 bg-sky-600">Grado</th>
                                    <th scope="col" class="px-6 py-3 sticky top-0 bg-sky-600">Sección</th>
                                    <th scope="col" class="px-6 py-3 sticky top-0 bg-sky-600">Entrada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asistenciasDelDia as $asistencia)
                                <tr class="bg-white border-b border-slate-200 hover:bg-sky-50 transition-colors duration-200">
                                    <td class="px-6 py-3 font-medium text-slate-800">
                                        {{ $asistencia->matricula->alumno->nombres }}
                                    </td>
                                    <td class="px-6 py-3">
                                        {{ $asistencia->matricula->alumno->apellido_paterno }} {{ $asistencia->matricula->alumno->apellido_materno }}
                                    </td>
                                    <td class="px-6 py-3">
                                        {{ $asistencia->grado->nombre }}
                                    </td>
                                    <td class="px-6 py-3">
                                        {{ $asistencia->seccion->nombre }}
                                    </td>
                                    <td class="px-6 py-3 font-medium text-slate-800 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($asistencia->hora)->format('h:i A') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>

    <div id="modal-component-container" class="hidden fixed inset-0 z-50">
        <div class="modal-flex-container flex items-center justify-center min-h-screen p-4 text-center">
            
            <div class="modal-bg-container fixed inset-0 bg-black/50 backdrop-blur-sm"></div>

            <div id="modal-container" 
                class="relative inline-block bg-slate-50 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 w-full max-w-md lg:max-w-4xl">
                
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button id="close-modal" type="button" class="bg-slate-100 rounded-md p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        <span class="sr-only">Close modal</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="modal-wrapper">
                    <div id="info-modal-header"></div> <div id="info-modal-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <audio id="qr-beep-sound" src="{{ asset('sounds/scanner-beep.mp3') }}"></audio>
</div>

@push('scripts')
<script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
<script src="{{ asset('js/reloj/reloj.js') }}"></script>
<script type="module">
    // import {
    //     checkInternetAccess,
    //     updateConnectionRouterIcons
    // } from "{{ asset('js/connectivity.js') }}";

    // // Lógica para los iconos de conexión
    // // Inicializa el estado de los iconos al cargar la página
    // updateConnectionRouterIcons();

    // // Actualiza el estado de los iconos cada 10 segundos
    // setInterval(() => {
    //     updateConnectionRouterIcons();
    // }, 10000); // Cada 10 segundos

    // Espera a que Livewire termine de inicializarse
    document.addEventListener('livewire:initialized', function() {


        // --- Lógica del escáner QR y el Modal ---
        const closeButton = document.querySelector("#close-modal");
        const modalContainer = document.querySelector(
            "#modal-component-container"
        );
        const modal = document.querySelector("#modal-container");

        closeButton.addEventListener("click", () => {
            closeModal();
        });

        function openModal() {
            showAndHide(modalContainer, ["block", "animate-fadeIn"], ["hidden", "animate-fadeOut"]);
            showAndHide(modal, ["animate-scaleIn"], ["animate-scaleOut"]);
        }

        function closeModal() {
            showAndHide(modalContainer, ["animate-fadeOut"], ["animate-fadeIn"]);
            showAndHide(modal, ["animate-scaleOut"], ["animate-scaleIn"]);

            setTimeout(() => {
                showAndHide(modalContainer, ["hidden"], ["block"]);
            }, 300);
        }

        function showAndHide(element, classessToAdd, classessToRemove) {
            element.classList.remove(...classessToRemove);
            element.classList.add(...classessToAdd);
        }

        let lastResult = '';
        let countResults = 0;
        let scannerActive = true;

        async function onScanSuccess(decodedText, decodedResult) {
            if (!scannerActive) return;
            if (decodedText !== lastResult) {
                lastResult = decodedText;
                countResults++;

                // console.log(`QR Detectado: ${decodedText}`, decodedResult);

                // Reproduce un sonido al escanear
                document.getElementById('qr-beep-sound').play().catch(err => {
                    console.warn("No se pudo reproducir el sonido:", err);
                });

                fetch("{{ url('/api/asistencia/registrar') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            qrAlumno: decodedText
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Respuesta del servidor: ', data);
                        if (data.success === true) {
                            // Creando el contenido del modal
                            const mainContentModal = document.getElementById('info-modal-body');
                            mainContentModal.innerHTML = `
                            <div class="lg:grid lg:grid-cols-3">
            
                                <div class="relative lg:col-span-1 flex items-center justify-center p-8 bg-gradient-to-br from-blue-500 to-blue-700">
                                    <div class="text-center">
                                        <img class="w-40 h-42 lg:w-48 lg:h-50 rounded-lg object-cover object-top shadow-2xl border-4 border-white/50 mx-auto"
                                            src="${data.alumno.foto ? `/storage/${data.alumno.foto}` : '/img/usuario.svg'}"
                                            alt="Foto de ${data.alumno.nombres}">
                                        <p class="mt-4 text-base font-semibold text-white">${data.message}</p>
                                    </div>
                                </div>

                                <div class="lg:col-span-2 p-8">
                                    <h2 class="text-base font-semibold leading-7 text-blue-600">¡Bienvenido(a) de vuelta!</h2>
                                    
                                    <p class="mt-1 text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">${data.alumno.nombres}</p>
                                    <p class="mt-1 text-3xl tracking-tight text-slate-600 sm:text-4xl">${data.alumno.apellido_paterno} ${data.alumno.apellido_materno}</p>
                                    
                                    <div class="mt-8 border-t border-slate-200 pt-8">
                                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8">
                                            
                                            <div class="sm:col-span-1">
                                                <dt class="text-sm font-medium text-slate-500">Grado y Sección</dt>
                                                <dd class="mt-1">
                                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-3 py-1 text-lg font-bold text-blue-700 ring-1 ring-inset ring-blue-600/20">${data.alumno.grado} "${data.alumno.seccion}"</span>
                                                </dd>
                                            </div>

                                            <div class="sm:col-span-1">
                                                <dt class="text-sm font-medium text-slate-500">Hora de Registro</dt>
                                                <dd class="mt-1 text-lg font-semibold text-slate-800">${new Date().toLocaleTimeString('es-PE', { hour: '2-digit', minute: '2-digit', second: '2-digit' })}</dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            `;
                            // Abrir Modal
                            openModal();

                            // Cerrar Modal y actualizar tabla despues de 3 segundos
                            setTimeout(() => {
                                closeModal();
                                Livewire.dispatch('asistencia-registrada');
                            }, 3000);
                        } else {
                            const infoModalBody = document.getElementById('info-modal-body');
                            infoModalBody.innerHTML = `
                                <div class="p-8 sm:p-12 text-center">
                                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-red-100">
                                        <svg class="h-12 w-12 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
                                        </svg>
                                    </div>

                                    <div class="mt-5">
                                        <h3 class="text-xl font-bold text-slate-900">
                                            Mensaje de Advertencia
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-2xl text-slate-600">
                                                ${data.message}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            `;
                            openModal();
                            setTimeout(() => {
                                closeModal();
                            }, 3000);
                        }
                        scannerActive = true;
                    })
                    .catch(error => {
                        console.error('Error en el envío: ', error);
                        scannerActive = true;
                    });
                scannerActive = false;
                setTimeout(() => {
                    scannerActive = true;
                }, 3000);
            }
        }

        const html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", {
            fps: 10,
            qrbox: 250
        });
        html5QrcodeScanner.render(onScanSuccess);
    });
</script>
@endpush