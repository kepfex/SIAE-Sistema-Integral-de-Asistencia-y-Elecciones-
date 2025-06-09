<?php

namespace App\Filament\Widgets;

use App\Models\AnioEscolar;
use Filament\Widgets\Widget;

class AnioEscolarSelector extends Widget
{
    protected static string $view = 'filament.widgets.anio-escolar-selector';

    protected static ?int $sort = -2; // Aparece primero
    protected static bool $isLazy = false;

    public static function canView(): bool
    {
        return true;
    }

    public function mount()
    {
        if (!session()->has('anio_escolar_id')) {
            $anio = AnioEscolar::orderByDesc('nombre')->first();
            if ($anio) {
                session(['anio_escolar_id' => $anio->id]);
            }
        }
    }

    public function getAnniosEscolares()
    {
        return AnioEscolar::orderByDesc('nombre')->get();
    }

    public function getSelectedAnioEscolar()
    {
        return session('anio_escolar_id');
    }
}
