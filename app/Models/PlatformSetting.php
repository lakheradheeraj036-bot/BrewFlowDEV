<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type'];

    // -------------------------------------------------------------------------
    // Static helpers
    // -------------------------------------------------------------------------

    /**
     * Retrieve a platform setting by key with optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => (bool) $setting->value,
            'integer' => (int)  $setting->value,
            'json'    => json_decode($setting->value, true),
            default   => $setting->value,
        };
    }

    /**
     * Persist (or update) a platform setting.
     */
    public static function set(
        string $key,
        mixed  $value,
        string $group = 'general',
        string $type  = 'string'
    ): void {
        if (is_array($value)) {
            $value = json_encode($value);
            $type  = 'json';
        }

        static::updateOrCreate(
            ['key'   => $key],
            ['value' => $value, 'group' => $group, 'type' => $type]
        );
    }
}
