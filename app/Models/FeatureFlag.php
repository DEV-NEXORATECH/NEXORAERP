<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureFlag extends Model
{
    protected $fillable = [
        'key',
        'label',
        'module',
        'description',
        'is_enabled',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    private static array $cache = [];
    private static array $moduleCache = [];

    public static function isEnabled(string $key, bool $default = true): bool
    {
        if (! array_key_exists($key, static::$cache)) {
            $value = static::query()->where('key', $key)->value('is_enabled');
            static::$cache[$key] = $value !== null ? (bool) $value : $default;
        }

        return static::$cache[$key];
    }

    public static function flushCache(): void
    {
        static::$cache = [];
        static::$moduleCache = [];
    }

    public static function enabledKeys(): array
    {
        return static::query()
            ->where('is_enabled', true)
            ->pluck('key')
            ->all();
    }

    public static function moduleEnabled(string $module, bool $default = true): bool
    {
        if (! array_key_exists($module, static::$moduleCache)) {
            static::$moduleCache[$module] = static::query()
                ->where('module', $module)
                ->where('is_enabled', true)
                ->exists() || static::query()->where('module', $module)->count() === 0;
        }

        return static::$moduleCache[$module];
    }
}
