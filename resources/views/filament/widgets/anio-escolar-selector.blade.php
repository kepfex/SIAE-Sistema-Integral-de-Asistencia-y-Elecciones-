<x-filament-widgets::widget>
    <x-filament::section>
        <form method="POST" action="{{ route('anio-escolar.seleccionar') }}">
            @csrf
            <div class="flex gap-4 items-center justify-center">
                <label class="text-sm text-gray-500" for="">Año Escolar:</label>
                <select name="anio_escolar_id" onchange="this.form.submit()" class="text-base rounded px-2 py-1 border border-blue-600 flex-1" >
                @foreach ($this->getAnniosEscolares() as $anio)
                <option value="{{ $anio->id }}" {{ $anio->id == $this->getSelectedAnioEscolar() ? 'selected' : '' }}>
                    {{ $anio->nombre }}
                </option>
                @endforeach
            </select>
            </div>
        </form>
    </x-filament::section>
</x-filament-widgets::widget>