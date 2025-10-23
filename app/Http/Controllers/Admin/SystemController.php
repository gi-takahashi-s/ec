<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class SystemController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * システム情報画面（動線ページ）
     */
    public function info()
    {
        return view('admin.system.info');
    }

    /**
     * システム情報詳細画面
     */
    public function infoDetails()
    {
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_version' => $this->getDatabaseVersion(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'timezone' => config('app.timezone'),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
            'mail_driver' => config('mail.default'),
            'storage_disk' => config('filesystems.default'),
            'app_url' => config('app.url'),
            'maintenance_mode' => app()->isDownForMaintenance(),
        ];

        $diskUsage = $this->getDiskUsage();
        $memoryUsage = $this->getMemoryUsage();

        return view('admin.system.info-details', compact('systemInfo', 'diskUsage', 'memoryUsage'));
    }

    /**
     * メンテナンスモード画面
     */
    public function maintenance()
    {
        // フロント専用メンテナンスモードの状態を取得
        $isMaintenanceMode = SystemSetting::getValue('frontend_maintenance_mode', false);
        $maintenanceData = [];
        
        if ($isMaintenanceMode) {
            $maintenanceData = [
                'message' => SystemSetting::getValue('frontend_maintenance_message', ''),
                'time' => SystemSetting::getValue('frontend_maintenance_start_time', now()->timestamp),
                'end_time' => SystemSetting::getValue('frontend_maintenance_end_time', ''),
                'secret' => SystemSetting::getValue('frontend_maintenance_secret', ''),
            ];
        }

        return view('admin.system.maintenance', compact('isMaintenanceMode', 'maintenanceData'));
    }

    /**
     * メンテナンスモード切り替え
     */
    public function toggleMaintenance(Request $request)
    {
        // デバッグ: メソッドが呼ばれているか確認
        Log::info('=== toggleMaintenance メソッドが呼ばれました ===');
        
        Log::info('メンテナンスモード切り替え開始', [
            'user_id' => auth()->id(),
            'request_data' => $request->all(),
            'current_maintenance_status' => SystemSetting::getValue('frontend_maintenance_mode', false)
        ]);

        $request->validate([
            'action' => 'nullable|string|in:enable,disable,update',
            'message' => 'nullable|string|max:500',
            'end_time' => 'nullable|date|after:now',
            'secret' => 'nullable|string|max:255',
        ]);

        try {
            $isCurrentlyMaintenance = SystemSetting::getValue('frontend_maintenance_mode', false);
            $action = $request->input('action');
            
            // actionパラメータがない場合は従来の動作（切り替え）
            if (!$action) {
                $action = $isCurrentlyMaintenance ? 'disable' : 'enable';
            }
            
            if ($action === 'disable') {
                // メンテナンスモード解除
                Log::info('メンテナンスモード解除を実行');
                
                SystemSetting::setValue('frontend_maintenance_mode', false, SystemSetting::TYPE_BOOLEAN, SystemSetting::GROUP_SYSTEM, 'メンテナンスモード');
                SystemSetting::setValue('frontend_maintenance_message', '', SystemSetting::TYPE_STRING, SystemSetting::GROUP_SYSTEM, 'メンテナンスメッセージ');
                SystemSetting::setValue('frontend_maintenance_end_time', '', SystemSetting::TYPE_STRING, SystemSetting::GROUP_SYSTEM, 'メンテナンス終了予定時刻');
                SystemSetting::setValue('frontend_maintenance_secret', '', SystemSetting::TYPE_STRING, SystemSetting::GROUP_SYSTEM, 'メンテナンスシークレット');
                SystemSetting::setValue('frontend_maintenance_start_time', null, SystemSetting::TYPE_STRING, SystemSetting::GROUP_SYSTEM, 'メンテナンス開始時刻');
                
                Log::info('メンテナンスモード解除完了');
                $message = 'メンテナンスモードを解除しました。';
            } elseif ($action === 'enable') {
                // メンテナンスモード有効化
                Log::info('メンテナンスモード有効化を実行', [
                    'message' => $request->message,
                    'end_time' => $request->end_time,
                    'secret' => $request->secret ? '設定あり' : '設定なし'
                ]);
                
                SystemSetting::setValue('frontend_maintenance_mode', true, SystemSetting::TYPE_BOOLEAN, SystemSetting::GROUP_SYSTEM, 'メンテナンスモード');
                SystemSetting::setValue('frontend_maintenance_message', $request->message ?: 'サイトメンテナンス中です。しばらくお待ちください。', SystemSetting::TYPE_STRING, SystemSetting::GROUP_SYSTEM, 'メンテナンスメッセージ');
                SystemSetting::setValue('frontend_maintenance_end_time', $request->end_time ?: '', SystemSetting::TYPE_STRING, SystemSetting::GROUP_SYSTEM, 'メンテナンス終了予定時刻');
                SystemSetting::setValue('frontend_maintenance_secret', $request->secret ?: '', SystemSetting::TYPE_STRING, SystemSetting::GROUP_SYSTEM, 'メンテナンスシークレット');
                SystemSetting::setValue('frontend_maintenance_start_time', now()->timestamp, SystemSetting::TYPE_INTEGER, SystemSetting::GROUP_SYSTEM, 'メンテナンス開始時刻');
                
                Log::info('メンテナンスモード有効化完了');
                $message = 'メンテナンスモードを有効にしました。';
            } elseif ($action === 'update') {
                // メンテナンス設定更新（メンテナンス中のみ）
                if (!$isCurrentlyMaintenance) {
                    return redirect()->route('admin.system.maintenance')
                        ->with('error', 'メンテナンス中でない場合は設定を更新できません。');
                }
                
                Log::info('メンテナンス設定更新を実行', [
                    'message' => $request->message,
                    'end_time' => $request->end_time,
                ]);
                
                SystemSetting::setValue('frontend_maintenance_message', $request->message ?: 'サイトメンテナンス中です。しばらくお待ちください。', SystemSetting::TYPE_STRING, SystemSetting::GROUP_SYSTEM, 'メンテナンスメッセージ');
                SystemSetting::setValue('frontend_maintenance_end_time', $request->end_time ?: '', SystemSetting::TYPE_STRING, SystemSetting::GROUP_SYSTEM, 'メンテナンス終了予定時刻');
                
                Log::info('メンテナンス設定更新完了');
                $message = 'メンテナンス設定を更新しました。';
            }

            Log::info('メンテナンスモード処理成功', ['message' => $message]);
            return redirect()->route('admin.system.maintenance')
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('メンテナンスモードの処理に失敗: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->route('admin.system.maintenance')
                ->with('error', 'メンテナンスモードの処理に失敗しました: ' . $e->getMessage());
        }
    }

    /**
     * ログイン履歴画面
     */
    public function loginHistory(Request $request)
    {
        $query = LoginHistory::with('user')->latest();

        // フィルタリング
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('logged_in_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('logged_in_at', '<=', $request->date_to);
        }

        $loginHistories = $query->paginate(50);
        $users = User::orderBy('name')->get();

        // 統計情報
        $stats = [
            'total_logins' => LoginHistory::count(),
            'successful_logins' => LoginHistory::successful()->count(),
            'failed_logins' => LoginHistory::failed()->count(),
            'unique_users' => LoginHistory::distinct('user_id')->whereNotNull('user_id')->count(),
            'today_logins' => LoginHistory::whereDate('logged_in_at', today())->count(),
        ];

        return view('admin.system.login-history', compact('loginHistories', 'users', 'stats'));
    }

    /**
     * セキュリティ監視画面
     */
    public function security()
    {
        // セキュリティ関連の統計
        $securityStats = [
            'failed_logins_today' => LoginHistory::failed()->whereDate('logged_in_at', today())->count(),
            'failed_logins_week' => LoginHistory::failed()->where('logged_in_at', '>=', now()->subWeek())->count(),
            'suspicious_ips' => $this->getSuspiciousIPs(),
            'admin_users_count' => User::where('is_admin', true)->count(),
            'total_users_count' => User::count(),
        ];

        // 最近の失敗ログイン
        $recentFailedLogins = LoginHistory::failed()
            ->with('user')
            ->latest()
            ->limit(20)
            ->get();

        // IPアドレス別の失敗回数
        $failedLoginsByIP = LoginHistory::failed()
            ->where('logged_in_at', '>=', now()->subDays(7))
            ->select('ip_address', DB::raw('count(*) as count'))
            ->groupBy('ip_address')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.system.security', compact('securityStats', 'recentFailedLogins', 'failedLoginsByIP'));
    }

    /**
     * セキュリティ設定画面
     */
    public function securitySettings()
    {
        // データベースから設定を取得
        $settings = [
            'admin_url' => SystemSetting::getValue('admin_url', '/admin'),
            'admin_ip_allow_list' => SystemSetting::getValue('admin_ip_allow_list', []),
            'admin_ip_deny_list' => SystemSetting::getValue('admin_ip_deny_list', []),
            'frontend_ip_allow_list' => SystemSetting::getValue('frontend_ip_allow_list', []),
            'frontend_ip_deny_list' => SystemSetting::getValue('frontend_ip_deny_list', []),
        ];

        // 配列を改行区切りの文字列に変換（ビュー表示用）
        $settings['admin_ip_allow_list'] = is_array($settings['admin_ip_allow_list']) 
            ? implode("\n", $settings['admin_ip_allow_list']) : '';
        $settings['admin_ip_deny_list'] = is_array($settings['admin_ip_deny_list']) 
            ? implode("\n", $settings['admin_ip_deny_list']) : '';
        $settings['frontend_ip_allow_list'] = is_array($settings['frontend_ip_allow_list']) 
            ? implode("\n", $settings['frontend_ip_allow_list']) : '';
        $settings['frontend_ip_deny_list'] = is_array($settings['frontend_ip_deny_list']) 
            ? implode("\n", $settings['frontend_ip_deny_list']) : '';

        return view('admin.system.security-settings', compact('settings'));
    }

    /**
     * セキュリティ設定更新
     */
    public function updateSecuritySettings(Request $request)
    {
        $request->validate([
            'admin_url' => 'required|string|max:255|regex:/^\/[a-zA-Z0-9\-_\/]*$/',
            'admin_ip_allow_list' => 'nullable|string',
            'admin_ip_deny_list' => 'nullable|string',
            'frontend_ip_allow_list' => 'nullable|string',
            'frontend_ip_deny_list' => 'nullable|string',
        ]);

        try {
            // IPリストを配列に変換
            $adminAllowList = $this->parseIpList($request->admin_ip_allow_list);
            $adminDenyList = $this->parseIpList($request->admin_ip_deny_list);
            $frontendAllowList = $this->parseIpList($request->frontend_ip_allow_list);
            $frontendDenyList = $this->parseIpList($request->frontend_ip_deny_list);

            // データベースに設定を保存（セキュアな方法）
            SystemSetting::setValue(
                'admin_url', 
                $request->admin_url, 
                SystemSetting::TYPE_STRING, 
                SystemSetting::GROUP_SECURITY,
                '管理画面のURL'
            );

            SystemSetting::setValue(
                'admin_ip_allow_list', 
                $adminAllowList, 
                SystemSetting::TYPE_ARRAY, 
                SystemSetting::GROUP_SECURITY,
                '管理画面アクセス許可IPリスト'
            );

            SystemSetting::setValue(
                'admin_ip_deny_list', 
                $adminDenyList, 
                SystemSetting::TYPE_ARRAY, 
                SystemSetting::GROUP_SECURITY,
                '管理画面アクセス拒否IPリスト'
            );

            SystemSetting::setValue(
                'frontend_ip_allow_list', 
                $frontendAllowList, 
                SystemSetting::TYPE_ARRAY, 
                SystemSetting::GROUP_SECURITY,
                'フロント画面アクセス許可IPリスト'
            );

            SystemSetting::setValue(
                'frontend_ip_deny_list', 
                $frontendDenyList, 
                SystemSetting::TYPE_ARRAY, 
                SystemSetting::GROUP_SECURITY,
                'フロント画面アクセス拒否IPリスト'
            );

            // 動的にConfigを更新（.envファイル書き換えなし）
            Config::set('app.admin_url', $request->admin_url);

            // 設定キャッシュをクリア（設定反映のため）
            Artisan::call('config:clear');
            Artisan::call('route:clear');

            // 監査ログの記録
            Log::info('セキュリティ設定が更新されました', [
                'user_id' => auth()->id(),
                'admin_url' => $request->admin_url,
                'ip_restrictions_updated' => true,
                'timestamp' => now(),
            ]);

            // 新しい管理画面URLでリダイレクト
            $newAdminUrl = ltrim($request->admin_url, '/');
            $redirectUrl = url($newAdminUrl . '/system/security-settings');

            return redirect($redirectUrl)
                ->with('success', 'セキュリティ設定を更新しました。');
        } catch (\Exception $e) {
            Log::error('セキュリティ設定の更新に失敗: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // エラー時は現在の管理画面URLでリダイレクト
            $currentAdminUrl = ltrim(SystemSetting::getValue('admin_url', '/admin'), '/');
            $redirectUrl = url($currentAdminUrl . '/system/security-settings');
            
            return redirect($redirectUrl)
                ->with('error', 'セキュリティ設定の更新に失敗しました: ' . $e->getMessage());
        }
    }

    /**
     * IPリストを解析して配列に変換
     */
    private function parseIpList($ipListString)
    {
        if (empty($ipListString)) {
            return [];
        }

        $ips = array_map('trim', explode("\n", $ipListString));
        $validIps = [];

        foreach ($ips as $ip) {
            if (!empty($ip) && $this->isValidIpOrCidr($ip)) {
                $validIps[] = $ip;
            }
        }

        return $validIps;
    }

    /**
     * IPアドレスまたはCIDR記法の妥当性をチェック
     */
    private function isValidIpOrCidr($ip)
    {
        // CIDR記法の場合
        if (strpos($ip, '/') !== false) {
            list($ipAddr, $mask) = explode('/', $ip, 2);
            return filter_var($ipAddr, FILTER_VALIDATE_IP) && is_numeric($mask) && $mask >= 0 && $mask <= 32;
        }
        
        // 単一IPアドレスの場合
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * データベースバージョンを取得
     */
    private function getDatabaseVersion()
    {
        try {
            $result = DB::select('SELECT VERSION() as version');
            return $result[0]->version ?? 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * ディスク使用量を取得
     */
    private function getDiskUsage()
    {
        $path = base_path();
        $totalBytes = disk_total_space($path);
        $freeBytes = disk_free_space($path);
        $usedBytes = $totalBytes - $freeBytes;

        return [
            'total' => $this->formatBytes($totalBytes),
            'used' => $this->formatBytes($usedBytes),
            'free' => $this->formatBytes($freeBytes),
            'percentage' => round(($usedBytes / $totalBytes) * 100, 2),
        ];
    }

    /**
     * メモリ使用量を取得
     */
    private function getMemoryUsage()
    {
        return [
            'current' => $this->formatBytes(memory_get_usage(true)),
            'peak' => $this->formatBytes(memory_get_peak_usage(true)),
            'limit' => ini_get('memory_limit'),
        ];
    }

    /**
     * バイト数をフォーマット
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * 疑わしいIPアドレスを取得
     */
    private function getSuspiciousIPs()
    {
        return LoginHistory::failed()
            ->where('logged_in_at', '>=', now()->subDays(7))
            ->select('ip_address')
            ->groupBy('ip_address')
            ->havingRaw('COUNT(*) >= 5')
            ->count();
    }
}
