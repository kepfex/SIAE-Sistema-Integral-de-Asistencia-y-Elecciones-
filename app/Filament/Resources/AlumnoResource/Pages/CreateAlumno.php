<?php

namespace App\Filament\Resources\AlumnoResource\Pages;

use App\Filament\Resources\AlumnoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CreateAlumno extends CreateRecord
{
    protected static string $resource = AlumnoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generar un hash del DNI
        $hashedDni = hash('sha256', $data['dni']);
        $filename = "qrcodes/{$hashedDni}.png";

        // Generar el código QR
        $qrCode = QrCode::format('png')->size(300)->generate($data['dni']);
        Storage::disk('public')->put($filename, $qrCode);

        // Guardar la ruta en la BD
        $data['codigo_qr'] = $filename;

        return $data;
    }
}
