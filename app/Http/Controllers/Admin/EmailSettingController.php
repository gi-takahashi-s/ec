<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailSetting;
use Illuminate\Http\Request;

class EmailSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * メール設定一覧を表示
     */
    public function index()
    {
        $emailSettings = EmailSetting::orderBy('type')->get()->keyBy('type');
        $allTypes = EmailSetting::getAllTypes();
        
        // 存在しないタイプのデフォルト設定を作成
        foreach ($allTypes as $type => $name) {
            if (!$emailSettings->has($type)) {
                EmailSetting::createDefaults();
                break;
            }
        }
        
        // 再取得
        $emailSettings = EmailSetting::orderBy('type')->get()->keyBy('type');
        
        return view('admin.email-settings.index', compact('emailSettings', 'allTypes'));
    }

    /**
     * 個別メール設定編集画面を表示
     */
    public function edit($type)
    {
        $allTypes = EmailSetting::getAllTypes();
        
        if (!array_key_exists($type, $allTypes)) {
            abort(404);
        }
        
        $emailSetting = EmailSetting::where('type', $type)->first();
        
        // 設定が存在しない場合はデフォルトを作成
        if (!$emailSetting) {
            EmailSetting::createDefaults();
            $emailSetting = EmailSetting::where('type', $type)->first();
        }
        
        return view('admin.email-settings.edit', compact('emailSetting', 'allTypes', 'type'));
    }

    /**
     * 個別メール設定を更新
     */
    public function updateSingle(Request $request, $type)
    {
        $allTypes = EmailSetting::getAllTypes();
        
        if (!array_key_exists($type, $allTypes)) {
            abort(404);
        }
        
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'is_active' => 'boolean',
        ]);

        EmailSetting::updateOrCreate(
            ['type' => $type],
            [
                'subject' => $request->input('subject'),
                'body' => $request->input('body'),
                'is_active' => $request->has('is_active'),
            ]
        );

        return redirect()->route('admin.email-settings.edit', $type)
            ->with('success', 'メール設定を更新しました。');
    }

    /**
     * メール設定の有効/無効を切り替え
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        $type = $request->input('type');
        $isActive = $request->input('is_active');
        $allTypes = EmailSetting::getAllTypes();
        
        if (!array_key_exists($type, $allTypes)) {
            return response()->json(['success' => false, 'message' => '無効なメールタイプです。'], 400);
        }

        try {
            EmailSetting::updateOrCreate(
                ['type' => $type],
                ['is_active' => $isActive]
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * メール設定を更新
     */
    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.subject' => 'required|string|max:255',
            'settings.*.body' => 'required|string',
            'settings.*.is_active' => 'boolean',
        ]);

        foreach ($request->input('settings') as $type => $data) {
            EmailSetting::updateOrCreate(
                ['type' => $type],
                [
                    'subject' => $data['subject'],
                    'body' => $data['body'],
                    'is_active' => isset($data['is_active']) ? true : false,
                ]
            );
        }

        return redirect()->route('admin.email-settings.index')
            ->with('success', 'メール設定を更新しました。');
    }

    /**
     * デフォルト設定をリセット
     */
    public function reset()
    {
        EmailSetting::createDefaults();
        
        return redirect()->route('admin.email-settings.index')
            ->with('success', 'メール設定をデフォルトに戻しました。');
    }
} 