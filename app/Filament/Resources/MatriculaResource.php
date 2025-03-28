<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MatriculaResource\Pages;
use App\Models\Matricula;
use App\Models\Seccion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;


class MatriculaResource extends Resource
{
    protected static ?string $model = Matricula::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('alumno_id')
                    ->label('Alumno: DNI - Nombres y Apellidos')
                    ->relationship('alumno', 'dni')
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->dni} - {$record->nombres} {$record->apellido_paterno} {$record->apellido_materno}")
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('anio_escolar_id')
                    ->label('Año Escolar')
                    // ->multiple()
                    ->relationship('anioEscolar', 'nombre')
                    // ->searchable()
                    ->required(),
                Forms\Components\Select::make('grado_id')
                    ->label('Grado')
                    ->formatStateUsing(fn($state) => strtoupper($state))
                    ->relationship('grado', 'nombre')
                    // ->options(Grado::all()->pluck('nombre', 'id')->map(fn($nombre) => strtoupper($nombre)))
                    ->required(),
                Forms\Components\Select::make('seccion_id')
                    ->label('Sección')
                    ->options(Seccion::all()->pluck('nombre', 'id'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('alumno.dni')
                    ->label('N° DNI')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alumno.nombres')
                    ->label('Nombres')
                    ->sortable(),
                Tables\Columns\TextColumn::make('alumno')
                    ->label('Apellidos')
                    ->formatStateUsing(fn($record) => $record->alumno->apellido_paterno . ' ' . $record->alumno->apellido_materno)
                    ->sortable(),
                Tables\Columns\TextColumn::make('anioEscolar.nombre')
                    ->label('Año Escolar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('grado.nombre')
                    ->sortable(),
                Tables\Columns\TextColumn::make('seccion.nombre')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            // ->headerActions([
            //     Tables\Actions\BulkAction::make('generar_qr')
            //         ->button()
            //         ->label('Generar Carnet')
            //         ->icon('heroicon-o-document-text')
            //         ->action(fn(Collection $records) => static::generarPDF($records))
            //         ->requiresConfirmation()
            //         ->color('primary'),
            // ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ExportAction::make()
                        ->label('Generar Carnet'),
                ]),
                Tables\Actions\BulkAction::make('export_qr')
                    ->button()
                    ->label('Generar Carnet')
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    ->action(fn ($records) => redirect()->route('pdf.carnetqr', ['records' => implode(',', $records->pluck('id')->toArray())]))

            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMatriculas::route('/'),
            'create' => Pages\CreateMatricula::route('/create'),
            'edit' => Pages\EditMatricula::route('/{record}/edit'),
        ];
    }

    public static function generarPDF(Collection $records)
    {
        // dd(json_encode($records, JSON_UNESCAPED_UNICODE));
        // dd($records->toArray());
        // Convertir los datos a UTF-8 correctamente
        // $records = $records->map(function ($record) {
        //     return array_map(fn($value) => mb_convert_encoding($value, 'UTF-8', 'auto'), $record->toArray());
        // });
    
        // $pdf = Pdf::loadView('pdf.carnetqr', ['matriculas' => [['nombre' => 'Pedro Ramirez']]]);
        // return $pdf->stream('carnets_reporte.pdf');
    }
}
