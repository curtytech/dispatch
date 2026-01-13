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
                    ->label('Data de Nascimento')
                    ->required()
                    ->maxDate(now()->subYears(18))

                    ->rules([
                        'required',
                        'date',
                        'before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
                    ])

                    ->validationMessages([
                        'required' => 'A data de nascimento é obrigatória.',
                        'before_or_equal' => 'O motorista deve ter 18 anos ou mais.',
                    ]),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255)
                    ->label('Descrição'),

                Forms\Components\TextInput::make('cpf')
                    ->label('CPF')
                    ->required()
                    ->placeholder('000.000.000-00')
                    ->mask('999.999.999-99') // máscara visível
                    ->extraInputAttributes([
                        'type' => 'text',
                        'inputmode' => 'numeric',
                    ])
                    ->reactive()
                    ->maxLength(14) // tamanho máximo com máscara
                    ->rules([
                        'required',
                        'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', // valida o formato da máscara
                    ])
                    ->dehydrateStateUsing(fn ($state) => preg_replace('/\D/', '', $state)) // salva só números
                    ->validationMessages([
                        'required' => 'O CPF é obrigatório.',
                        'regex' => 'O CPF deve estar no formato 000.000.000-00.',
                ]),
                
                Forms\Components\TextInput::make('cnh')
                    ->label('CNH')
                    ->required()
                    ->maxLength(11)
                    ->placeholder('Informe os 11 números da CNH')

                    // Teclado numérico sem validação do browser
                    ->extraInputAttributes([
                        'type' => 'text',
                        'inputmode' => 'numeric',
                    ])

                    // Remove qualquer coisa que não seja número ao salvar
                    ->dehydrateStateUsing(fn ($state) => preg_replace('/\D/', '', $state))

                    // Validação real
                    ->rules([
                        'required',
                        'digits:11',
                    ])

                    ->validationMessages([
                        'required' => 'A CNH é obrigatória.',
                        'digits' => 'A CNH deve conter exatamente 11 números.',
                    ]),

                Forms\Components\DatePicker::make('cnh_expiration_date')
                    ->label('Validade da CNH')
                    ->required()
                    ->minDate(\Carbon\Carbon::today()) // permite hoje
                    ->placeholder('Selecione a data')
                    ->reactive()
                    ->validationMessages([
                        'required' => 'A validade da CNH é obrigatória.',
                    ]),

                Forms\Components\DatePicker::make('toxicologic_exam_expiration_date')
    ->label('Validade do Exame Toxicológico')
    ->required()
    ->minDate(\Carbon\Carbon::today()) // permite hoje, bloqueia datas passadas
    ->placeholder('Selecione a data')
    ->reactive()
    ->validationMessages([
        'required' => 'A validade do exame toxicológico é obrigatória.',
    ]),

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
