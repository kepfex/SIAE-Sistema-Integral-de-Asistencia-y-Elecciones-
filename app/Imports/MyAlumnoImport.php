<?php

namespace App\Imports;

use App\Models\Alumno;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MyAlumnoImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $dni = Alumno::where('dni', $row['dni'])->first();

            if ($dni === null) { // Si el DNI no existe, entonces insertamos

                // Generar un hash del DNI
                $hashedDni = substr(hash('sha256', $row['dni'] . uniqid()), 0, 10) . $row['dni'];
                $row['codigo_qr'] = $hashedDni;

                // Generar el código QR y guardarlo en storage
                $qrCode = QrCode::format('png')
                                ->size(300)
                                // ->errorCorrection('H')
                                ->generate($hashedDni);
                                
                $pathName = "qrcodes/{$hashedDni}.png"; // Nombre y ruta del archivo
                Storage::disk('public')->put($pathName, $qrCode); // Guardamos en el Storage

                // Insertar el alumno a la Base de datos
                Alumno::create([
                    'dni' => $row['dni'],
                    'nombres' => $row['nombres'],
                    'apellido_paterno' => $row['apellido_paterno'],
                    'apellido_materno' => $row['apellido_materno'],
                    'genero' => $row['genero'],
                    'celular' => $row['celular'],
                    'codigo_qr' => $row['codigo_qr'],
                    'imagen_url' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
