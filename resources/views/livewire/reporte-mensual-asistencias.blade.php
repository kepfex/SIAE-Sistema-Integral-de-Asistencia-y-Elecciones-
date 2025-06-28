<div>
    <div class="space-y-4">
        <button wire:click="generarReporte" class="bg-blue-500 text-white px-4 py-2 rounded">
    Generar Reporte
</button>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            {{-- Filtro Grado --}}
            <div>
                <label for="gradoId" class="block text-sm font-medium text-gray-700">Grado</label>
                <select id="gradoId" wire:model="gradoId" class="form-select w-full mt-1">
                    <option value="">Seleccione</option>
                    @foreach($grados as $grado)
                        <option value="{{ $grado->id }}">{{ $grado->nombre}}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filtro Sección --}}
            <div>
                <label for="seccionId" class="block text-sm font-medium text-gray-700">Sección</label>
                <select id="seccionId" wire:model="seccionId" class="form-select w-full mt-1">
                    <option value="">Seleccione</option>
                    @foreach(\App\Models\Seccion::all() as $seccion)
                        <option value="{{ $seccion->id }}">{{ $seccion->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filtro Mes --}}
            <div>
                <label for="mes" class="block text-sm font-medium text-gray-700">Mes</label>
                <select id="mes" wire:model="mes" class="form-select w-full mt-1">
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

        @if(!empty($tabla))
        <div class="overflow-auto">
            <table class="table-auto border w-full text-sm">
                <thead class="bg-gray-200 text-gray-900">
                    <tr>
                        <th class="px-2 py-1 border">#</th>
                        <th class="px-2 py-1 border">Alumno</th>
                        @foreach($diasDelMes as $dia)
                            <th class="px-1 py-1 border text-center text-xs">
                                {{ $dia['numero'] }}<br>{{ $dia['nombre_corto'] }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($tabla as $index => $fila)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-2 py-1 text-center">{{ $index + 1 }}</td>
                            <td class="border px-2 py-1">{{ $fila['alumno'] }}</td>
                            @foreach($diasDelMes as $dia)
                                @php
                                    $estado = $fila['asistencias'][$dia['numero']] ?? '';
                                    $color = match($estado) {
                                        'A' => 'bg-green-100 text-green-800',
                                        'F' => 'bg-red-100 text-red-800',
                                        'T' => 'bg-yellow-100 text-yellow-800',
                                        default => '',
                                    };
                                @endphp
                                <td class="border px-1 py-1 text-center {{ $color }}">{{ $estado }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
