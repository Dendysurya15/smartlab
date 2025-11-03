<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LandingPageSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'label',
        'description',
        'placeholder',
        'validation_rules',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)
                ->where('is_active', true)
                ->first();

            return $setting ? $setting->value : $default;
        });
    }

    public static function getGroup(string $group): array
    {
        return Cache::remember("settings_group_{$group}", 3600, function () use ($group) {
            return static::where('group', $group)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'is_active' => true]
        );
    }

    public static function getAllSettings(): array
    {
        return Cache::remember("all_settings", 3600, function () {
            return static::where('is_active', true)
                ->orderBy('group')
                ->orderBy('sort_order')
                ->get()
                ->groupBy('group')
                ->map(function ($settings) {
                    return $settings->pluck('value', 'key')->toArray();
                })
                ->toArray();
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::flush();
        });

        static::deleted(function () {
            Cache::flush();
        });
    }
}
