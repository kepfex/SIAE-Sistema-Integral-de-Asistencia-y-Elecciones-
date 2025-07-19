<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsistenciaResource\Pages;
use App\Models\Asistencia;
use App\Models\Grado;
use App\Models\Seccion;
use Filament\Forms;
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
                Forms\Components\Select::make('matricula_id')
                    ->label('Estudiante: Nombres y Apellidos')
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return \App\Models\Matricula::whereHas('alumno', function ($query) use ($search) {
                                $query->whereRaw("CONCAT(nombres, ' ', apellido_paterno, ' ', apellido_materno) LIKE ?", ["%{$search}%"]);
                            })
                            ->where('anio_escolar_id', session('anio_escolar_id'))
                            ->with('alumno')
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($matricula) {
                                $alumno = $matricula->alumno;
                                return [$matricula->id => "{$alumno->nombres} {$alumno->apellido_paterno} {$alumno->apellido_materno}"];
                            });
                    })
                    ->getOptionLabelUsing(function ($value): ?string {
                        $matricula = \App\Models\Matricula::with('alumno')->find($value);
                        if (!$matricula || !$matricula->alumno) return null;

                        return "{$matricula->alumno->nombres} {$matricula->alumno->apellido_paterno} {$matricula->alumno->apellido_materno}";
                    })
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                        $matricula = \App\Models\Matricula::with(['grado', 'seccion'])->find($state);

                        if ($matricula) {
                            $set('grado_id', $matricula->grado_id);
                            $set('seccion_id', $matricula->seccion_id);
                        } else {
                            $set('grado_id', null);
                            $set('seccion_id', null);
                        }
                    }),
                Forms\Components\DatePicker::make('fecha')
                    ->label('Fecha de Asistencia')
                    ->default(now())
                    ->required(),
    
                Forms\Components\TimePicker::make('hora')
                    ->label('Hora de Ingreso')
                    ->default(now()->format('H:i'))
                    ->required(),
    
                Forms\Components\Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'P' => 'Puntual',
                        'T' => 'Tardanza',
                        'F' => 'Faltó',
                        'J' => 'Falta Justificada',
                        'U' => 'Tardanza Justificada',
                    ])
                    ->required(),
    
                Forms\Components\Select::make('grado_id')
                    ->label('Grado')
                    ->options(\App\Models\Grado::pluck('nombre', 'id'))
                    ->disabled()
                    ->dehydrated(true)
                    ->required(),
                
                Forms\Components\Select::make('seccion_id')
                    ->label('Sección')
                    ->options(\App\Models\Seccion::pluck('nombre', 'id'))
                    ->disabled()
                    ->dehydrated(true)
                    ->required(),
    
                Forms\Components\Hidden::make('anio_escolar_id')
                    ->default(fn () => session('anio_escolar_id')),
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
            ->recordUrl(null) // Esto desactiva el clic en toda la fila
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
