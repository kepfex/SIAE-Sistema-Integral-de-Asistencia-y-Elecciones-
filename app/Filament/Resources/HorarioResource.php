<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HorarioResource\Pages;
use App\Filament\Resources\HorarioResource\RelationManagers;
use App\Models\Horario;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HorarioResource extends Resource
{
    protected static ?string $model = Horario::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Configuración';
    protected static ?string $navigationLabel = 'Horarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('turno')
                    ->label('Turno')
                    ->options([
                        'mañana' => 'Mañana',
                        'tarde' => 'Tarde',
                        'noche' => 'Noche',
                    ])
                    ->required(),

                TimePicker::make('hora_inicio')
                    ->label('Inicio')
                    ->required(),
                TimePicker::make('hora_tolerancia')
                    ->label('Tolerancia')
                    ->required(),
                TimePicker::make('hora_maxima')
                    ->label('Máxima')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('turno')
                    ->label('Turno')
                    ->sortable(),
                TextColumn::make('hora_inicio')
                    ->label('Inicio'),
                TextColumn::make('hora_tolerancia')
                    ->label('Tolerancia'),
                TextColumn::make('hora_maxima')
                    ->label('Máxima'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('turno');
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
            'index' => Pages\ListHorarios::route('/'),
            'create' => Pages\CreateHorario::route('/create'),
            'edit' => Pages\EditHorario::route('/{record}/edit'),
        ];
    }
}
