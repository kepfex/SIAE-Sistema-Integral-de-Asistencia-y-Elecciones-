<?php

namespace App\Filament\Resources\AuxiliarGradoSeccionAnioResource\Pages;

use App\Filament\Resources\AuxiliarGradoSeccionAnioResource;
use App\Models\AuxiliarGradoSeccionAnio;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAuxiliarGradoSeccionAnio extends CreateRecord
{
    protected static string $resource = AuxiliarGradoSeccionAnioResource::class;
    protected static bool $canCreateAnother = false;

    public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        $data = $this->form->getState();

        $secciones = $data['secciones'];
        unset($data['secciones']);

        $anioEscolarId = session('anio_escolar_id');
        $yaRegistradas = [];

        foreach ($secciones as $seccionId) {
            $existe = \App\Models\AuxiliarGradoSeccionAnio::where([
                'user_id' => $data['user_id'],
                'grado_id' => $data['grado_id'],
                'seccion_id' => $seccionId,
                'anio_escolar_id' => $anioEscolarId,
            ])->exists();

            if ($existe) {
                $nombreSeccion = \App\Models\Seccion::find($seccionId)?->nombre;
                $yaRegistradas[] = $nombreSeccion ?? "ID $seccionId";
                continue;
            }

            AuxiliarGradoSeccionAnio::create([
                ...$data,
                'seccion_id' => $seccionId,
                'anio_escolar_id' => $anioEscolarId,
            ]);
        }

        if (count($yaRegistradas) > 0) {
            Notification::make()
                ->title('Algunas secciones ya estaban registradas')
                ->body('No se guardaron las siguientes secciones porque ya estaban asignadas: ' . implode(', ', $yaRegistradas))
                ->warning()
                ->persistent()
                ->send();
        } else {
            Notification::make()
                ->title('Asignación creada correctamente')
                ->success()
                ->send();
        }

        // Redirigir manualmente (sin pasar por CreateRecord::create)
        $this->redirect($this->getResource()::getUrl('index'));
    }

    protected function shouldSendNotification(): bool
    {
        return false;
    }
}
