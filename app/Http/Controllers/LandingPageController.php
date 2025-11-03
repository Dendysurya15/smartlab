<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\LandingPageSetting;
use App\Models\FeatureCard;
use App\Models\Statistic;
use Illuminate\Http\Request;
use App\Models\GalleryImage;

class LandingPageController extends Controller
{
    public function index()
    {
        // Get all active gallery images grouped by position
        $galleryImages = collect();
        try {
            $galleryImages = GalleryImage::active()
                ->with('category')
                ->ordered()
                ->get()
                ->groupBy('position');
        } catch (\Exception $e) {
            // If gallery images table doesn't exist yet, use empty collection
            $galleryImages = collect();
        }

        // Get carousel images for hero section
        $carouselImages = collect();
        try {
            $carouselImages = GalleryImage::active()
                ->carousel()
                ->ordered()
                ->get();
        } catch (\Exception $e) {
            // If gallery images table doesn't exist yet, use empty collection
            $carouselImages = collect();
        }

        // Get active announcements for home page
        $announcements = collect();
        try {
            $announcements = Announcement::active()
                ->forPage('home')
                ->byPriority()
                ->get();
        } catch (\Exception $e) {
            // If announcements table doesn't exist yet, use empty collection
            $announcements = collect();
        }

        // Get page settings with fallback defaults
        $settings = [
            'hero' => $this->getSettingsWithDefaults('hero', [
                'hero_badge_text' => 'Sample Tracking System',
                'hero_title' => 'Lacak Progress',
                'hero_title_highlight' => 'Sampel',
                'hero_title_end' => 'Anda',
                'hero_subtitle' => 'Pantau status sampel laboratorium secara real-time dengan sistem tracking modern dan efisien.',
            ]),
            'general' => $this->getSettingsWithDefaults('general', [
                'site_name' => 'SmartLab',
                'site_tagline' => 'Modern Laboratory Management',
                'site_logo_path' => 'images/logo_srs_new.png',
            ]),
            'footer' => $this->getSettingsWithDefaults('footer', [
                'footer_text' => 'Copyright © Digital Architect 2023 - Made with ❤️',
            ]),
        ];

        // Get feature cards
        $featureCards = collect();
        try {
            $featureCards = FeatureCard::active()
                ->ordered()
                ->get();
        } catch (\Exception $e) {
            // If feature cards table doesn't exist yet, use empty collection
            $featureCards = collect();
        }

        // Get statistics
        $statistics = collect();
        try {
            $statistics = Statistic::active()
                ->ordered()
                ->get();
        } catch (\Exception $e) {
            // If statistics table doesn't exist yet, use empty collection
            $statistics = collect();
        }

        return view('layouts.authentication', compact(
            'galleryImages',
            'carouselImages',
            'announcements',
            'settings',
            'featureCards',
            'statistics'
        ));
    }

    /**
     * Get settings with default fallbacks
     */
    private function getSettingsWithDefaults(string $group, array $defaults): array
    {
        try {
            $settings = LandingPageSetting::getGroup($group);
            return array_merge($defaults, $settings);
        } catch (\Exception $e) {
            // If settings table doesn't exist yet, return defaults
            return $defaults;
        }
    }
}
