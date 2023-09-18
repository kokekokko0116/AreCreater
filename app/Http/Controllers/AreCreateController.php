<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class AreCreateController extends Controller
{
    public function are_create(Request $request)
    {
        // 小数点切り捨て
        $xPos = floor($request->input('xPos'));
        $yPos = floor($request->input('yPos'));
        $angle = floor($request->input('angle'));
        // base64エンコードされた画像データを取得
        $image_data = $request->input('base64image');
        // base64データをデコード
        $image_parts = explode(";base64,", $image_data);
        // イメージタイプを取得
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        // 一時的なファイルを作成
        $tmp_file = tempnam(sys_get_temp_dir(), 'base64img');
        file_put_contents($tmp_file, $image_base64);

        // 一時的なファイルをUploadedFileインスタンスとして扱い、Laravelのバリデーションやファイル処理機能を利用
        $file = new \Illuminate\Http\UploadedFile($tmp_file, uniqid() . '.' . $image_type, 'image/png', null, true);
        $template = Image::make($file);

        $insertImage = Image::make(public_path('image/flyman.png'));
        $insertImage->rotate($angle);

        // 画像を合成 angleは回転角度
        $template->insert($insertImage, 'top-left', $xPos, $yPos);

        // 画像を保存
        $unique = uniqid();
        $template->save(public_path('flymans/' . $unique . '.png'));

        //保存された画像のパス
        $path = 'flymans/' . $unique . '.png';

        // 使いおわったtmpファイルを削除する処理
        unlink($tmp_file);

        return view('flyman', compact('path'));
    }
}
