<?php

namespace App\Filament\Resources\AuxiliarGradoSeccionAnioResource\Pages;

use App\Filament\Resources\AuxiliarGradoSeccionAnioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuxiliarGradoSeccionAnio extends EditRecord
{
    protected static string $resource = AuxiliarGradoSeccionAnioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
