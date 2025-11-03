<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Statistic extends Model
{
    protected $fillable = [
        'label',
        'value',
        'suffix',
        'gradient_from',
        'gradient_to',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function getFullValueAttribute(): string
    {
        return $this->value . ($this->suffix ?? '');
    }
}
