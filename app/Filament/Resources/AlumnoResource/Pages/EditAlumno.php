<?php

namespace App\Filament\Resources\AlumnoResource\Pages;

use App\Filament\Resources\AlumnoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class EditAlumno extends EditRecord
{
    protected static string $resource = AlumnoResource::class;
    public ?string $desde = null;
    public ?string $urlAnterior = null;

    public function mount($record): void
    {
        parent::mount($record);

        // Solo guardamos la anterior si viene desde matriculas
        if (request()->query('desde') === 'matriculas') {
            $this->desde = 'matriculas';
            $this->urlAnterior = url()->previous(); // Captura la URL con filtros
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        if ($this->desde === 'matriculas' && $this->urlAnterior) {
            // Valida que es una URL interna segura
            if (Str::startsWith($this->urlAnterior, config('app.url'))) {
                return $this->urlAnterior;
            }
        }

        return AlumnoResource::getUrl('index');
    }

    
}
