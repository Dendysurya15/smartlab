<?php

use App\Models\LandingPageSetting;

if (!function_exists('landing_setting')) {
    /**
     * Get a landing page setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function landing_setting($key, $default = null)
    {
        return LandingPageSetting::get($key, $default);
    }
}

if (!function_exists('landing_settings_group')) {
    /**
     * Get all settings from a specific group
     *
     * @param string $group
     * @return array
     */
    function landing_settings_group($group)
    {
        return LandingPageSetting::getGroup($group);
    }
}

if (!function_exists('landing_gallery_images')) {
    /**
     * Get gallery images as array
     *
     * @return array
     */
    function landing_gallery_images()
    {
        $gallerySetting = LandingPageSetting::where('key', 'gallery_images')->first();
        if ($gallerySetting && $gallerySetting->value) {
            return json_decode($gallerySetting->value, true) ?? [];
        }
        return [];
    }
}

if (!function_exists('landing_announcements')) {
    /**
     * Get announcements content (legacy - single announcement)
     *
     * @return string
     */
    function landing_announcements()
    {
        return LandingPageSetting::get('announcements_content', 'Tidak ada pengumuman saat ini.');
    }
}

if (!function_exists('landing_announcements_list')) {
    /**
     * Get multiple announcements
     *
     * @return array
     */
    function landing_announcements_list(): array
    {
        $announcements = LandingPageSetting::get('announcements_list', '[]');
        $decoded = json_decode($announcements, true);

        if (!is_array($decoded)) {
            return [];
        }

        // Filter only active announcements and sort by date
        return collect($decoded)
            ->filter(function ($announcement) {
                return isset($announcement['is_active']) && $announcement['is_active'];
            })
            ->sortByDesc('date')
            ->values()
            ->toArray();
    }
}

if (!function_exists('landing_hero_settings')) {
    /**
     * Get hero section settings
     *
     * @return array
     */
    function landing_hero_settings()
    {
        return [
            'title' => LandingPageSetting::get('hero_title', 'SMARTLAB SRS'),
            'subtitle' => LandingPageSetting::get('hero_subtitle', 'Laboratorium Terpercaya untuk Analisis Sampel'),
            'background_image' => LandingPageSetting::get('hero_background_image', ''),
        ];
    }
}

if (!function_exists('landing_hero_images')) {
    /**
     * Get hero images as array for slideshow
     *
     * @return array
     */
    function landing_hero_images()
    {
        $heroSetting = LandingPageSetting::where('key', 'hero_background_images')->first();
        if ($heroSetting && $heroSetting->value) {
            return json_decode($heroSetting->value, true) ?? [];
        }

        // Fallback to single image for backward compatibility
        $singleImage = LandingPageSetting::get('hero_background_image', '');
        if ($singleImage) {
            return [[
                'filename' => $singleImage,
                'url' => \Illuminate\Support\Facades\Storage::url($singleImage),
                'uploaded_at' => now()->toDateTimeString()
            ]];
        }

        return [];
    }
}

if (!function_exists('landing_contact_info')) {
    /**
     * Get contact information
     *
     * @return array
     */
    function landing_contact_info()
    {
        return [
            'phone' => LandingPageSetting::get('contact_phone', '+62 21 1234 5678'),
            'email' => LandingPageSetting::get('contact_email', 'info@smartlab-srs.com'),
            'address' => LandingPageSetting::get('contact_address', 'Jl. Contoh No. 123, Jakarta Selatan, Indonesia'),
        ];
    }
}
