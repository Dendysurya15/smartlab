<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class GalleryImage extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'width',
        'height',
        'alt_text',
        'caption',
        'position',
        'grid_area',
        'is_carousel',
        'is_featured',
        'is_active',
        'sort_order',
        'overlay_gradient',
        'badge_text',
        'badge_color',
    ];

    protected $casts = [
        'is_carousel' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'file_size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'sort_order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(GalleryCategory::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCarousel($query)
    {
        return $query->where('is_carousel', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    public function scopeByPosition($query, string $position)
    {
        return $query->where('position', $position);
    }

    public function getImageUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }
}
