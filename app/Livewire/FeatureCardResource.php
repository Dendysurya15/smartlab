<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeatureCardResource\Pages;
use App\Models\FeatureCard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;

class FeatureCardResource extends Resource
{
    protected static ?string $model = FeatureCard::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Landing Page';

    protected static ?string $navigationLabel = 'Feature Cards';

    protected static ?string $modelLabel = 'Feature Card';

    protected static ?string $pluralModelLabel = 'Feature Cards';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Content')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('subtitle')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('icon_svg')
                            ->label('Icon SVG Path')
                            ->placeholder('M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707')
                            ->helperText('SVG path from Heroicons or custom SVG')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Styling')
                    ->schema([
                        ColorPicker::make('color_from')
                            ->label('Gradient From Color')
                            ->default('#10b981')
                            ->helperText('Starting color of the gradient'),

                        ColorPicker::make('color_to')
                            ->label('Gradient To Color')
                            ->default('#3b82f6')
                            ->helperText('Ending color of the gradient'),

                        ColorPicker::make('text_color')
                            ->label('Text Color')
                            ->default('#ffffff')
                            ->helperText('Color of the text on the card'),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
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
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('subtitle')
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextColumn::make('color_from')
                    ->label('From Color')
                    ->formatStateUsing(fn(string $state): string => strtoupper($state))
                    ->color(fn(string $state): string => $state),

                TextColumn::make('color_to')
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
            'index' => Pages\ListFeatureCards::route('/'),
            'create' => Pages\CreateFeatureCard::route('/create'),
            'edit' => Pages\EditFeatureCard::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('sort_order', 'asc');
    }
}
