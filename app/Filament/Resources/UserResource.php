<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Role;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): ?string
    {
        return __('Seguridad');
    }

    public static function getLabel(): ?string
    {
        return __('Usuario');
    }

    public static function getNavigationLabel(): string
    {
        return __('Usuarios');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->autofocus()
                    ->required()
                    ->maxLength(200)
                    ->label(__('Nombre')),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(200)
                    ->unique(static::getModel(), 'email', ignoreRecord: true)
                    ->label(__('Correo Electrónico')),
                Select::make('roles')
                    ->relationship('roles', 'nombre')
                    ->multiple()
                    ->label(__('Roles')),
                Select::make('permissions')
                    ->relationship('permissions', 'nombre')
                    ->multiple()
                    ->label(__('Permisos')),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context == 'create')
                    ->confirmed()
                    ->maxLength(200)
                    ->label(__('Contraseña')),
                TextInput::make('password_confirmation')
                    ->password()
                    ->label(__('Confirmar contraseña')),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Nombre'))
                    ->sortable()
                    ->searchable()
                    ->description(fn (User $user) => $user->email),
                TextColumn::make('roles.nombre')
                    ->label(__('Roles'))
                    ->badge(),
                TextColumn::make('permissions.nombre')
                    ->label(__('Permisos'))
                    ->badge(),
                TextColumn::make('created_at')
                    ->label(__('Creado'))
                    ->date('d/m/Y H:i')
            ])
            ->filters([
                // SelectFilter::make('roles')
                //     ->label(__('Roles'))
                //     ->options(Role::pluck('nombre', 'id')->toArray())
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
