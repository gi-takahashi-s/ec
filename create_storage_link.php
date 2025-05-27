<?php

// アプリケーションのベースパスを設定
$basePath = __DIR__;

// ストレージリンクの作成
$target = $basePath . '/storage/app/public';
$link = $basePath . '/public/storage';

// 既存のリンクを削除（存在する場合）
if (is_link($link)) {
    unlink($link);
}

// シンボリックリンクを作成
if (symlink($target, $link)) {
    echo "ストレージリンクが正常に作成されました。\n";
    echo "リンク先: " . $link . "\n";
    echo "ターゲット: " . $target . "\n";
} else {
    echo "エラー: ストレージリンクの作成に失敗しました。\n";
    echo "パーミッションを確認してください。\n";
}

// パーミッションの設定
$directories = [
    $basePath . '/storage',
    $basePath . '/storage/app',
    $basePath . '/storage/app/public',
    $basePath . '/storage/app/public/categories',
    $basePath . '/public/storage'
];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        chmod($dir, 0775);
        echo "ディレクトリのパーミッションを設定しました: " . $dir . "\n";
    }
}

// 画像ファイルのパーミッションを設定
$imageFiles = glob($basePath . '/storage/app/public/categories/*.*');
foreach ($imageFiles as $file) {
    chmod($file, 0644);
    echo "ファイルのパーミッションを設定しました: " . $file . "\n";
}

echo "\n処理が完了しました。\n";