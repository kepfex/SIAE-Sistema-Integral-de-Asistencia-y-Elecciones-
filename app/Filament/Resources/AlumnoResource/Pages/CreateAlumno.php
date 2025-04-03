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
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generar un hash del DNI
        // $hashedDni = hash('sha256', $data['dni']);
        $hashedDni = substr(hash('sha256', $data['dni'] . uniqid()), 0, 10) . $data['dni'];
        $data['codigo_qr'] = $hashedDni; // Guardar el hash en la BD

        // Generar el código QR y guardarlo en storage
        $qrCode = QrCode::format('png')
            ->size(300)
            // ->errorCorrection('H')
            // ->merge(public_path('img/insignia.png'), 0.3, true)
            ->generate($hashedDni);

        $pathName = "qrcodes/{$hashedDni}.png"; // Nombre y ruta del archivo
        Storage::disk('public')->put($pathName, $qrCode); // Guardamos en el Storage


        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
