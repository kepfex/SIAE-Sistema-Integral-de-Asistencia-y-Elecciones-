<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\AnioEscolar;
use App\Models\Grado;
use App\Models\Matricula;
use App\Models\Seccion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MyMatriculaImport implements ToCollection, WithHeadingRow
{
    // use SkipsFailures;
    
    /**
    * @param Collection $collection
    */
    public function collection(Collection $filas)
    {
        foreach ($filas as $fila) {
            $alumno = Alumno::where('dni', $fila['nro_dni'])->first();
            if (!$alumno) { // Si el alumno no existe, saltar la fila o registrar un error
                continue;
            }
            $anioEscolar = AnioEscolar::where('nombre', $fila['periodo_escolar'])->first();
            if (!$anioEscolar) { // Si no se encuentra el año escolar, saltar la fila
                continue;
            }
            
            // Verificar si el alumno ya tiene una matrícula en ese año escolar
            $existeMatricula = Matricula::where('alumno_id', $alumno->id)
            ->where('anio_escolar_id', $anioEscolar->id)
            ->exists();
            if ($existeMatricula) { // Si ya está matriculado en ese año, saltar la inserción
                continue;
            }

            $grado = Grado::where('nombre', $fila['grado'])->first();
            $seccion = Seccion::where('nombre', $fila['seccion'])->first();
            if (!$grado || !$seccion) {
                continue;
            }
            // dd($fila);
            Matricula::create([ // Insertar la nueva matrícula
                'alumno_id'      => $alumno->id,
                'anio_escolar_id' => $anioEscolar->id,
                'grado_id'       => $grado->id,
                'seccion_id'     => $seccion->id,
            ]);
        }
    }

    /**
     * Reglas de validación para el archivo.
     */
    // public function rules(): array
    // {
    //     return [
    //         'dni'          => 'required|exists:alumnos,dni',
    //         'anio_escolar' => 'required|exists:anios_escolares,nombre',
    //         'grado_id'     => 'required|exists:grados,id',
    //         'seccion_id'   => 'required|exists:secciones,id',
    //     ];
    // }

    // /**
    //  * Mensajes personalizados para los errores de validación.
    //  */
    // public function customValidationMessages()
    // {
    //     return [
    //         'dni.required'          => 'El campo DNI es obligatorio.',
    //         'dni.exists'            => 'El DNI no está registrado en la base de datos.',
    //         'anio_escolar.required' => 'El año escolar es obligatorio.',
    //         'anio_escolar.exists'   => 'El año escolar no existe en la base de datos.',
    //         'grado_id.required'     => 'El grado es obligatorio.',
    //         'grado_id.exists'       => 'El grado no existe en la base de datos.',
    //         'seccion_id.required'   => 'La sección es obligatoria.',
    //         'seccion_id.exists'     => 'La sección no existe en la base de datos.',
    //     ];
    // }
}
