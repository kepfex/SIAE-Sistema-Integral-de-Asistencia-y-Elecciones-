<?php

namespace App\Filament\Resources\MatriculaResource\Pages;

use App\Filament\Resources\MatriculaResource;
use App\Imports\MyMatriculaImport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMatriculas extends ListRecords
{
    protected static string $resource = MatriculaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->color("success")
                ->icon('heroicon-m-document-arrow-down')
                ->label('Cargar Matricula')
                ->use(MyMatriculaImport::class),
            Actions\CreateAction::make(),
        ];
    }
}
