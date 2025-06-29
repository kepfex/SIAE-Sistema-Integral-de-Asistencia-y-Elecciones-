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
                <div class="flex items-center justify-center bg-white h-10 w-10 rounded-full">
                    {{-- Icono de Router Conectado (Inicialmente visible, color se asigna por JS) --}}
                    <svg id="router-on-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-router w-6 h-6">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 13m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                        <path d="M17 17l0 .01" />
                        <path d="M13 17l0 .01" />
                        <path d="M15 13l0 -2" />
                        <path d="M11.75 8.75a4 4 0 0 1 6.5 0" />
                        <path d="M8.5 6.5a8 8 0 0 1 13 0" />
                    </svg>

                    {{-- Icono de Router Desconectado (Inicialmente oculto) --}}
                    <svg id="router-off-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-router-off w-6 h-6 hidden">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M17 13h2a2 2 0 0 1 2 2v2m-.588 3.417c-.362 .36 -.861 .583 -1.412 .583h-14a2 2 0 0 1 -2 -2v-4a2 2 0 0 1 2 -2h8" />
                        <path d="M17 17v.01" />
                        <path d="M13 17v.01" />
                        <path d="M12.226 8.2a4 4 0 0 1 6.024 .55" />
                        <path d="M9.445 5.407a8 8 0 0 1 12.055 1.093" />
                        <path d="M3 3l18 18" />
                    </svg>
                </div>

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
                                {{-- CÓDIGO BLADE ORIGINAL (SIN ALTERAR) --}}
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
        <div class="modal-flex-container flex justify-center items-center min-h-screen p-4 px-4 text-center sm:block sm:p-0">
            <div class="modal-bg-container fixed inset-0 bg-black/60"></div>
            <div class="modal-space-container hidden sm:inline-block sm:align-middle sm:h-screen"></div>
            <div id="modal-container" class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full">
                <div id="modal-header" class="flex items-center justify-between py-2 px-4 border-b border-slate-200 rounded-t">
                    <div id="info-modal-header" class="flex gap-2 items-center">
                    </div>
                    <button id="close-modal" type="button" class="text-slate-400 bg-transparent hover:bg-slate-200 hover:text-slate-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="modal-wrapper bg-white px-4 pt-5 pb-7">
                    <div id="info-modal-body" class="modal-wrapper-flex flex flex-col items-center sm:items-start">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <audio id="qr-beep-sound" src="{{ asset('sounds/scanner-beep.mp3') }}"></audio>
</div>


{{-- SCRIPT ORIGINAL (CON CAMBIO MENOR DE COLOR EN MODAL) --}}
@push('scripts')
<script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
<script src="{{ asset('js/reloj/reloj.js') }}"></script>
<script type="module">
    import {
        checkInternetAccess,
        updateConnectionRouterIcons
    } from "{{ asset('js/connectivity.js') }}";

    // Lógica para los iconos de conexión
    // Inicializa el estado de los iconos al cargar la página
    updateConnectionRouterIcons();

    // Actualiza el estado de los iconos cada 10 segundos
    setInterval(() => {
        updateConnectionRouterIcons();
    }, 10000); // Cada 10 segundos

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

                console.log(`QR Detectado: ${decodedText}`, decodedResult);
                // Reproduce un sonido al escanear
                document.getElementById('qr-beep-sound').play().catch(err => {
                    console.warn("No se pudo reproducir el sonido:", err);
                });

                fetch("{{ url('/api/asistencia/registrar') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
                            if (document.getElementById('info-modal-header')) {
                                const infoModalHeader = document.getElementById('info-modal-header');
                                infoModalHeader.innerHTML = `
                                         <div class="h-10 w-10 rounded-full bg-green-100 mx-auto flex-shrink-0 flex items-center justify-center sm:mx-0 sm:h-12 sm:w-12">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-check text-green-600">
                                                 <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                                 <path d="M15 19l2 2l4 -4" />
                                            </svg>
                                        </div>
                                         <h3 class="font-medium text-green-700 text-lg md:text-base">
                                            ${data.message}
                                         </h3>
                                    `;
                            }

                            if (document.getElementById('info-modal-body')) {
                                const mainContentModal = document.getElementById('info-modal-body');
                                mainContentModal.innerHTML = `
                                    <div class="modal-text mb-2 ">
                                        <p class="text-lg font-medium text-slate-800 sm:ml-4">¡Bienvenido(a)!</p>
                                     </div>
                                    <div class="text-center sm:flex gap-4  sm:text-left bg-green-50 border border-green-200 rounded-lg shadow-lg p-4 sm:w-full">
                                         <div class="flex justify-center mb-2">
                                            <img class="w-32 h-36 sm:w-36 sm:h-40 overflow-hidden rounded-lg border object-cover object-top shadow-lg"
                                                 src="${data.alumno.foto ? `/storage/${data.alumno.foto}` : '/img/usuario.svg'}"
                                                alt="Foto del alumno(a) ${data.alumno.nombres}">
                                         </div>
                                        <div class="sm:mt-1">
                                            <p class="text-xl font-bold leading-none pb-[1px] sm:text-2xl text-slate-900">${data.alumno.nombres}</p>
                                            <p class="leading-none text-2xl pb-2 sm:text-3xl text-slate-700">${data.alumno.apellido_paterno} ${data.alumno.apellido_materno}</p>
                                            <div class="font-bold w-full flex gap-2 justify-center sm:justify-start sm:mt-4 sm:text-xl text-sky-600">
                                                <p><span class="font-normal text-xs">Grado: </span>${data.alumno.grado}</p>
                                                 <p><span class="font-normal text-xs">Sección: </span>${data.alumno.seccion}</p>
                                            </div>
                                        </div>
                                     </div>
                                `;
                            }
                            // Abrir Modal
                            openModal();

                            // **Verifica la conexión a Internet antes de enviar WhatsApp**
                            checkInternetAccess().then(isConnectedToInternet => {
                                if (isConnectedToInternet) {
                                    console.warn('Hay internet, enviar mensaje de whatsapp');
                                    // fetch("/api/enviar-whatsapp", {
                                    //         method: 'POST',
                                    //         headers: {
                                    //             'Content-Type': 'application/json'
                                    //         },
                                    //         body: JSON.stringify({
                                    //             telefono: data.alumno.celular_whatsapp,
                                    //             estudiante: data.alumno.full_name,
                                    //             grado: data.alumno.grado,
                                    //             seccion: data.alumno.seccion,
                                    //             tipo: data.alumno.tipo,
                                    //             fecha: data.alumno.fecha,
                                    //             hora: data.alumno.hora
                                    //         })
                                    //     })
                                    //     .then(response => response.json())
                                    //     .then(wsp => console.log("Respuesta WhatsApp:", wsp))
                                    //     .catch(error => console.error("Error WhatsApp:", error));
                                } else {
                                    console.warn("No se pudo enviar el mensaje de WhatsApp: No hay conexión a Internet.");
                                }
                            });

                            // Cerrar Modal y actualizar tabla despues de 3 segundos
                            setTimeout(() => {
                                closeModal();
                                Livewire.dispatch('asistencia-registrada');
                            }, 3000);
                        } else {
                            if (document.getElementById('info-modal-header')) {
                                const infoModalHeader = document.getElementById('info-modal-header');
                                infoModalHeader.innerHTML = `
                                    <div class="h-10 w-10 rounded-full bg-red-100 mx-auto flex-shrink-0 flex items-center justify-center sm:mx-0 sm:h-12 sm:w-12">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-alert-triangle text-red-600">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                             <path d="M12 9v4" />
                                            <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                                               <path d="M12 16h.01" />
                                        </svg>
                                    </div>
                                      <h3 class="font-medium text-red-800 text-lg md:text-base">
                                        Mensaje de advertencia    
                                     </h3>
                                `;
                            }

                            if (document.getElementById('info-modal-body')) {
                                const infoModalBody = document.getElementById('info-modal-body');
                                infoModalBody.innerHTML = `
                                    <div class="modal-content text-center mt-3 sm:mt-0 sm:ml-4 sm:text-left mb-2">
                                        <p class="text-slate-600 text-lg">${data.message}</p>
                                     </div>
                                `;
                            }

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