<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingPageSetting;
use App\Models\FeatureCard;
use App\Models\Statistic;
use App\Models\Announcement;

class LandingPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hero Section Settings
        $heroSettings = [
            [
                'key' => 'hero_badge_text',
                'group' => 'hero',
                'label' => 'Hero Badge Text',
                'value' => 'Sample Tracking System',
                'description' => 'Text shown in the badge above the main title',
                'type' => 'text',
                'sort_order' => 1,
            ],
            [
                'key' => 'hero_title',
                'group' => 'hero',
                'label' => 'Hero Title',
                'value' => 'Lacak Progress',
                'description' => 'Main title of the hero section',
                'type' => 'text',
                'sort_order' => 2,
            ],
            [
                'key' => 'hero_title_highlight',
                'group' => 'hero',
                'label' => 'Hero Title Highlight',
                'value' => 'Sampel',
                'description' => 'Highlighted part of the title',
                'type' => 'text',
                'sort_order' => 3,
            ],
            [
                'key' => 'hero_title_end',
                'group' => 'hero',
                'label' => 'Hero Title End',
                'value' => 'Anda',
                'description' => 'Ending part of the title',
                'type' => 'text',
                'sort_order' => 4,
            ],
            [
                'key' => 'hero_subtitle',
                'group' => 'hero',
                'label' => 'Hero Subtitle',
                'value' => 'Pantau status sampel laboratorium secara real-time dengan sistem tracking modern dan efisien.',
                'description' => 'Subtitle text below the main title',
                'type' => 'textarea',
                'sort_order' => 5,
            ],
        ];

        // General Settings
        $generalSettings = [
            [
                'key' => 'site_name',
                'group' => 'general',
                'label' => 'Site Name',
                'value' => 'SmartLab',
                'description' => 'Name of the website',
                'type' => 'text',
                'sort_order' => 1,
            ],
            [
                'key' => 'site_tagline',
                'group' => 'general',
                'label' => 'Site Tagline',
                'value' => 'Modern Laboratory Management',
                'description' => 'Tagline shown in browser title',
                'type' => 'text',
                'sort_order' => 2,
            ],
            [
                'key' => 'site_logo_path',
                'group' => 'general',
                'label' => 'Site Logo Path',
                'value' => 'images/logo_srs_new.png',
                'description' => 'Path to the site logo',
                'type' => 'text',
                'sort_order' => 3,
            ],
        ];

        // Footer Settings
        $footerSettings = [
            [
                'key' => 'footer_text',
                'group' => 'footer',
                'label' => 'Footer Text',
                'value' => 'Copyright © Digital Architect 2023 - Made with ❤️',
                'description' => 'Text shown in the footer',
                'type' => 'text',
                'sort_order' => 1,
            ],
        ];

        // Insert settings
        foreach (array_merge($heroSettings, $generalSettings, $footerSettings) as $setting) {
            LandingPageSetting::create([
                'setting_key' => $setting['key'],
                'setting_value' => $setting['value'],
                'setting_type' => $setting['type'],
                'group_name' => $setting['group'],
                'label' => $setting['label'],
                'description' => $setting['description'],
                'is_active' => true,
            ]);
        }

        // Feature Cards
        $featureCards = [
            [
                'title' => 'Akurasi Tinggi',
                'subtitle' => '99.9% Precision',
                'description' => 'Sistem analisis laboratorium dengan tingkat akurasi tertinggi untuk hasil yang dapat diandalkan.',
                'icon_svg' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'color_from' => '#10b981',
                'color_to' => '#059669',
                'text_color' => '#ffffff',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Real-time Tracking',
                'subtitle' => 'Instant Updates',
                'description' => 'Pantau progress sampel secara real-time dengan notifikasi otomatis setiap ada update.',
                'icon_svg' => 'M13 10V3L4 14h7v7l9-11h-7z',
                'color_from' => '#3b82f6',
                'color_to' => '#1d4ed8',
                'text_color' => '#ffffff',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Layanan 24/7',
                'subtitle' => 'Always Available',
                'description' => 'Sistem tersedia 24 jam sehari, 7 hari seminggu untuk memastikan layanan terbaik.',
                'icon_svg' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'color_from' => '#8b5cf6',
                'color_to' => '#7c3aed',
                'text_color' => '#ffffff',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($featureCards as $card) {
            FeatureCard::create($card);
        }

        // Statistics
        $statistics = [
            [
                'label' => 'Akurasi',
                'value' => '99.9',
                'suffix' => '%',
                'gradient_from' => '#10b981',
                'gradient_to' => '#059669',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'label' => 'Sampel/Hari',
                'value' => '1000',
                'suffix' => '+',
                'gradient_from' => '#3b82f6',
                'gradient_to' => '#1d4ed8',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'label' => 'Klien Puas',
                'value' => '500',
                'suffix' => '+',
                'gradient_from' => '#8b5cf6',
                'gradient_to' => '#7c3aed',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'label' => 'Pengalaman',
                'value' => '10',
                'suffix' => ' Tahun',
                'gradient_from' => '#f59e0b',
                'gradient_to' => '#d97706',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($statistics as $statistic) {
            Statistic::create($statistic);
        }

        // Sample Announcement
        $announcements = [
            [
                'title' => 'Selamat Datang!',
                'message' => 'Sistem tracking sampel laboratorium terbaru telah tersedia. Nikmati pengalaman tracking yang lebih cepat dan akurat!',
                'icon' => '🎉',
                'type' => 'success',
                'position' => 'top',
                'color' => 'green',
                'priority' => 10,
                'is_active' => true,
                'is_sticky' => false,
                'is_dismissible' => true,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create([
                'title' => $announcement['title'],
                'message' => $announcement['message'],
                'icon' => $announcement['icon'],
                'type' => $announcement['type'],
                'position' => $announcement['position'],
                'color' => $announcement['color'],
                'priority' => $announcement['priority'],
                'is_active' => $announcement['is_active'],
                'is_sticky' => $announcement['is_sticky'],
                'is_dismissible' => $announcement['is_dismissible'],
            ]);
        }

        $this->command->info('Landing page data seeded successfully!');
    }
}
