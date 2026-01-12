<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DriverResource\Pages;
use App\Filament\Resources\DriverResource\RelationManagers;
use App\Models\Driver;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DriverResource extends Resource
{
    protected static ?string $model = Driver::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'Motorista';

    protected static ?string $pluralModelLabel = 'Motoristas';

    protected static ?string $navigationLabel = 'Motoristas';

    protected static ?string $navigationGroup = 'Gerenciamento';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->label('Usuário'),
                Forms\Components\Select::make('vehicle_id')
                    ->relationship('vehicle', 'name')
                    ->required()
                    ->label('Veículo'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nome'),
                Forms\Components\DatePicker::make('birth_date')
                    ->required()
                    ->label('Data de Nascimento'),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255)
                    ->label('Descrição'),
                Forms\Components\TextInput::make('cpf')
                    ->required()
                    ->maxLength(255)
                    ->label('CPF'),
                Forms\Components\TextInput::make('cnh')
                    ->required()
                    ->maxLength(255)
                    ->label('CNH'),
                Forms\Components\DatePicker::make('cnh_expiration_date')
                    ->required()
                    ->label('Validade da CNH'),
                Forms\Components\DatePicker::make('toxicologic_exam_expiration_date')
                    ->required()
                    ->label('Validade do Exame Toxicológico'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuário')
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehicle.name')
                    ->label('Veículo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cpf')
                    ->label('CPF')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cnh')
                    ->label('CNH')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cnh_expiration_date')
                    ->date('d/m/Y')
                    ->sortable()
                    ->label('Validade CNH'),
                Tables\Columns\TextColumn::make('toxicologic_exam_expiration_date')
                    ->date('d/m/Y')
                    ->sortable()
                    ->label('Validade Toxicológico'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Criado em'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Atualizado em'),
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
            'index' => Pages\ListDrivers::route('/'),
            'create' => Pages\CreateDriver::route('/create'),
            'edit' => Pages\EditDriver::route('/{record}/edit'),
        ];
    }
}
