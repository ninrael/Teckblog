<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'type',
        'category_id',
        'tag_id',
        'page_id',
        'parent_id',
        'order',
        'is_active',
        'target',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    public function getFinalUrlAttribute(): string
    {
        if ($this->type === 'category' && $this->category) {
            return route('categories.show', $this->category->slug);
        }
        
        if ($this->type === 'tag' && $this->tag) {
            return route('tags.show', $this->tag->slug);
        }
        
        if ($this->type === 'page' && $this->page) {
            return route('pages.show', $this->page->slug);
        }
        
        return $this->url;
    }
}
