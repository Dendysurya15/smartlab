<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Models\Announcement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Landing Page';

    protected static ?string $navigationLabel = 'Announcements';

    protected static ?string $modelLabel = 'Announcement';

    protected static ?string $pluralModelLabel = 'Announcements';

    protected static ?int $navigationSort = 2;

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

                        Textarea::make('message')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        TextInput::make('icon')
                            ->label('Icon')
                            ->placeholder('📢')
                            ->helperText('Emoji or icon character')
                            ->maxLength(10)
                            ->columnSpan(1),

                        Select::make('type')
                            ->options([
                                'info' => 'Info',
                                'success' => 'Success',
                                'warning' => 'Warning',
                                'error' => 'Error',
                            ])
                            ->default('info')
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Section::make('Display Settings')
                    ->schema([
                        Select::make('position')
                            ->options([
                                'top' => 'Top',
                                'bottom' => 'Bottom',
                            ])
                            ->default('top')
                            ->required(),

                        Select::make('color')
                            ->options([
                                'blue' => 'Blue',
                                'green' => 'Green',
                                'yellow' => 'Yellow',
                                'red' => 'Red',
                                'purple' => 'Purple',
                                'gray' => 'Gray',
                            ])
                            ->default('blue'),

                        TextInput::make('priority')
                            ->label('Priority')
                            ->numeric()
                            ->default(0)
                            ->helperText('Higher numbers appear first'),

                        CheckboxList::make('pages')
                            ->label('Show on Pages')
                            ->options([
                                'home' => 'Home Page',
                                'login' => 'Login Page',
                                'register' => 'Register Page',
                                'dashboard' => 'Dashboard',
                            ])
                            ->default(['home'])
                            ->columns(2),
                    ])
                    ->columns(2),

                Section::make('Visibility')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                        Toggle::make('is_sticky')
                            ->label('Sticky')
                            ->helperText('Stays fixed at top/bottom'),

                        Toggle::make('is_dismissible')
                            ->label('Dismissible')
                            ->helperText('Users can close the announcement')
                            ->default(true),

                        DateTimePicker::make('start_date')
                            ->label('Start Date')
                            ->helperText('When to start showing'),

                        DateTimePicker::make('end_date')
                            ->label('End Date')
                            ->helperText('When to stop showing'),
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

                BadgeColumn::make('type')
                    ->colors([
                        'info' => 'blue',
                        'success' => 'green',
                        'warning' => 'yellow',
                        'error' => 'red',
                    ]),

                BadgeColumn::make('position')
                    ->colors([
                        'top' => 'success',
                        'bottom' => 'warning',
                    ]),

                BadgeColumn::make('color')
                    ->colors([
                        'blue' => 'blue',
                        'green' => 'green',
                        'yellow' => 'yellow',
                        'red' => 'red',
                        'purple' => 'purple',
                        'gray' => 'gray',
                    ]),

                TextColumn::make('priority')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                IconColumn::make('is_sticky')
                    ->label('Sticky')
                    ->boolean(),

                IconColumn::make('is_dismissible')
                    ->label('Dismissible')
                    ->boolean(),

                TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'info' => 'Info',
                        'success' => 'Success',
                        'warning' => 'Warning',
                        'error' => 'Error',
                    ]),

                SelectFilter::make('position')
                    ->options([
                        'top' => 'Top',
                        'bottom' => 'Bottom',
                    ]),

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
            ->defaultSort('priority', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('priority', 'desc');
    }
}
