<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\LoginHistory;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // ログイン成功時の履歴記録
        Event::listen(Login::class, function ($event) {
            LoginHistory::recordLogin(
                $event->user,
                request(),
                'success'
            );
        });

        // ログイン失敗時の履歴記録
        Event::listen(Failed::class, function ($event) {
            LoginHistory::recordLogin(
                $event->user,
                request(),
                'failed',
                '認証失敗'
            );
        });

        // ログアウト時の履歴更新
        Event::listen(Logout::class, function ($event) {
            $latestLogin = LoginHistory::where('user_id', $event->user->id)
                ->whereNull('logged_out_at')
                ->latest('logged_in_at')
                ->first();
            
            if ($latestLogin) {
                $latestLogin->recordLogout();
            }
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
