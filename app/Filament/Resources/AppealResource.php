<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppealResource\Pages;
use App\Filament\Resources\AppealResource\RelationManagers;
use App\Models\Appeal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppealResource extends Resource
{
    protected static ?string $model = Appeal::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';

    protected static ?string $modelLabel = 'Recurso';

    protected static ?string $pluralModelLabel = 'Recursos';

    protected static ?string $navigationLabel = 'Recursos';

    protected static ?string $navigationGroup = 'Gerenciamento';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->label('Usuário'),
                Forms\Components\Select::make('fine_id')
                    ->relationship('fine', 'ait')
                    ->required()
                    ->label('Multa (AIT)'),
                Forms\Components\Select::make('appeal_status_id')
                    ->relationship('appealStatus', 'name')
                    ->label('Status')
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->label('Data')
                    ->maxDate('today'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuário')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fine.ait')
                    ->label('Multa (AIT)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('appealStatus.name')
                    ->label('Status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date('d/m/Y')
                    ->sortable()
                    ->label('Data'),
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
            'index' => Pages\ListAppeals::route('/'),
            'create' => Pages\CreateAppeal::route('/create'),
            'edit' => Pages\EditAppeal::route('/{record}/edit'),
        ];
    }
}
