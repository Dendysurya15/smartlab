<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LandingPageSetting;

class LandingPageSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = [
            // Hero Section
            [
                'key' => 'hero_title',
                'value' => 'SMARTLAB SRS',
                'group' => 'hero',
                'type' => 'text',
                'label' => 'Hero Title',
                'description' => 'Main title displayed in hero section',
                'placeholder' => 'Enter hero title',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'hero_subtitle',
                'value' => 'Laboratorium Terpercaya untuk Analisis Sampel',
                'group' => 'hero',
                'type' => 'textarea',
                'label' => 'Hero Subtitle',
                'description' => 'Subtitle text below the main title',
                'placeholder' => 'Enter hero subtitle',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'key' => 'hero_background_image',
                'value' => '',
                'group' => 'hero',
                'type' => 'image',
                'label' => 'Hero Background Image',
                'description' => 'Background image for hero section',
                'placeholder' => 'Upload background image',
                'sort_order' => 3,
                'is_active' => true,
            ],

            // General Settings
            [
                'key' => 'site_name',
                'value' => 'SMARTLAB SRS',
                'group' => 'general',
                'type' => 'text',
                'label' => 'Site Name',
                'description' => 'Name of the website',
                'placeholder' => 'Enter site name',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'site_description',
                'value' => 'Laboratorium analisis sampel yang terpercaya dan profesional',
                'group' => 'general',
                'type' => 'textarea',
                'label' => 'Site Description',
                'description' => 'Brief description of the website',
                'placeholder' => 'Enter site description',
                'sort_order' => 2,
                'is_active' => true,
            ],

            // Features Section
            [
                'key' => 'features_title',
                'value' => 'Layanan Kami',
                'group' => 'features',
                'type' => 'text',
                'label' => 'Features Title',
                'description' => 'Title for features section',
                'placeholder' => 'Enter features title',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'features_description',
                'value' => 'Kami menyediakan berbagai layanan analisis laboratorium yang komprehensif',
                'group' => 'features',
                'type' => 'textarea',
                'label' => 'Features Description',
                'description' => 'Description for features section',
                'placeholder' => 'Enter features description',
                'sort_order' => 2,
                'is_active' => true,
            ],

            // Contact Information
            [
                'key' => 'contact_phone',
                'value' => '+62 21 1234 5678',
                'group' => 'contact',
                'type' => 'text',
                'label' => 'Contact Phone',
                'description' => 'Primary contact phone number',
                'placeholder' => 'Enter phone number',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@smartlab-srs.com',
                'group' => 'contact',
                'type' => 'email',
                'label' => 'Contact Email',
                'description' => 'Primary contact email address',
                'placeholder' => 'Enter email address',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'key' => 'contact_address',
                'value' => 'Jl. Contoh No. 123, Jakarta Selatan, Indonesia',
                'group' => 'contact',
                'type' => 'textarea',
                'label' => 'Contact Address',
                'description' => 'Physical address of the laboratory',
                'placeholder' => 'Enter full address',
                'sort_order' => 3,
                'is_active' => true,
            ],

            // Footer Settings
            [
                'key' => 'footer_copyright',
                'value' => '© 2024 SMARTLAB SRS. All rights reserved.',
                'group' => 'footer',
                'type' => 'text',
                'label' => 'Footer Copyright',
                'description' => 'Copyright text in footer',
                'placeholder' => 'Enter copyright text',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'footer_description',
                'value' => 'Laboratorium analisis sampel terpercaya dengan teknologi terdepan',
                'group' => 'footer',
                'type' => 'textarea',
                'label' => 'Footer Description',
                'description' => 'Brief description in footer',
                'placeholder' => 'Enter footer description',
                'sort_order' => 2,
                'is_active' => true,
            ],

            // Gallery
            [
                'key' => 'gallery_title',
                'value' => 'Galeri Laboratorium',
                'group' => 'gallery',
                'type' => 'text',
                'label' => 'Gallery Title',
                'description' => 'Title for gallery section',
                'placeholder' => 'Enter gallery title',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'gallery_images',
                'value' => '[]',
                'group' => 'gallery',
                'type' => 'text',
                'label' => 'Gallery Images',
                'description' => 'JSON array of gallery images',
                'placeholder' => 'Gallery images will be managed through the interface',
                'sort_order' => 2,
                'is_active' => true,
            ],

            // Announcements
            [
                'key' => 'announcements_title',
                'value' => 'Pengumuman',
                'group' => 'announcements',
                'type' => 'text',
                'label' => 'Announcements Title',
                'description' => 'Title for announcements section',
                'placeholder' => 'Enter announcements title',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'announcements_content',
                'value' => 'Tidak ada pengumuman saat ini.',
                'group' => 'announcements',
                'type' => 'textarea',
                'label' => 'Announcements Content',
                'description' => 'Main announcements content',
                'placeholder' => 'Enter announcements content',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($defaultSettings as $setting) {
            LandingPageSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
