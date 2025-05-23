<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
    }
}
