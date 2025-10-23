<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Models\SystemSetting;

class SystemSettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // データベースが利用可能な場合のみ設定を読み込み
        if ($this->app->runningInConsole() && $this->isMigrationCommand()) {
            return;
        }

        try {
            // システム設定をConfigに反映
            $this->loadSystemSettings();
        } catch (\Exception $e) {
            // データベースアクセスエラーの場合はログに記録（マイグレーション実行前など）
            if (config('app.debug')) {
                Log::debug('SystemSettings not loaded: ' . $e->getMessage());
            }
        }
    }

    /**
     * システム設定をConfigに読み込み
     *
     * @return void
     */
    protected function loadSystemSettings()
    {
        // セキュリティ設定の読み込み
        $adminUrl = SystemSetting::getValue('admin_url', '/admin');
        Config::set('app.admin_url', $adminUrl);

        // ルーティングの動的設定
        $this->configureDynamicRoutes($adminUrl);

        // IP制限設定の読み込み
        $this->loadSecuritySettings();
    }

    /**
     * 動的ルーティングの設定
     *
     * @param string $adminUrl
     * @return void
     */
    protected function configureDynamicRoutes($adminUrl)
    {
        // 管理画面URLをルーティングに反映
        if ($adminUrl && $adminUrl !== '/admin') {
            Route::macro('adminPrefix', function () use ($adminUrl) {
                return ltrim($adminUrl, '/');
            });
        }
    }

    /**
     * セキュリティ設定の読み込み
     *
     * @return void
     */
    protected function loadSecuritySettings()
    {
        $securitySettings = [
            'admin_ip_allow_list' => SystemSetting::getValue('admin_ip_allow_list', []),
            'admin_ip_deny_list' => SystemSetting::getValue('admin_ip_deny_list', []),
            'frontend_ip_allow_list' => SystemSetting::getValue('frontend_ip_allow_list', []),
            'frontend_ip_deny_list' => SystemSetting::getValue('frontend_ip_deny_list', []),
        ];

        foreach ($securitySettings as $key => $value) {
            Config::set("security.{$key}", $value);
        }
    }

    /**
     * マイグレーションコマンドかどうかを判定
     *
     * @return bool
     */
    protected function isMigrationCommand()
    {
        return in_array('migrate', $_SERVER['argv'] ?? []) || 
               in_array('migrate:fresh', $_SERVER['argv'] ?? []) ||
               in_array('migrate:refresh', $_SERVER['argv'] ?? []) ||
               in_array('migrate:reset', $_SERVER['argv'] ?? []) ||
               in_array('migrate:rollback', $_SERVER['argv'] ?? []);
    }
} 