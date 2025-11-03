<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'message',
        'type',
        'icon',
        'color',
        'position',
        'is_dismissible',
        'is_sticky',
        'start_date',
        'end_date',
        'show_on_pages',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'is_dismissible' => 'boolean',
        'is_sticky' => 'boolean',
        'is_active' => 'boolean',
        'priority' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'show_on_pages' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', Carbon::now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::now());
            });
    }

    public function scopeForPage($query, string $page)
    {
        return $query->where(function ($q) use ($page) {
            $q->whereJsonContains('show_on_pages', $page)
                ->orWhereNull('show_on_pages');
        });
    }

    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'desc')->orderBy('created_at', 'desc');
    }
}
