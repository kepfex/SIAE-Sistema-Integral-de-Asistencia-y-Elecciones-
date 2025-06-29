{{-- Este es el componente de Blade completo y rediseñado con la columna fija --}}
<div class="p-4 sm:p-6 bg-gray-50 dark:bg-gray-900/50 rounded-2xl shadow-lg">
    <div class="space-y-6">
        
        <!-- Panel de Control: Filtros y Acciones -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Sección de Filtros -->
            <div class="lg:col-span-2 p-4 border rounded-xl bg-white dark:bg-gray-800 dark:border-gray-700 shadow-sm">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Filtros de Búsqueda</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Filtro Grado --}}
                    <div>
                        <label for="gradoId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grado</label>
                        <select id="gradoId" wire:model.live="gradoId" class="block w-full px-3 py-2 text-gray-800 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition">
                            <option value="">Seleccione</option>
                            @foreach($grados as $grado)
                                <option value="{{ $grado->id }}">{{ $grado->nombre}}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtro Sección --}}
                    <div>
                        <label for="seccionId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sección</label>
                        <select id="seccionId" wire:model.live="seccionId" class="block w-full px-3 py-2 text-gray-800 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition">
                            <option value="">Seleccione</option>
                            @foreach($secciones as $seccion)
                                <option value="{{ $seccion->id }}">{{ $seccion->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtro Mes --}}
                    <div>
                        <label for="mes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mes</label>
                        <select id="mes" wire:model.live="mes" class="block w-full px-3 py-2 text-gray-800 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition">
                            <option value="">Seleccione</option>
                            @foreach([
                                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                            ] as $numero => $nombre)
                                <option value="{{ $numero }}">{{ $nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Sección de Acciones -->
            <div class="p-4 border rounded-xl bg-white dark:bg-gray-800 dark:border-gray-700 shadow-sm flex flex-col justify-center space-y-3">
                <button wire:click="generarReporte" class="inline-flex items-center justify-center w-full px-4 py-2.5 font-semibold text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" /></svg>
                    Generar Reporte
                </button>
                
                @if(!empty($tabla))
                    <button wire:click="descargarPdf" class="inline-flex items-center justify-center w-full px-4 py-2.5 font-semibold text-white bg-green-600 rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        Descargar PDF
                    </button>
                @else
                     <div class="text-center text-sm text-gray-500 dark:text-gray-400 pt-2">
                         El botón de descarga aparecerá aquí.
                     </div>
                @endif
            </div>
        </div>


        {{-- Tabla de Resultados --}}
        @if(!empty($tabla))
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700/50">
                    <tr>
                        <th scope="col" class="sticky left-0 z-20 px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-100 dark:bg-gray-700/50">#</th>
                        
                        {{-- ENCABEZADO FIJO --}}
                        <th scope="col" class="sticky left-10 z-20 px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-100 dark:bg-gray-700/50">Nombres y Apellidos</th>
                        
                        @foreach($diasDelMes as $dia)
                        <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <span>{{ $dia['numero'] }}</span>
                            <span class="block">{{ $dia['nombre_corto'] }}</span>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($tabla as $index => $fila)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150">
                        <td class="sticky left-0 z-10 px-3 py-2 text-center text-sm text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800">{{ $index + 1 }}</td>

                        {{-- CELDA DE DATOS FIJA --}}
                        <td class="sticky left-10 z-10 px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800">{{ $fila['alumno'] }}</td>
                        
                        @foreach($diasDelMes as $dia)
                            @php
                                $estado = $fila['asistencias'][$dia['numero']] ?? '';
                                $colorClasses = match($estado) {
                                    'A' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                                    'F' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
                                    'T' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300',
                                    default => 'bg-gray-50 dark:bg-gray-700/30',
                                };
                            @endphp
                            <td class="px-2 py-2 text-center text-sm font-mono font-bold {{ $colorClasses }}">{{ $estado }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="text-center py-12 px-4 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl">
                 <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                 <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">No hay reporte generado</h3>
                 <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Por favor, complete los filtros y haga clic en "Generar Reporte" para ver los resultados.</p>
            </div>
        @endif
    </div>
</div>
