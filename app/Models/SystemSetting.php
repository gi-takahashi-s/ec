<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'encrypted'
    ];

    protected $casts = [
        'encrypted' => 'boolean',
    ];

    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_JSON = 'json';
    const TYPE_ARRAY = 'array';

    const GROUP_SECURITY = 'security';
    const GROUP_ADMIN = 'admin';
    const GROUP_FRONTEND = 'frontend';
    const GROUP_SYSTEM = 'system';

    public static function getValue($key, $default = null)
    {
        $cacheKey = "system_setting.{$key}";
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }
            
            $value = $setting->encrypted ? Crypt::decrypt($setting->value) : $setting->value;
            
            return self::castValue($value, $setting->type);
        });
    }

    public static function setValue($key, $value, $type = self::TYPE_STRING, $group = self::GROUP_SYSTEM, $description = null, $encrypted = false)
    {
        $stringValue = self::convertToString($value, $type);
        
        if ($encrypted) {
            $stringValue = Crypt::encrypt($stringValue);
        }
        
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $stringValue,
                'type' => $type,
                'group' => $group,
                'description' => $description,
                'encrypted' => $encrypted
            ]
        );
        
        Cache::forget("system_setting.{$key}");
        
        return $setting;
    }

    protected static function castValue($value, $type)
    {
        switch ($type) {
            case self::TYPE_BOOLEAN:
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case self::TYPE_INTEGER:
                return (int) $value;
            case self::TYPE_JSON:
                return json_decode($value, true);
            case self::TYPE_ARRAY:
                return is_string($value) ? json_decode($value, true) : $value;
            default:
                return $value;
        }
    }

    protected static function convertToString($value, $type)
    {
        switch ($type) {
            case self::TYPE_BOOLEAN:
                return $value ? '1' : '0';
            case self::TYPE_JSON:
            case self::TYPE_ARRAY:
                return json_encode($value);
            default:
                return (string) $value;
        }
    }
}
