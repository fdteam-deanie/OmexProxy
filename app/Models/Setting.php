<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null, $group = 'default')
    {
        $setting = self::where('key', $key)
            ->where('group', $group)
            ->first();

        if(!$setting) {
            return $default;
        }

        return $setting->value;
    }

    public static function set(string $key, $value, $group = 'default')
    {
        $setting = self::where('key', $key)
            ->where('group', $group)
            ->first();

        if(!$setting) {
            $setting = new self();
            $setting->group = $group;
            $setting->key = $key;
        }

        $setting->value = $value;
        $setting->save();

        return $setting;
    }
}
