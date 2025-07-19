<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MatriculaResource\Pages;
use App\Models\Matricula;
use App\Models\Seccion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Illuminate\Support\Facades\Storage;

class MatriculaResource extends Resource
{
    protected static ?string $model = Matricula::class;

    protected static ?string $navigationIcon = 'iconpark-checklist';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $anioEscolarId = session('anio_escolar_id');
        $user = Auth::user();

        // Filtro por año escolar
        $query->when(
            $anioEscolarId,
            fn($q) =>
            $q->where('anio_escolar_id', $anioEscolarId)
        );

        // Filtro si el usuario es auxiliar
        if ($user && $user->hasRole('Auxiliar')) {
            $asignaciones = $user->asignacionesAuxiliar()
                ->where('anio_escolar_id', $anioEscolarId)
                ->get();

            $gradosIds = $asignaciones->pluck('grado_id')->unique();
            $seccionesIds = $asignaciones->pluck('seccion_id')->unique();

            $query->whereIn('grado_id', $gradosIds)
                ->whereIn('seccion_id', $seccionesIds);
        }

        return $query;
    }

    // public static function getEloquentQuery(): Builder
    // {
    //     $anioEscolarId = session('anio_escolar_id');
    //     return parent::getEloquentQuery()
    //     ->when($anioEscolarId, fn ($query) =>
    //         $query->where('anio_escolar_id', $anioEscolarId)
    //     );
    // }

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
                Tables\Columns\ImageColumn::make('alumno.imagen_url')
                    ->label('Foto')
                    ->disk('public')
                    ->circular()
                    ->getStateUsing(function ($record) {
                        $ruta = $record->alumno?->imagen_url;
                
                        if ($ruta && Storage::disk('public')->exists($ruta)) {
                            return asset("storage/{$ruta}");
                        }
                
                        return url('/img/usuario.svg');
                    })
                    ->openUrlInNewTab(true)
                    ->tooltip('Editar estudiante')
                    ->extraAttributes(['class' => 'cursor-pointer hover:opacity-80'])
                    ->url(fn ($record) => AlumnoResource::getUrl('edit', [
                        'record' => $record->alumno->id,
                        'desde' => 'matriculas',
                    ]))
                    ->openUrlInNewTab(false),
                Tables\Columns\TextColumn::make('alumno.dni')
                    ->label('Nro DNI')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alumno.nombres')
                    ->label('Nombres')
                    ->sortable(),
                Tables\Columns\TextColumn::make('alumno')
                    ->label('Apellidos')
                    ->formatStateUsing(fn($record) => $record->alumno->apellido_paterno . ' ' . $record->alumno->apellido_materno)
                    ->sortable(),
                Tables\Columns\TextColumn::make('alumno.celular')
                    ->label('# WhatsApp')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('anioEscolar.nombre')
                    ->label('Periodo Escolar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('grado.nombre')
                    ->sortable(),
                Tables\Columns\TextColumn::make('seccion.nombre')
                    ->sortable(),
            ])
            ->recordUrl(null) // Esto desactiva el clic en toda la fila
            ->filters([
                SelectFilter::make('grado_id')
                    ->label('Grado')
                    ->options(function () {
                        $user = Auth::user();
                        $anioEscolarId = session('anio_escolar_id');

                        if ($user->hasRole('Auxiliar')) {
                            return $user->asignacionesAuxiliar()
                                ->where('anio_escolar_id', $anioEscolarId)
                                ->with('grado')
                                ->get()
                                ->pluck('grado.nombre', 'grado.id');
                        }

                        return \App\Models\Grado::pluck('nombre', 'id');
                    }),
                SelectFilter::make('seccion_id')
                    ->label('Sección')
                    ->options(function () {
                        $user = Auth::user();
                        $anioEscolarId = session('anio_escolar_id');

                        if ($user->hasRole('Auxiliar')) {
                            return $user->asignacionesAuxiliar()
                                ->where('anio_escolar_id', $anioEscolarId)
                                ->with('seccion')
                                ->get()
                                ->pluck('seccion.nombre', 'seccion.id');
                        }

                        return \App\Models\Seccion::pluck('nombre', 'id');
                    }),
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
                    ExportBulkAction::make()
                        ->exports([
                            ExcelExport::make('table')
                                ->fromTable()
                                ->withFilename('AlumnosMatriculados' . date('Y-m-d') . ' - export'),
                        ]),
                ]),
                Tables\Actions\BulkAction::make('export_qr')
                    ->button()
                    ->label('Generar Carnet PDF')
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    // ->action(fn($records) => redirect()->route('pdf.carnetqr', ['records' => implode(',', $records->pluck('id')->toArray())]))

                    ->modalHeading('Carnets Listos')
                    ->modalDescription('Haz clic en el botón para abrir el PDF en una nueva pestaña e imprimir.')
                    ->modalSubmitAction(function ($records) {
                        $ids = implode(',', $records->pluck('id')->toArray());

                        return Action::make('Imprimir')
                            ->url(route('pdf.carnetqr', ['records' => $ids]))
                            ->openUrlInNewTab()
                            ->button()
                            ->icon('heroicon-o-printer')
                            ->color('success')
                            ->close();
                    })
                    ->action(fn() => null)
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
}
