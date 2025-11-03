<?php

namespace App\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\LandingPageSetting;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\Action;

class LandingPageSettings extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms, WithFileUploads;



    public function table(Table $table): Table
    {
        return $table
            ->query(
                LandingPageSetting::query()
                    ->whereIn('key', [
                        'hero_title',
                        'site_name',
                        'features_title',
                        'contact_phone',
                        'footer_copyright',
                        'gallery_title',
                        'announcements_title'
                    ])
            )
            ->columns([
                TextColumn::make('group')
                    ->label('Category')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'hero' => 'info',
                        'general' => 'success',
                        'features' => 'warning',
                        'contact' => 'primary',
                        'footer' => 'gray',
                        'gallery' => 'secondary',
                        'announcements' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('label')
                    ->label('Section')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('value')
                    ->label('Current Value')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'text' => 'gray',
                        'textarea' => 'info',
                        'image' => 'success',
                        'boolean' => 'warning',
                        default => 'gray',
                    }),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->options([
                        'hero' => 'Hero',
                        'general' => 'General',
                        'features' => 'Features',
                        'contact' => 'Contact',
                        'footer' => 'Footer',
                        'gallery' => 'Gallery',
                        'announcements' => 'Announcements',
                    ]),
                SelectFilter::make('type')
                    ->options([
                        'text' => 'Text',
                        'textarea' => 'Textarea',
                        'image' => 'Image',
                        'boolean' => 'Boolean',
                    ]),
                SelectFilter::make('is_active')
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ]),
            ])
            ->actions([
                // Hero Section Management
                Action::make('manage_hero')
                    ->label('Manage Hero')
                    ->form(formHero())
                    ->closeModalByClickingAway(false)
                    ->icon('heroicon-o-home')
                    ->color('info')
                    ->visible(fn($record): bool => $record->key === 'hero_title')
                    ->fillForm(function (): array {
                        $settings = landing_settings_group('hero');
                        $heroImages = json_decode(landing_setting('hero_background_images', '[]'), true) ?? [];
                        $imagePaths = array_map(fn($image) => $image['filename'], $heroImages);
                        return [
                            'hero_title' => $settings['hero_title'] ?? 'SMARTLAB SRS',
                            'hero_subtitle' => $settings['hero_subtitle'] ?? '',
                            'hero_background_image' => $imagePaths,
                            'hero_button_text' => $settings['hero_button_text'] ?? 'Track Sampel',
                            'hero_button_link' => $settings['hero_button_link'] ?? '#tracking',
                            'hero_active' => filter_var($settings['hero_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
                        ];
                    })
                    ->action(function (array $data) {
                        // Handle hero images upload
                        $heroImages = [];
                        if (isset($data['hero_background_image'])) {
                            foreach ($data['hero_background_image'] as $image) {
                                $heroImages[] = [
                                    'filename' => $image,
                                    'url' => Storage::url($image),
                                    'uploaded_at' => now()->toDateTimeString()
                                ];
                            }
                        }

                        // Update or create hero images setting
                        LandingPageSetting::updateOrCreate(
                            ['key' => 'hero_background_images'],
                            [
                                'value' => json_encode($heroImages),
                                'group' => 'hero',
                                'type' => 'text',
                                'label' => 'Hero Background Images',
                                'description' => 'JSON array of hero background images for slideshow',
                                'is_active' => $data['hero_active'] ?? true,
                                'sort_order' => 2,
                            ]
                        );

                        // Update other hero settings
                        $heroSettings = [
                            'hero_title' => $data['hero_title'] ?? 'SMARTLAB SRS',
                            'hero_subtitle' => $data['hero_subtitle'] ?? '',
                            'hero_button_text' => $data['hero_button_text'] ?? 'Track Sampel',
                            'hero_button_link' => $data['hero_button_link'] ?? '#tracking',
                        ];

                        foreach ($heroSettings as $key => $value) {
                            LandingPageSetting::updateOrCreate(
                                ['key' => $key],
                                [
                                    'value' => $value,
                                    'group' => 'hero',
                                    'type' => 'text',
                                    'label' => ucwords(str_replace('_', ' ', $key)),
                                    'is_active' => $data['hero_active'] ?? true,
                                    'sort_order' => array_search($key, array_keys($heroSettings)),
                                ]
                            );
                        }
                    }),

                // General Section Management
                Action::make('manage_general')
                    ->label('Manage General')
                    ->form(formGeneral())
                    ->closeModalByClickingAway(false)
                    ->icon('heroicon-o-cog')
                    ->color('success')
                    ->visible(fn($record): bool => $record->key === 'site_name')
                    ->fillForm(function (): array {
                        $settings = landing_settings_group('general');
                        $siteLogo = $settings['site_logo'] ?? null;
                        return [
                            'site_name' => $settings['site_name'] ?? 'SMARTLAB SRS',
                            'site_description' => $settings['site_description'] ?? '',
                            'site_tagline' => $settings['site_tagline'] ?? '',
                            'site_logo' => $siteLogo ? [$siteLogo] : [],
                            'general_active' => filter_var($settings['general_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
                        ];
                    })
                    ->action(function (array $data) {
                        // Update general settings
                        $generalSettings = [
                            'site_name' => $data['site_name'] ?? 'SMARTLAB SRS',
                            'site_description' => $data['site_description'] ?? '',
                            'site_tagline' => $data['site_tagline'] ?? '',
                            'site_logo' => $data['site_logo'] ?? '',
                        ];

                        foreach ($generalSettings as $key => $value) {
                            LandingPageSetting::updateOrCreate(
                                ['key' => $key],
                                [
                                    'value' => $value,
                                    'group' => 'general',
                                    'type' => 'text',
                                    'label' => ucwords(str_replace('_', ' ', $key)),
                                    'is_active' => $data['general_active'] ?? true,
                                    'sort_order' => array_search($key, array_keys($generalSettings)),
                                ]
                            );
                        }
                    }),

                // Features Section Management
                Action::make('manage_features')
                    ->label('Manage Features')
                    ->form(formFeatures())
                    ->closeModalByClickingAway(false)
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->visible(fn($record): bool => $record->key === 'features_title')
                    ->fillForm(function (): array {
                        $settings = landing_settings_group('features');
                        $featuresList = json_decode(landing_setting('features_list', '[]'), true) ?? [];
                        return [
                            'features_title' => $settings['features_title'] ?? 'Layanan Kami',
                            'features_description' => $settings['features_description'] ?? '',
                            'features_list' => $featuresList,
                            'features_active' => filter_var($settings['features_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
                        ];
                    })
                    ->action(function (array $data) {
                        // Update features title and description
                        LandingPageSetting::updateOrCreate(
                            ['key' => 'features_title'],
                            [
                                'value' => $data['features_title'] ?? 'Layanan Kami',
                                'group' => 'features',
                                'type' => 'text',
                                'label' => 'Features Title',
                                'is_active' => $data['features_active'] ?? true,
                                'sort_order' => 0,
                            ]
                        );

                        LandingPageSetting::updateOrCreate(
                            ['key' => 'features_description'],
                            [
                                'value' => $data['features_description'] ?? '',
                                'group' => 'features',
                                'type' => 'text',
                                'label' => 'Features Description',
                                'is_active' => $data['features_active'] ?? true,
                                'sort_order' => 1,
                            ]
                        );

                        // Update features list
                        if (isset($data['features_list'])) {
                            $features = [];
                            foreach ($data['features_list'] as $feature) {
                                $features[] = [
                                    'id' => uniqid(),
                                    'title' => $feature['title'],
                                    'description' => $feature['description'],
                                    'icon' => $feature['icon'] ?? '',
                                    'is_active' => $feature['is_active'],
                                    'created_at' => now()->toISOString()
                                ];
                            }

                            LandingPageSetting::updateOrCreate(
                                ['key' => 'features_list'],
                                [
                                    'value' => json_encode($features),
                                    'group' => 'features',
                                    'type' => 'text',
                                    'label' => 'Features List',
                                    'description' => 'JSON array of multiple features',
                                    'is_active' => $data['features_active'] ?? true,
                                    'sort_order' => 2,
                                ]
                            );
                        }
                    }),

                // Contact Section Management
                Action::make('manage_contact')
                    ->label('Manage Contact')
                    ->form(formContact())
                    ->closeModalByClickingAway(false)
                    ->icon('heroicon-o-phone')
                    ->color('primary')
                    ->visible(fn($record): bool => $record->key === 'contact_phone')
                    ->fillForm(function (): array {
                        $settings = landing_settings_group('contact');
                        return [
                            'contact_phone' => $settings['contact_phone'] ?? '+62 21 1234 5678',
                            'contact_email' => $settings['contact_email'] ?? '',
                            'contact_address' => $settings['contact_address'] ?? '',
                            'contact_whatsapp' => $settings['contact_whatsapp'] ?? '',
                            'contact_instagram' => $settings['contact_instagram'] ?? '',
                            'contact_facebook' => $settings['contact_facebook'] ?? '',
                            'contact_active' => filter_var($settings['contact_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
                        ];
                    })
                    ->action(function (array $data) {
                        // Update contact settings
                        $contactSettings = [
                            'contact_phone' => $data['contact_phone'] ?? '+62 21 1234 5678',
                            'contact_email' => $data['contact_email'] ?? '',
                            'contact_address' => $data['contact_address'] ?? '',
                            'contact_whatsapp' => $data['contact_whatsapp'] ?? '',
                            'contact_instagram' => $data['contact_instagram'] ?? '',
                            'contact_facebook' => $data['contact_facebook'] ?? '',
                        ];

                        foreach ($contactSettings as $key => $value) {
                            LandingPageSetting::updateOrCreate(
                                ['key' => $key],
                                [
                                    'value' => $value,
                                    'group' => 'contact',
                                    'type' => 'text',
                                    'label' => ucwords(str_replace('_', ' ', $key)),
                                    'is_active' => $data['contact_active'] ?? true,
                                    'sort_order' => array_search($key, array_keys($contactSettings)),
                                ]
                            );
                        }
                    }),

                // Footer Section Management
                Action::make('manage_footer')
                    ->label('Manage Footer')
                    ->form(formFooter())
                    ->closeModalByClickingAway(false)
                    ->icon('heroicon-o-document-text')
                    ->color('gray')
                    ->visible(fn($record): bool => $record->key === 'footer_copyright')
                    ->fillForm(function (): array {
                        $settings = landing_settings_group('footer');
                        $footerLinks = json_decode(landing_setting('footer_links', '[]'), true) ?? [];
                        return [
                            'footer_copyright' => $settings['footer_copyright'] ?? '© 2024 SMARTLAB SRS. All rights reserved.',
                            'footer_description' => $settings['footer_description'] ?? '',
                            'footer_company_name' => $settings['footer_company_name'] ?? 'SMARTLAB SRS',
                            'footer_website' => $settings['footer_website'] ?? '',
                            'footer_links' => $footerLinks,
                            'footer_active' => filter_var($settings['footer_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
                        ];
                    })
                    ->action(function (array $data) {
                        // Update footer settings
                        $footerSettings = [
                            'footer_copyright' => $data['footer_copyright'] ?? '© 2024 SMARTLAB SRS. All rights reserved.',
                            'footer_description' => $data['footer_description'] ?? '',
                            'footer_company_name' => $data['footer_company_name'] ?? 'SMARTLAB SRS',
                            'footer_website' => $data['footer_website'] ?? '',
                        ];

                        foreach ($footerSettings as $key => $value) {
                            LandingPageSetting::updateOrCreate(
                                ['key' => $key],
                                [
                                    'value' => $value,
                                    'group' => 'footer',
                                    'type' => 'text',
                                    'label' => ucwords(str_replace('_', ' ', $key)),
                                    'is_active' => $data['footer_active'] ?? true,
                                    'sort_order' => array_search($key, array_keys($footerSettings)),
                                ]
                            );
                        }

                        // Update footer links
                        if (isset($data['footer_links'])) {
                            $footerLinks = [];
                            foreach ($data['footer_links'] as $link) {
                                $footerLinks[] = [
                                    'id' => uniqid(),
                                    'title' => $link['title'],
                                    'url' => $link['url'],
                                    'is_active' => $link['is_active'],
                                    'created_at' => now()->toISOString()
                                ];
                            }

                            LandingPageSetting::updateOrCreate(
                                ['key' => 'footer_links'],
                                [
                                    'value' => json_encode($footerLinks),
                                    'group' => 'footer',
                                    'type' => 'text',
                                    'label' => 'Footer Links',
                                    'description' => 'JSON array of footer links',
                                    'is_active' => $data['footer_active'] ?? true,
                                    'sort_order' => count($footerSettings),
                                ]
                            );
                        }
                    }),

                // Announcements Section Management
                Action::make('manage_announcements')
                    ->label('Manage Announcements')
                    ->form(formAnnouncements())
                    ->closeModalByClickingAway(false)
                    ->icon('heroicon-o-megaphone')
                    ->color('warning')
                    ->visible(fn($record): bool => $record->key === 'announcements_title')
                    ->fillForm(function (): array {
                        $settings = landing_settings_group('announcements');
                        $announcementsList = json_decode(landing_setting('announcements_list', '[]'), true) ?? [];
                        return [
                            'announcements_title' => $settings['announcements_title'] ?? 'Pengumuman',
                            'announcements_description' => $settings['announcements_description'] ?? '',
                            'announcements_list' => $announcementsList,
                            'announcements_active' => filter_var($settings['announcements_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
                        ];
                    })
                    ->action(function (array $data) {
                        // Update announcements title
                        LandingPageSetting::updateOrCreate(
                            ['key' => 'announcements_title'],
                            [
                                'value' => $data['announcements_title'] ?? 'Pengumuman',
                                'group' => 'announcements',
                                'type' => 'text',
                                'label' => 'Announcements Title',
                                'description' => $data['announcements_description'] ?? '',
                                'is_active' => $data['announcements_active'] ?? true,
                                'sort_order' => 0,
                            ]
                        );

                        // Update announcements list
                        if (isset($data['announcements_list'])) {
                            $announcements = [];
                            foreach ($data['announcements_list'] as $announcement) {
                                $announcements[] = [
                                    'id' => uniqid(),
                                    'title' => $announcement['title'],
                                    'content' => $announcement['content'],
                                    'date' => $announcement['date'],
                                    'is_active' => $announcement['is_active'],
                                    'created_at' => now()->toISOString()
                                ];
                            }

                            LandingPageSetting::updateOrCreate(
                                ['key' => 'announcements_list'],
                                [
                                    'value' => json_encode($announcements),
                                    'group' => 'announcements',
                                    'type' => 'text',
                                    'label' => 'Announcements List',
                                    'description' => 'JSON array of multiple announcements',
                                    'is_active' => $data['announcements_active'] ?? true,
                                    'sort_order' => 1,
                                ]
                            );
                        }
                    }),

                // Gallery Section Management
                Action::make('manage_gallery')
                    ->label('Manage Gallery')
                    ->form(formGallery())
                    ->closeModalByClickingAway(false)
                    ->icon('heroicon-o-photo')
                    ->color('success')
                    ->visible(fn($record): bool => $record->key === 'gallery_title')
                    ->fillForm(function (): array {
                        $settings = landing_settings_group('gallery');
                        $galleryImages = json_decode(landing_setting('gallery_images', '[]'), true) ?? [];
                        $imagePaths = array_map(fn($image) => $image['filename'], $galleryImages);
                        return [
                            'gallery_title' => $settings['gallery_title'] ?? 'Gallery Laboratorium',
                            'gallery_description' => $settings['gallery_description'] ?? '',
                            'gallery_images' => $imagePaths,
                            'gallery_active' => filter_var($settings['gallery_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
                        ];
                    })
                    ->action(function (array $data) {
                        // Handle gallery images upload
                        $galleryImages = [];
                        if (isset($data['gallery_images'])) {
                            foreach ($data['gallery_images'] as $image) {
                                $galleryImages[] = [
                                    'filename' => $image,
                                    'url' => Storage::url($image),
                                    'uploaded_at' => now()->toDateTimeString()
                                ];
                            }
                        }

                        // Update or create gallery setting
                        LandingPageSetting::updateOrCreate(
                            ['key' => 'gallery_images'],
                            [
                                'value' => json_encode($galleryImages),
                                'group' => 'gallery',
                                'type' => 'text',
                                'label' => 'Gallery Images',
                                'description' => $data['gallery_description'] ?? '',
                                'is_active' => $data['gallery_active'] ?? true,
                                'sort_order' => 1,
                            ]
                        );

                        // Update gallery title
                        LandingPageSetting::updateOrCreate(
                            ['key' => 'gallery_title'],
                            [
                                'value' => $data['gallery_title'] ?? 'Gallery Laboratorium',
                                'group' => 'gallery',
                                'type' => 'text',
                                'label' => 'Gallery Title',
                                'description' => $data['gallery_description'] ?? '',
                                'is_active' => $data['gallery_active'] ?? true,
                                'sort_order' => 0,
                            ]
                        );
                    }),
            ])
            ->bulkActions([
                BulkAction::make('delete')
                    ->label('Delete Selected')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn(Collection $records) => $records->each->delete()),
            ])
            ->defaultSort('group')
            ->defaultSort('sort_order')
            ->reorderable('sort_order');
    }

    public function render()
    {
        return view('livewire.landing-page-settings');
    }
}
