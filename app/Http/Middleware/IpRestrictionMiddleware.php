<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class IpRestrictionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $type  'admin' or 'frontend'
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $type = 'frontend'): Response
    {
        $clientIp = $this->getClientIp($request);
        
        // デバッグログ追加
        Log::info('IP Restriction Middleware Called', [
            'ip' => $clientIp,
            'type' => $type,
            'url' => $request->fullUrl(),
        ]);
        
        // IP制限設定を取得
        $allowList = Config::get("security.{$type}_ip_allow_list", []);
        $denyList = Config::get("security.{$type}_ip_deny_list", []);
        
        // デバッグログ追加
        Log::info('IP Restriction Lists', [
            'allowList' => $allowList,
            'denyList' => $denyList,
            'type' => $type,
        ]);
        
        // 拒否リストチェック（優先）
        if (!empty($denyList) && $this->isIpInList($clientIp, $denyList)) {
            return $this->accessDeniedResponse($request, $clientIp, 'denied', $type);
        }
        
        // 許可リストチェック
        // 許可リストが設定されている場合のみ制限を適用
        if (!empty($allowList)) {
            if (!$this->isIpInList($clientIp, $allowList)) {
                return $this->accessDeniedResponse($request, $clientIp, 'not_allowed', $type);
            }
        }
        // 許可リストが空の場合は、すべてのIPを許可（制限なし）
        
        Log::info('IP Access Granted', [
            'ip' => $clientIp,
            'type' => $type,
        ]);
        
        return $next($request);
    }

    /**
     * クライアントIPアドレスを取得
     */
    private function getClientIp(Request $request): string
    {
        // プロキシ経由の場合を考慮
        $ipHeaders = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_X_FORWARDED_FOR',      // 標準的なプロキシ
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'               // 直接接続
        ];

        foreach ($ipHeaders as $header) {
            if ($request->server($header)) {
                $ip = trim(strtok($request->server($header), ','));
                if ($this->isValidIp($ip)) {
                    return $ip;
                }
            }
        }

        return $request->ip() ?? '0.0.0.0';
    }

    /**
     * IPアドレスがリストに含まれているかチェック
     */
    private function isIpInList(string $ip, array $ipList): bool
    {
        foreach ($ipList as $allowedIp) {
            if ($this->matchIp($ip, $allowedIp)) {
                return true;
            }
        }
        return false;
    }

    /**
     * IPアドレスとパターンをマッチング
     */
    private function matchIp(string $ip, string $pattern): bool
    {
        // 完全一致
        if ($ip === $pattern) {
            return true;
        }

        // CIDR記法の場合
        if (strpos($pattern, '/') !== false) {
            return $this->ipInCidr($ip, $pattern);
        }

        return false;
    }

    /**
     * IPアドレスがCIDRブロックに含まれているかチェック
     */
    private function ipInCidr(string $ip, string $cidr): bool
    {
        [$subnet, $mask] = explode('/', $cidr);
        
        if (!$this->isValidIp($ip) || !$this->isValidIp($subnet)) {
            return false;
        }
        
        $mask = (int) $mask;
        if ($mask < 0 || $mask > 32) {
            return false;
        }

        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $maskLong = -1 << (32 - $mask);

        return ($ipLong & $maskLong) === ($subnetLong & $maskLong);
    }

    /**
     * 有効なIPアドレスかチェック
     */
    private function isValidIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
    }

    /**
     * アクセス拒否レスポンス
     */
    private function accessDeniedResponse(Request $request, string $clientIp, string $reason, string $type): Response
    {
        // ログに記録
        Log::warning('IP Access Denied', [
            'ip' => $clientIp,
            'url' => $request->fullUrl(),
            'user_agent' => $request->userAgent(),
            'reason' => $reason,
            'type' => $type,
            'timestamp' => now(),
        ]);

        // AJAX リクエストの場合
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Access denied from your IP address.',
                'ip' => $clientIp,
                'type' => $type
            ], 403);
        }

        // 通常のHTTPリクエストの場合
        return response()->view('errors.ip-restricted', [
            'ip' => $clientIp,
            'reason' => $reason,
            'type' => $type,
            'message' => $type === 'admin' ? 
                '管理画面へのアクセスが制限されています。' : 
                'このサイトへのアクセスが制限されています。'
        ], 403);
    }
}
