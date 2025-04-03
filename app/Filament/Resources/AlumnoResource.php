<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlumnoResource\Pages;
use App\Filament\Resources\AlumnoResource\RelationManagers;
use App\Models\Alumno;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;


class AlumnoResource extends Resource
{
    protected static ?string $model = Alumno::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->columns(5)
                    ->schema([
                        Forms\Components\Section::make()
                            ->columnSpan(3)
                            ->schema([
                                Forms\Components\TextInput::make('dni')
                                    ->label('Nro DNI')
                                    ->required()
                                    ->numeric() // Asegura que solo se acepten números
                                    ->minLength(8) // Asegura que tenga al menos 8 caracteres
                                    ->maxLength(8) // Asegura que tenga como máximo 8 caracteres
                                    ->rule('regex:/^[0-9]{8}$/') // Asegura que solo tenga 8 dígitos numéricos
                                    ->rule('unique:alumnos,dni') // Verifica que el DNI no exista en la tabla 'alumnos'
                                    ->placeholder('Ingrese el número de DNI')
                                    ->validationMessages([
                                        'regex' => 'El DNI debe ser de 8 dígitos.',
                                        'min_digits' => 'El DNI debe tener 8 dígitos.',
                                        'max_digits' => 'El DNI no puede tener más de 8 dígitos.',
                                        'unique' => 'Este número de DNI ya está registrado.',
                                    ]),
                                Forms\Components\TextInput::make('nombres')
                                    ->required()
                                    ->minLength(3)
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('apellido_paterno')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('apellido_materno')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('genero')
                                    ->label('Género')
                                    ->options([
                                        'Hombre' => 'Hombre',
                                        'Mujer' => 'Mujer',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('celular')
                                    ->label('N° de Celular')
                                    ->numeric() // Asegura que solo se acepten números
                                    ->minLength(9) // Asegura que tenga al menos 9 caracteres
                                    ->maxLength(9) // Asegura que tenga como máximo 9 caracteres
                                    ->rule('regex:/^9[0-9]{8}$/') // Asegura que el número comience con "9" y tenga 9 dígitos
                                    ->placeholder('Ingrese el número de celular del apoderado')
                                    ->validationMessages([
                                        'regex' => 'El número de celular debe comenzar con 9.',
                                        'min_digits' => 'El número de celular debe tener 9 dígitos.',
                                        'max_digits' => 'El número de celular debe tener solo 9 dígitos.',
                                    ])
                                    ->default(null)
                            ]),
                        Forms\Components\Section::make()
                            ->columnSpan(2)
                            ->schema([
                                Forms\Components\FileUpload::make('imagen_url')
                                    ->image(),
                            ])
                    ])
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dni')
                    ->label('DNI')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombres')
                    ->searchable(),
                Tables\Columns\TextColumn::make('apellido_paterno')
                    ->searchable(),
                Tables\Columns\TextColumn::make('apellido_materno')
                    ->searchable(),
                Tables\Columns\TextColumn::make('genero')
                    ->label('Género'),
                Tables\Columns\TextColumn::make('celular')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('codigo_qr')
                    ->label('Código QR')
                    ->disk('public') // Asegura que busca en storage/app/public
                    ->getStateUsing(fn($record) => asset("storage/qrcodes/{$record->codigo_qr}.png")),
                Tables\Columns\ImageColumn::make('imagen_url')
                    ->label('Foto')
                    ->defaultImageUrl(url('/img/usuario.svg')),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exports([
                            ExcelExport::make('table')
                                ->fromTable()
                                ->withFilename('AlumnosACP_' . date('Y-m-d') . ' - export'),
                    ]),
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
            'index' => Pages\ListAlumnos::route('/'),
            'create' => Pages\CreateAlumno::route('/create'),
            'edit' => Pages\EditAlumno::route('/{record}/edit'),
        ];
    }
}
