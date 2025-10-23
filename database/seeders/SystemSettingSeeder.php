<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // セキュリティ設定のデフォルト値を作成
        SystemSetting::seedSecurityDefaults();

        // 追加のシステム設定
        $additionalSettings = [
            [
                'key' => 'maintenance_mode_enabled',
                'value' => false,
                'type' => SystemSetting::TYPE_BOOLEAN,
                'group' => SystemSetting::GROUP_SYSTEM,
                'description' => 'メンテナンスモードの有効/無効'
            ],
            [
                'key' => 'max_login_attempts',
                'value' => 5,
                'type' => SystemSetting::TYPE_INTEGER,
                'group' => SystemSetting::GROUP_SECURITY,
                'description' => '最大ログイン試行回数'
            ],
            [
                'key' => 'login_lockout_duration',
                'value' => 900,
                'type' => SystemSetting::TYPE_INTEGER,
                'group' => SystemSetting::GROUP_SECURITY,
                'description' => 'ログインロックアウト時間（秒）'
            ],
            [
                'key' => 'session_timeout',
                'value' => 3600,
                'type' => SystemSetting::TYPE_INTEGER,
                'group' => SystemSetting::GROUP_SECURITY,
                'description' => 'セッションタイムアウト時間（秒）'
            ],
            [
                'key' => 'enable_audit_log',
                'value' => true,
                'type' => SystemSetting::TYPE_BOOLEAN,
                'group' => SystemSetting::GROUP_SECURITY,
                'description' => '監査ログの有効/無効'
            ]
        ];

        foreach ($additionalSettings as $setting) {
            SystemSetting::setValue(
                $setting['key'],
                $setting['value'],
                $setting['type'],
                $setting['group'],
                $setting['description']
            );
        }

        $this->command->info('System settings seeded successfully.');
    }
} 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // セキュリティ設定のデフォルト値を作成
        SystemSetting::seedSecurityDefaults();

        // 追加のシステム設定
        $additionalSettings = [
            [
                'key' => 'maintenance_mode_enabled',
                'value' => false,
                'type' => SystemSetting::TYPE_BOOLEAN,
                'group' => SystemSetting::GROUP_SYSTEM,
                'description' => 'メンテナンスモードの有効/無効'
            ],
            [
                'key' => 'max_login_attempts',
                'value' => 5,
                'type' => SystemSetting::TYPE_INTEGER,
                'group' => SystemSetting::GROUP_SECURITY,
                'description' => '最大ログイン試行回数'
            ],
            [
                'key' => 'login_lockout_duration',
                'value' => 900,
                'type' => SystemSetting::TYPE_INTEGER,
                'group' => SystemSetting::GROUP_SECURITY,
                'description' => 'ログインロックアウト時間（秒）'
            ],
            [
                'key' => 'session_timeout',
                'value' => 3600,
                'type' => SystemSetting::TYPE_INTEGER,
                'group' => SystemSetting::GROUP_SECURITY,
                'description' => 'セッションタイムアウト時間（秒）'
            ],
            [
                'key' => 'enable_audit_log',
                'value' => true,
                'type' => SystemSetting::TYPE_BOOLEAN,
                'group' => SystemSetting::GROUP_SECURITY,
                'description' => '監査ログの有効/無効'
            ]
        ];

        foreach ($additionalSettings as $setting) {
            SystemSetting::setValue(
                $setting['key'],
                $setting['value'],
                $setting['type'],
                $setting['group'],
                $setting['description']
            );
        }

        $this->command->info('System settings seeded successfully.');
    }
} 