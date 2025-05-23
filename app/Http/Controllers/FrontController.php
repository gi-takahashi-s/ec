<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    /**
     * トップページを表示
     */
    public function index()
    {
        return view('front.index');
    }

    /**
     * サブページを表示
     */
    public function subPage()
    {
        return view('front.sub');
    }
} 