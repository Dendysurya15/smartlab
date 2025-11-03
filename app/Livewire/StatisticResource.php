<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatisticResource\Pages;
use App\Models\Statistic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;

class StatisticResource extends Resource
{
    protected static ?string $model = Statistic::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Landing Page';

    protected static ?string $navigationLabel = 'Statistics';

    protected static ?string $modelLabel = 'Statistic';

    protected static ?string $pluralModelLabel = 'Statistics';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Content')
                    ->schema([
                        TextInput::make('label')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Label for the statistic (e.g., "Akurasi", "Sampel/Hari")'),

                        TextInput::make('value')
                            ->required()
                            ->maxLength(255)
                            ->helperText('The numeric value to display'),

                        TextInput::make('suffix')
                            ->maxLength(10)
                            ->placeholder('%')
                            ->helperText('Suffix to display after the value (e.g., %, +, h)'),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
                    ])
                    ->columns(2),

                Section::make('Styling')
                    ->schema([
                        ColorPicker::make('gradient_from')
                            ->label('Gradient From Color')
                            ->default('#10b981')
                            ->helperText('Starting color of the gradient'),

                        ColorPicker::make('gradient_to')
                            ->label('Gradient To Color')
                            ->default('#3b82f6')
                            ->helperText('Ending color of the gradient'),
                    ])
                    ->columns(2),

                Section::make('Visibility')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('value')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('suffix')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextColumn::make('gradient_from')
                    ->label('From Color')
                    ->formatStateUsing(fn(string $state): string => strtoupper($state))
                    ->color(fn(string $state): string => $state),

                TextColumn::make('gradient_to')
                    ->label('To Color')
                    ->formatStateUsing(fn(string $state): string => strtoupper($state))
                    ->color(fn(string $state): string => $state),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_active')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStatistics::route('/'),
            'create' => Pages\CreateStatistic::route('/create'),
            'edit' => Pages\EditStatistic::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('sort_order', 'asc');
    }
}
