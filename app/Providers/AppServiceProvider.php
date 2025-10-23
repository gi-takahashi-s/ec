<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use App\Services\ShopSettingService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // カスタムポートを使用する環境でURLが正しく生成されるよう設定
        if (str_contains(env('APP_URL'), ':8025')) {
            URL::forceRootUrl(env('APP_URL'));
        }

        // メール差出人名をショップ設定から動的に設定
        try {
            $basicInfo = ShopSettingService::getBasicInfo();
            $shopName = $basicInfo['shop_name'] ?? 'ECショップ';
            Config::set('mail.from.name', $shopName);
        } catch (\Exception $e) {
            // データベース接続エラーなどの場合はデフォルト値を使用
            Config::set('mail.from.name', 'ECショップ');
        }
    }
}
