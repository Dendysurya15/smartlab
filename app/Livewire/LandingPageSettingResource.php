<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LandingPageSettingResource\Pages;
use App\Models\LandingPageSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;

class LandingPageSettingResource extends Resource
{
    protected static ?string $model = LandingPageSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Landing Page';

    protected static ?string $navigationLabel = 'Page Settings';

    protected static ?string $modelLabel = 'Page Setting';

    protected static ?string $pluralModelLabel = 'Page Settings';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Setting Information')
                    ->schema([
                        TextInput::make('key')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Unique identifier for this setting'),

                        Select::make('group')
                            ->options([
                                'hero' => 'Hero Section',
                                'general' => 'General Settings',
                                'footer' => 'Footer Settings',
                                'features' => 'Features Section',
                                'contact' => 'Contact Information',
                            ])
                            ->required()
                            ->reactive(),

                        TextInput::make('label')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Human-readable label'),
                    ])
                    ->columns(2),

                Section::make('Content')
                    ->schema([
                        TextInput::make('value')
                            ->label('Value')
                            ->helperText('The actual setting value')
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->helperText('Description of what this setting does')
                            ->columnSpanFull(),

                        Select::make('type')
                            ->options([
                                'text' => 'Text',
                                'textarea' => 'Text Area',
                                'image' => 'Image Upload',
                                'url' => 'URL',
                                'email' => 'Email',
                                'number' => 'Number',
                                'boolean' => 'Yes/No',
                            ])
                            ->default('text')
                            ->reactive(),
                    ])
                    ->columns(2),

                Section::make('Additional Options')
                    ->schema([
                        TextInput::make('placeholder')
                            ->helperText('Placeholder text for form fields'),

                        TextInput::make('validation_rules')
                            ->label('Validation Rules')
                            ->helperText('Laravel validation rules (e.g., required|max:255)'),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Order within the group'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('label')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('group')
                    ->colors([
                        'hero' => 'success',
                        'general' => 'info',
                        'footer' => 'warning',
                        'features' => 'danger',
                        'contact' => 'gray',
                    ]),

                BadgeColumn::make('type')
                    ->colors([
                        'text' => 'primary',
                        'textarea' => 'secondary',
                        'image' => 'success',
                        'url' => 'info',
                        'email' => 'warning',
                        'number' => 'danger',
                        'boolean' => 'gray',
                    ]),

                TextColumn::make('value')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->options([
                        'hero' => 'Hero Section',
                        'general' => 'General Settings',
                        'footer' => 'Footer Settings',
                        'features' => 'Features Section',
                        'contact' => 'Contact Information',
                    ]),

                SelectFilter::make('type')
                    ->options([
                        'text' => 'Text',
                        'textarea' => 'Text Area',
                        'image' => 'Image Upload',
                        'url' => 'URL',
                        'email' => 'Email',
                        'number' => 'Number',
                        'boolean' => 'Yes/No',
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
            ->defaultSort('group', 'asc')
            ->defaultSort('sort_order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLandingPageSettings::route('/'),
            'create' => Pages\CreateLandingPageSetting::route('/create'),
            'edit' => Pages\EditLandingPageSetting::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('group', 'asc')
            ->orderBy('sort_order', 'asc');
    }
}
