<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * ユーザー一覧を表示
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // 検索フィルタリング
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // 管理者フィルタリング
        if ($request->has('is_admin') && $request->input('is_admin') !== '') {
            $query->where('is_admin', $request->input('is_admin'));
        }
        
        // メール確認フィルタリング
        if ($request->has('email_verified') && $request->input('email_verified') !== '') {
            if ($request->input('email_verified') == 1) {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }
        
        // 並び替え
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $users = $query->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * ユーザー詳細を表示
     */
    public function show(User $user)
    {
        $user->load(['orders' => function($query) {
            $query->orderBy('created_at', 'desc');
        }, 'shippingAddresses']);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * ユーザー編集フォームを表示
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * ユーザー情報を更新
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'is_admin' => 'sometimes|boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->has('is_admin')) {
            $user->is_admin = $request->is_admin;
        }
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'ユーザー情報を更新しました。');
    }

    /**
     * ユーザーを削除
     */
    public function destroy(User $user)
    {
        // 自分自身は削除できないようにする
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', '自分自身を削除することはできません。');
        }
        
        // 関連データの処理はLaravelのカスケード削除に任せる
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'ユーザーを削除しました。');
    }
}
