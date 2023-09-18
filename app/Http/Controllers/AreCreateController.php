<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class AreCreateController extends Controller
{
    public function are_create(Request $request)
    {
        // 小数点切り捨て ~~.0も切り捨て
        $xPos = floor($request->input('xPos'));
        $yPos = floor($request->input('yPos'));
        $angle = floor($request->input('angle'));
        $image = Image::make($request->background);
        $maxWidth = 500;
        $maxHeight = 500;

        // 画像の現在の幅と高さを取得
        $currentWidth = $image->width();
        $currentHeight = $image->height();

        // 縦または横のいずれかが指定した値を超える場合の処理
        if ($currentWidth > $maxWidth || $currentHeight > $maxHeight) {
            $image->resize($maxWidth, $maxHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize(); // このオプションで、元のサイズよりも小さくならないようにする
            });
        }
        $insertImage = Image::make(public_path('image/flyman.png'));
        // width 100px, height 100px にリサイズ
        $insertImage->resize(150, 150, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize(); // このオプションで、元のサイズよりも小さくならないようにする
        });
        $angle = $angle * -1;
        $insertImage->rotate($angle);

        // 画像を合成 angleは回転角度
        $image->insert($insertImage, 'top-left', $xPos, $yPos);

        // 画像を保存
        $unique = uniqid();
        $image->save(public_path('flymans/' . $unique . '.png'));

        //保存された画像のパス
        $path = 'flymans/' . $unique . '.png';

        return view('flyman', compact('path'));
    }
}
