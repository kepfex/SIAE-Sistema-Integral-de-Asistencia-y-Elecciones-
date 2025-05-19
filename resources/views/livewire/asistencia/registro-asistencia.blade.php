{{-- registro-asistencia.blade.php --}}

@push('styles')
<link rel="stylesheet" href="{{ asset('css/html5-qrcode/html5-qrcode-custom.css') }} ">
<link rel="stylesheet" href="{{ asset('css/reloj/reloj.css') }}">
@endpush

<div class=" bg-gray-100 h-screen">
    <header class="w-full bg-blue-600 border-b border-b-blue-700">
        <nav class="w-[95%] md:w-[90%] mx-auto py-3 flex justify-between">
            <div class="flex items-center gap-2">
                <img class="w-12" src="{{ asset('img/insignia.png') }}" alt="Insignia">
                <div class="uppercase font-bold text-white">
                    <span class="block text-sm">I.E. Emblemático</span>
                    <span class="text-xs">"Aurelio Cárdenas Pachas"</span>
                </div>
            </div>
            <div class="flex gap-4">
                <div>
                    <button id="open-modal">Abrir</button>
                </div>
                <div class="text-white text-center">
                    <span class="block text-xs leading-none pb-1">Año Escolar</span>
                    <span class="font-semibold leading-none text-[28px]">{{ $ultimoAnioEscolar ? $ultimoAnioEscolar->nombre : '---' }}</span>
                </div>
            </div>
        </nav>
    </header>

    <div class="w-[95%] md:w-[90%] mx-auto">
        <div class="mb-6 min-[900px]:mb-0 md:flex md:justify-between md:items-center">
            <h2 class="text-center min-[900px]:text-left text-4xl font-bold py-10 text-gray-800">Control de Asistencia</h2>
            <div id="widget-reloj" class="flex flex-col items-center" wire:ignore>
                <div id="fecha" class="flex gap-2 text-gray-700 font-medium ">
                    <p id="diaSemana" class=""></p>
                    <p id="dia" class=""></p>
                    <p>de </p>
                    <p id="mes" class=""></p>
                    <p>del </p>
                    <p id="year" class=""></p>
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

        <div class="grid min-[900px]:grid-cols-5 min-[900px]:gap-3">
            <div class="grid min-[900px]:col-span-2 mb-5 min-[900px]:mb-0">
                <div class="w-full max-w-[410px] mx-auto min-[900px]:m-0" wire:ignore>
                    <div id="qr-reader" class="border shadow-md rounded-lg overflow-hidden  bg-white"></div>
                    <!-- <div id="qr-reader-results" class="mt-4 text-sm text-gray-700 space-y-1"></div> -->
                </div>
            </div>
            @if ($asistenciasDelDia->isEmpty())
            <div class="my-6 min-[900px]:my-0 grid min-[900px]:col-span-3 justify-center items-center">
                <p class="text-white text-center font-medium bg-amber-400 rounded-md py-2 px-4">
                    Aún no hay asistencias registradas para hoy.
                </p>
            </div>
            @else
            <div class="my-6 min-[900px]:my-0 grid min-[900px]:col-span-3"> <!-- este div debe de aparecer de forma condicional cuando se registra al menos una asistencia y se debe ir listando las siguientes del mas actual -->
                <div class="flex gap-4 items-center mb-4">
                    <div class="flex gap-2 items-center">
                        <label for="buscar" class="font-medium">Buscar:</label>
                        <input id="buscar" type="text" class="py-1 px-3 rounded-md border ">
                    </div>
                    <div>
                        <span class="text-green-700">Registros del día.</span>
                    </div>
                </div>
                <div class="relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="max-h-[354px] overflow-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                            <thead class="text-xs text-gray-50 uppercase">
                                <tr>
                                    <th scope="col" class="px-6 py-3 sticky top-0 bg-green-400">
                                        Nombres
                                    </th>
                                    <th scope="col" class="px-6 py-3 sticky top-0 bg-green-400">
                                        Apellidos
                                    </th>
                                    <th scope="col" class="px-6 py-3 sticky top-0 bg-green-400">
                                        Grado
                                    </th>
                                    <th scope="col" class="px-6 py-3 sticky top-0 bg-green-400">
                                        Sección
                                    </th>
                                    <th scope="col" class="px-6 py-3 sticky top-0 bg-green-400">
                                        Entrada
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asistenciasDelDia as $asistencia)
                                <tr class="bg-white border-b  border-gray-300">
                                    <td class="px-4 py-3">
                                        {{ $asistencia->matricula->alumno->nombres }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $asistencia->matricula->alumno->apellido_paterno }} {{ $asistencia->matricula->alumno->apellido_materno }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $asistencia->grado->nombre }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $asistencia->seccion->nombre }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
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
    </div>

    <div id="modal-component-container" class="hidden fixed inset-0">
        <div class="modal-flex-container
            flex justify-center items-center min-h-screen p-4 px-4 text-center sm:block sm:p-0        
        ">
            <div class="modal-bg-container fixed inset-0 bg-gray-700 bg-opacity-75"></div>

            <div class="modal-space-container hidden sm:inline-block sm:align-middle sm:h-screen"></div>

            <div id="modal-container" class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full">
                <!-- Encabezado -->
                <div id="modal-header" class="flex items-center justify-between py-2 px-4 border-b rounded-t border-gray-300">
                    <!-- Info Header -->
                    <div id="info-modal-header" class="flex gap-2 items-center">
                        <!-- Aquí Contenido dinámico -->
                    </div>
                    <button id="close-modal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Contenido principal -->
                <div class="modal-wrapper bg-white px-4 pt-5 pb-7">
                    <div id="info-modal-body" class="modal-wrapper-flex flex flex-col items-center sm:items-start">
                        <!-- Aquí Contenido dinámico -->
                    </div>
                </div>
                <!-- Footer -->
                <!-- <div class="w-full mb-7 flex justify-center">
                    <div class="inline-flex gap-6 items-center py-2 px-4 shadow-lg border border-gray-200 rounded-xl">
                        <div class="flex gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-bowl-spoon text-amber-600">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M20 10a2 2 0 0 1 2 2v.5c0 1.694 -2.247 5.49 -3.983 6.983l-.017 .013v.504a2 2 0 0 1 -1.85 1.995l-.15 .005h-8a2 2 0 0 1 -2 -2v-.496l-.065 -.053c-1.76 -1.496 -3.794 -4.965 -3.928 -6.77l-.007 -.181v-.5a2 2 0 0 1 2 -2z" />
                                <path d="M8 2c1.71 0 3.237 .787 3.785 2h8.215a1 1 0 0 1 0 2l-8.216 .001c-.548 1.213 -2.074 1.999 -3.784 1.999c-2.144 0 -4 -1.237 -4 -3s1.856 -3 4 -3" />
                            </svg>
                            <span class="text-sm text-amber-500">¿Permiso para almozar?</span>
                        </div>
                        <div class="flex gap-2 items-center bg-green-500 text-white py-1 px-4 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline icon-tabler-tools-kitchen-2 w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M19 3v12h-5c-.023 -3.681 .184 -7.406 5 -12zm0 12v6h-1v-3m-10 -14v17m-3 -17v3a3 3 0 1 0 6 0v-3" />
                            </svg>
                            <span class="sm:text-xl font-medium">Si</span>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="modal-actions bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button class="w-full inline-flex justify-center rounded-md border border-transparent shadow-md px-4 py-2 bg-red-700 font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-200 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Eliminar</button>
                    <button id="close-modal" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-md px-4 py-2 bg-white font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-200 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm mt-3">Cancelar</button>
                </div> -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
<script src="{{ asset('js/reloj/reloj.js') }}"></script>
@endpush
<!-- Html5QrcodeScanner Script -->
<script>
    // Espera a que Livewire termine de inicializarse
    document.addEventListener('livewire:initialized', function() {

        const closeButton = document.querySelector("#close-modal");
        const openButton = document.querySelector("#open-modal");
        const modalContainer = document.querySelector(
            "#modal-component-container"
        );
        const modal = document.querySelector("#modal-container");

        openButton.addEventListener("click", () => {
            openModal();
        });

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
            }, 500);
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
                        // console.log('Respuesta del servidor: ', data);
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
                                        <h3 class="font-medium text-green-600 text-lg md:text-base">
                                            ${data.message}
                                        </h3>
                                    `;
                                // const modalHeader = document.getElementById('modal-header');
                                // modalHeader.prepend(infoModalHeader);
                            }
                            
                            const mainContentModal = document.querySelector('.modal-wrapper-flex');
                            mainContentModal.innerHTML = `
                                <div class="modal-text mb-2 ">
                                    <p class="text-lg font-medium text-gray-900 sm:ml-4">Bienvenido(a)</p>
                                </div>
                                <div class="text-center sm:flex gap-4  sm:text-left bg-green-50 border border-green-200 rounded-lg shadow-lg p-4 sm:w-full">
                                    <div class="flex justify-center mb-2">
                                        <img class="w-32 h-36 sm:w-36 sm:h-40 overflow-hidden rounded-lg border object-cover object-top shadow-lg"
                                            src="${data.alumno.foto ? `/storage/${data.alumno.foto}` : '/img/usuario.svg'}"
                                            alt="Foto del alumno(a) ${data.alumno.nombres}">
                                    </div>
                                    <div class="sm:mt-1">
                                        <p class="text-xl font-bold leading-none pb-[1px] sm:text-2xl text-slate-900">${data.alumno.nombres}</p>
                                        <p class="leading-none text-2xl pb-2 sm:text-3xl text-slate-800">${data.alumno.apellido_paterno} ${data.alumno.apellido_materno}</p>
                                        <div class="font-bold w-full flex gap-2 justify-center sm:justify-start sm:mt-4 sm:text-xl text-indigo-500">
                                            <p><span class="font-normal text-xs">Grado: </span>${data.alumno.grado}</p>
                                            <p><span class="font-normal text-xs">Sección: </span>${data.alumno.seccion}</p>
                                        </div>
                                    </div>
                                </div>
                            `;

                            openModal();

                            Livewire.dispatch('asistencia-registrada');

                            // Ocultar después de 5 segundos
                            setTimeout(() => {
                                closeModal();
                                // noti.remove();
                            }, 5000);

                        } else {
                            // console.warn('Error:', data);

                            // Creando el contenido del modal
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
                                    <h3 class="font-medium text-gray-950 text-sm md:text-lg">
                                        Mensaje de advertencia    
                                    </h3>
                                `;
                            }

                            if (document.getElementById('info-modal-body')) {
                                const infoModalBody = document.getElementById('info-modal-body');
                                infoModalBody.innerHTML = `
                                    <div class="modal-content text-center mt-3 sm:mt-0 sm:ml-12 sm:text-left mb-2">
                                        <p class="text-gray-600 text-lg">${data.message}</p>
                                    </div>
                                `;
                            }

                            openModal();
                            // Ocultar después de 5 segundos
                            setTimeout(() => {
                                closeModal();
                                // noti.remove();
                            }, 5000);
                        }

                        scannerActive = true;
                    })
                    .catch(error => {
                        console.error('Error en el envío: ', error);
                        scannerActive = true;
                    });

                // Deshabilitar temporalmente el escáner para evitar múltiples lecturas
                scannerActive = false;
                setTimeout(() => {
                    scannerActive = true;
                }, 3000); // Reactivar después de 3 segundos
            }
        }

        // Inicializar escáner QR
        const html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", {
                fps: 10,
                qrbox: 250
            }
        );

        html5QrcodeScanner.render(onScanSuccess);

        // function waitFor(ms) {
        //     return new Promise(resolve => setTimeout(resolve, ms));
        // }

        // Advertencia al intentar cerrar la pestaña
        // window.addEventListener('beforeunload', function(e) {
        //     e.preventDefault();
        //     e.returnValue = '';
        // });
    });
</script>