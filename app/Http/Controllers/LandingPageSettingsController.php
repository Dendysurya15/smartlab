<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LandingPageSetting;

class LandingPageSettingsController extends Controller
{
    public function index()
    {
        return view('pages.landing-page-settings');
    }

    public function getSetting($key, $default = null)
    {
        return LandingPageSetting::get($key, $default);
    }

    public function getGroup($group)
    {
        return LandingPageSetting::getGroup($group);
    }

    public function getAllSettings()
    {
        return LandingPageSetting::getAllSettings();
    }
}
