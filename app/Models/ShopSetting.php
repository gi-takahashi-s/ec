<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ShopSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'group',
        'value',
        'type',
        'description',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    // 設定グループの定数
    const GROUP_BASIC_INFO = 'basic_info';
    const GROUP_SHIPPING = 'shipping';
    const GROUP_LEGAL = 'legal';
    const GROUP_PAYMENT = 'payment';

    // データ型の定数
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_JSON = 'json';

    /**
     * 設定値を取得（型変換付き）
     */
    public function getTypedValue()
    {
        switch ($this->type) {
            case self::TYPE_INTEGER:
                return (int) $this->value;
            case self::TYPE_BOOLEAN:
                return filter_var($this->value, FILTER_VALIDATE_BOOLEAN);
            case self::TYPE_JSON:
                return json_decode($this->value, true);
            default:
                return $this->value;
        }
    }

    /**
     * 設定値を型に応じて保存
     */
    public function setTypedValue($value)
    {
        switch ($this->type) {
            case self::TYPE_JSON:
                $this->value = json_encode($value);
                break;
            case self::TYPE_BOOLEAN:
                $this->value = $value ? '1' : '0';
                break;
            default:
                $this->value = (string) $value;
        }
        return $this;
    }

    /**
     * 設定値を取得（静的メソッド）
     */
    public static function getValue($key, $default = null)
    {
        $cacheKey = "shop_setting_{$key}";
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)
                          ->where('is_active', true)
                          ->first();
            
            return $setting ? $setting->getTypedValue() : $default;
        });
    }

    /**
     * 設定値を更新（静的メソッド）
     */
    public static function setValue($key, $value, $type = self::TYPE_STRING, $group = null, $description = null)
    {
        $setting = self::firstOrNew(['key' => $key]);
        
        if ($group) {
            $setting->group = $group;
        }
        
        if ($description) {
            $setting->description = $description;
        }
        
        $setting->type = $type;
        $setting->setTypedValue($value);
        $setting->is_active = true;
        $setting->save();

        // キャッシュクリア
        Cache::forget("shop_setting_{$key}");
        Cache::forget("shop_settings_group_{$setting->group}");

        return $setting;
    }

    /**
     * グループ別設定取得
     */
    public static function getByGroup($group)
    {
        $cacheKey = "shop_settings_group_{$group}";
        
        return Cache::remember($cacheKey, 3600, function () use ($group) {
            return self::where('group', $group)
                      ->where('is_active', true)
                      ->orderBy('sort_order')
                      ->get()
                      ->mapWithKeys(function ($setting) {
                          return [$setting->key => $setting->getTypedValue()];
                      });
        });
    }

    /**
     * 設定値を一括更新
     */
    public static function updateGroup($group, $settings)
    {
        foreach ($settings as $key => $value) {
            $fullKey = "{$group}.{$key}";
            $setting = self::where('key', $fullKey)->first();
            
            if ($setting) {
                $setting->setTypedValue($value);
                $setting->save();
                
                // キャッシュクリア
                Cache::forget("shop_setting_{$fullKey}");
            }
        }
        
        Cache::forget("shop_settings_group_{$group}");
    }

    /**
     * スコープ: アクティブな設定のみ
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * スコープ: グループ別
     */
    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * 全設定を配列で取得
     */
    public static function getSettings()
    {
        $cacheKey = "shop_settings_all";
        
        return Cache::remember($cacheKey, 3600, function () {
            return self::where('is_active', true)
                      ->get()
                      ->mapWithKeys(function ($setting) {
                          // キーから最後の部分を取得（例: basic_info.shop_name → shop_name）
                          $keyParts = explode('.', $setting->key);
                          $shortKey = end($keyParts);
                          return [$shortKey => $setting->getTypedValue()];
                      })
                      ->toArray();
        });
    }
}
