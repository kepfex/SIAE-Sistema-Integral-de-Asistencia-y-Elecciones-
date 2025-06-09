<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuxiliarGradoSeccionAnioResource\Pages;
use App\Filament\Resources\AuxiliarGradoSeccionAnioResource\RelationManagers;
use App\Models\AnioEscolar;
use App\Models\AuxiliarGradoSeccionAnio;
use App\Models\Seccion;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuxiliarGradoSeccionAnioResource extends Resource
{
    protected static ?string $model = AuxiliarGradoSeccionAnio::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return 'Asignación Auxiliares';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Asignaciones Auxiliares';
    }

    public static function getNavigationLabel(): string
    {
        return 'Asignación Auxiliares';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Auxiliar')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Select::make('grado_id')
                    ->label('Grado')
                    ->relationship('grado', 'nombre')
                    ->required(),

                CheckboxList::make('secciones')
                    ->label('Secciones')
                    ->options(Seccion::all()->pluck('nombre', 'id'))
                    ->columns(3)
                    ->required()
                    ->hint('Selecciona una o más secciones'),

                // Select::make('anio_escolar_visible')
                //     ->label('Año Escolar')
                //     ->options(\App\Models\AnioEscolar::pluck('nombre', 'id'))
                //     ->default(fn() => session('anio_escolar_id'))
                //     ->disabled(),
                Hidden::make('anio_escolar_id')
                    ->default(fn() => session('anio_escolar_id'))
                    ->required(),
                Placeholder::make('anio_escolar_visible')
                    ->label('Año Escolar')
                    ->content(fn() => AnioEscolar::find(session('anio_escolar_id'))?->nombre ?? '---'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Auxiliar'),
                TextColumn::make('grado.nombre')->label('Grado'),
                TextColumn::make('seccion.nombre')->label('Sección'),
                TextColumn::make('anioEscolar.nombre')->label('Año Escolar'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAuxiliarGradoSeccionAnios::route('/'),
            'create' => Pages\CreateAuxiliarGradoSeccionAnio::route('/create'),
            'edit' => Pages\EditAuxiliarGradoSeccionAnio::route('/{record}/edit'),
        ];
    }
}
