<?php

//サニタイズを行うクラス
class Sanitization
{
    private $post;

    //サニタイズ（配列）
    public function sanitize(array $post)
    {
        foreach ($post as $key => $value) {
            $sanitizedPost[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
        return $sanitizedPost;
    }

    //サニタイズ（二次元配列）
    public function sanitizeTwoDimensionalArray(array $post)
    {
        foreach ($post as $data => $array) {
            foreach ($array as $key => $value) {
                $sanitizedArray[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
            $sanitizedPost[$data] = $sanitizedArray;
        }
        return $sanitizedPost;
    }
}
