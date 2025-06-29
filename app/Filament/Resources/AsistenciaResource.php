<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsistenciaResource\Pages;
use App\Models\Asistencia;
use App\Models\Grado;
use App\Models\Seccion;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AsistenciaResource extends Resource
{
    protected static ?string $model = Asistencia::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $title = 'Asistencias por día';
    
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationGroup = 'Asistencia';
    protected static ?string $navigationLabel = 'Asistencia diaria';

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


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('matricula.alumno.nombre_completo')
                    ->label('Alumno')
                    ->getStateUsing(
                        fn($record) =>
                        $record->matricula->alumno->nombres . ' ' .
                            $record->matricula->alumno->apellido_paterno . ' ' .
                            $record->matricula->alumno->apellido_materno
                    ),
                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d \d\e F \d\e Y')
                    ->sortable(),
                TextColumn::make('hora')
                    ->label('Hora')
                    ->time('h:i A')
                    ->sortable(),
                TextColumn::make('estado_nombre')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Puntual' => 'success',
                        'Faltó' => 'danger',
                        'Tardanza' => 'warning',
                        'Falta Justificada' => 'info',
                        'Tardanza Justificada' => 'gray',
                        default => 'secondary',
                    }),
                TextColumn::make('grado.nombre')->label('Grado'),
                TextColumn::make('seccion.nombre')->label('Sección'),

            ])
            ->filters([
                // Filtro por fecha
                Filter::make('fecha')
                    ->form([
                        DatePicker::make('fecha')->label('Fecha de asistencia'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $data['fecha']
                            ? $query->whereDate('fecha', $data['fecha'])
                            : $query;
                    }),
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
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListAsistencias::route('/'),
            'create' => Pages\CreateAsistencia::route('/create'),
            'edit' => Pages\EditAsistencia::route('/{record}/edit'),
        ];
    }
}
