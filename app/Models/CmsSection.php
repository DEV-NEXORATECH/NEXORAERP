<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsSection extends Model
{
    protected $fillable = [
        'key',
        'label',
        'title',
        'subtitle',
        'content',
        'items',
        'image_path',
        'button_label',
        'button_url',
        'is_visible',
        'sort_order',
    ];

    protected $casts = [
        'items' => 'array',
        'is_visible' => 'boolean',
    ];

    public static function ensureDefaultSections(): void
    {
        foreach (self::defaultSections() as $section) {
            self::firstOrCreate(['key' => $section['key']], $section);
        }
    }

    private static function defaultSections(): array
    {
        return [
            [
                'key' => 'hero',
                'label' => 'Hero / Header',
                'title' => null,
                'subtitle' => null,
                'content' => null,
                'items' => [],
                'sort_order' => 10,
                'is_visible' => true,
            ],
            [
                'key' => 'about',
                'label' => 'About / Deskripsi',
                'title' => null,
                'subtitle' => null,
                'content' => null,
                'items' => [],
                'sort_order' => 20,
                'is_visible' => true,
            ],
            [
                'key' => 'services',
                'label' => 'Services / Fitur',
                'title' => null,
                'subtitle' => null,
                'content' => null,
                'items' => [],
                'sort_order' => 30,
                'is_visible' => true,
            ],
            [
                'key' => 'contact',
                'label' => 'Contact / CTA',
                'title' => null,
                'subtitle' => null,
                'content' => null,
                'items' => [],
                'sort_order' => 40,
                'is_visible' => true,
            ],
        ];
    }
}
