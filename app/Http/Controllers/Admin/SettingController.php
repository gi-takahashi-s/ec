<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * サイト設定画面を表示
     */
    public function index()
    {
        $settings = [
            'site_name' => config('app.name'),
            'tax_rate' => config('ec.tax_rate', 0.1),
            'shipping_fee' => config('ec.shipping_fee', 800),
            'free_shipping_threshold' => config('ec.free_shipping_threshold', 10000),
            'currency' => config('ec.currency', 'JPY'),
            'maintenance_mode' => app()->isDownForMaintenance(),
        ];
        
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * サイト設定を更新
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'tax_rate' => 'required|numeric|min:0|max:1',
            'shipping_fee' => 'required|numeric|min:0',
            'free_shipping_threshold' => 'required|numeric|min:0',
            'currency' => 'required|string|in:JPY,USD,EUR',
            'maintenance_mode' => 'sometimes|boolean',
        ]);
        
        try {
            // アプリケーション名の更新
            $this->updateEnvFile('APP_NAME', '"' . $request->site_name . '"');
            
            // EC設定の更新
            $this->updateEcConfig([
                'tax_rate' => $request->tax_rate,
                'shipping_fee' => $request->shipping_fee,
                'free_shipping_threshold' => $request->free_shipping_threshold,
                'currency' => $request->currency,
            ]);
            
            // メンテナンスモードの切り替え
            if ($request->has('maintenance_mode')) {
                if ($request->maintenance_mode && !app()->isDownForMaintenance()) {
                    Artisan::call('down', ['--render' => 'errors.maintenance']);
                } elseif (!$request->maintenance_mode && app()->isDownForMaintenance()) {
                    Artisan::call('up');
                }
            }
            
            // キャッシュをクリア
            Cache::flush();
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            
            return redirect()->route('admin.settings')
                ->with('success', 'サイト設定を更新しました。');
        } catch (\Exception $e) {
            Log::error('サイト設定の更新に失敗しました: ' . $e->getMessage());
            
            return redirect()->route('admin.settings')
                ->with('error', 'サイト設定の更新に失敗しました: ' . $e->getMessage());
        }
    }

    /**
     * .env ファイルの値を更新
     */
    private function updateEnvFile($key, $value)
    {
        $path = base_path('.env');
        
        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            // 既存のキーを更新
            if (strpos($content, "{$key}=") !== false) {
                $content = preg_replace("/{$key}=.*/", "{$key}={$value}", $content);
            } else {
                // キーが存在しない場合は追加
                $content .= "\n{$key}={$value}";
            }
            
            file_put_contents($path, $content);
        }
    }

    /**
     * EC設定を更新
     */
    private function updateEcConfig($settings)
    {
        $path = config_path('ec.php');
        
        // EC設定ファイルがない場合は作成
        if (!file_exists($path)) {
            $content = "<?php\n\nreturn [\n";
            
            foreach ($settings as $key => $value) {
                $content .= "    '{$key}' => {$value},\n";
            }
            
            $content .= "];\n";
            
            file_put_contents($path, $content);
        } else {
            // 既存の設定ファイルを読み込み
            $config = include($path);
            
            // 新しい設定をマージ
            $newConfig = array_merge($config, $settings);
            
            // ファイルに書き戻し
            $content = "<?php\n\nreturn [\n";
            
            foreach ($newConfig as $key => $value) {
                if (is_string($value) && !is_numeric($value)) {
                    $content .= "    '{$key}' => '{$value}',\n";
                } else {
                    $content .= "    '{$key}' => {$value},\n";
                }
            }
            
            $content .= "];\n";
            
            file_put_contents($path, $content);
        }
    }
}
