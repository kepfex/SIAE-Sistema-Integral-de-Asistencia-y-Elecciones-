<x-filament-widgets::widget>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <x-filament::section>
        <form method="POST" action="{{ route('anio-escolar.seleccionar') }}">
            @csrf
            <div class="flex gap-4 items-center justify-center">
                <label class="text-sm font-medium text-gray-600 dark:text-white whitespace-nowrap" for="">Año Escolar:</label>
                <select name="anio_escolar_id" onchange="this.form.submit()" class="block w-full px-4 py-2.5 text-base text-gray-800 dark:text-white bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition duration-150 ease-in-out" >
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