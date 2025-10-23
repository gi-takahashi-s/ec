<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Log;

class FrontendMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 管理画面は常にアクセス可能
        $adminUrl = ltrim(SystemSetting::getValue('admin_url', '/admin'), '/');
        if ($request->is($adminUrl . '*')) {
            return $next($request);
        }

        // フロント画面のメンテナンス状態をチェック
        $isMaintenanceMode = SystemSetting::getValue('frontend_maintenance_mode', false);
        
        if ($isMaintenanceMode) {
            Log::info('フロント画面メンテナンスモード適用', [
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // シークレットキーでのバイパス機能
            $secret = SystemSetting::getValue('frontend_maintenance_secret', '');
            if ($secret && $request->get('secret') === $secret) {
                Log::info('メンテナンスモードをシークレットキーでバイパス', [
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                ]);
                return $next($request);
            }

            // メンテナンス画面を表示
            $maintenanceData = [
                'message' => SystemSetting::getValue('frontend_maintenance_message', 'サイトメンテナンス中です。しばらくお待ちください。'),
                'time' => SystemSetting::getValue('frontend_maintenance_start_time', now()->timestamp),
                'end_time' => SystemSetting::getValue('frontend_maintenance_end_time', ''),
            ];

            return response()->view('errors.frontend-maintenance', $maintenanceData, 503);
        }

        return $next($request);
    }
}
