<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryImageResource\Pages;
use App\Models\GalleryImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;

class GalleryImageResource extends Resource
{
    protected static ?string $model = GalleryImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Landing Page';

    protected static ?string $navigationLabel = 'Gallery Images';

    protected static ?string $modelLabel = 'Gallery Image';

    protected static ?string $pluralModelLabel = 'Gallery Images';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Image Information')
                    ->schema([
                        FileUpload::make('file_path')
                            ->label('Image')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->directory('gallery')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),

                        TextInput::make('alt_text')
                            ->label('Alt Text')
                            ->maxLength(255)
                            ->columnSpan(1),

                        Textarea::make('caption')
                            ->rows(3)
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Display Settings')
                    ->schema([
                        Select::make('position')
                            ->options([
                                'hero' => 'Hero (Large Main)',
                                'top-right' => 'Top Right',
                                'middle-right' => 'Middle Right',
                                'bottom-left' => 'Bottom Left',
                                'bottom-center' => 'Bottom Center',
                                'bottom-right' => 'Bottom Right',
                            ])
                            ->required()
                            ->default('bottom-left'),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                        Toggle::make('is_carousel')
                            ->label('Show in Carousel')
                            ->helperText('Enable for auto-rotating carousel images'),
                    ])
                    ->columns(2),

                Section::make('Badge & Overlay')
                    ->schema([
                        TextInput::make('badge_text')
                            ->label('Badge Text')
                            ->maxLength(50)
                            ->helperText('Optional badge text to display on image'),

                        Select::make('badge_color')
                            ->label('Badge Color')
                            ->options([
                                'emerald' => 'Emerald',
                                'blue' => 'Blue',
                                'purple' => 'Purple',
                                'red' => 'Red',
                                'yellow' => 'Yellow',
                                'gray' => 'Gray',
                            ])
                            ->default('emerald'),

                        TextInput::make('overlay_gradient')
                            ->label('Overlay Gradient')
                            ->placeholder('emerald-blue')
                            ->helperText('CSS gradient colors for hover overlay'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('file_path')
                    ->label('Image')
                    ->size(60)
                    ->square(),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('position')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'hero' => 'success',
                        'top-right', 'middle-right' => 'info',
                        'bottom-left', 'bottom-center', 'bottom-right' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                IconColumn::make('is_carousel')
                    ->label('Carousel')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('position')
                    ->options([
                        'hero' => 'Hero',
                        'top-right' => 'Top Right',
                        'middle-right' => 'Middle Right',
                        'bottom-left' => 'Bottom Left',
                        'bottom-center' => 'Bottom Center',
                        'bottom-right' => 'Bottom Right',
                    ]),

                SelectFilter::make('is_active')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),

                SelectFilter::make('is_carousel')
                    ->options([
                        1 => 'Carousel',
                        0 => 'Static',
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
            'index' => Pages\ListGalleryImages::route('/'),
            'create' => Pages\CreateGalleryImage::route('/create'),
            'edit' => Pages\EditGalleryImage::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('sort_order', 'asc');
    }
}
