<?php

namespace App\Filament\Resources\AlumnoResource\Pages;

use App\Filament\Resources\AlumnoResource;
use App\Imports\MyAlumnoImport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;


class ListAlumnos extends ListRecords
{
    protected static string $resource = AlumnoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->color("primary")
                ->label('Importar')
                ->use(MyAlumnoImport::class),
                Actions\CreateAction::make(),
        ];
    }
}
