<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // ユーザーがログインしているかつ管理者かチェック
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }
        
        // 管理者でない場合はトップページにリダイレクト
        return redirect()->route('welcome')->with('error', '管理者権限が必要です。');
    }
}
