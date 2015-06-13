<?php

/**
 * Created by PhpStorm.
 * User: DUONG_TRUC
 * Date: 8/21/14
 * Time: 3:52 PM
 */
class Captcha
{
    public function getNewCaptcha() {
        /*$md5_hash = md5(rand(0, 999));
        $captcha_code = substr($md5_hash, 15, 5);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        //echo "here!";
        $_SESSION['captcha'] = $captcha_code;*/

        session_start();

        header("Expires: Tue, 01 Jan 2013 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < 5; $i++)
        {
            $randomString .= $chars[rand(0, strlen($chars)-1)];
        }

        $_SESSION['captcha'] = strtolower( $randomString );


        $im = @imagecreatefrompng("http://tinhnguyen.edu.vn/public/img/background/captcha_bg.png");


        imagettftext($im, 30, 0, 10, 38, imagecolorallocate ($im, 0, 0, 0), 'http://tinhnguyen.edu.vn/public/fonts/larabiefont.ttf', $randomString);

        header ('Content-type: image/png');
        imagepng($im, NULL, 0);
        imagedestroy($im);

        /*$im = @imagecreatefrompng(URL."public/img/background/captcha_bg.png");
        //imagettftext($im, 30, 0, 10, 38, imagecolorallocate ($im, 0, 0, 0), URL.'public/fonts/OpenSans-Bold.woff', $captcha_code);
        header ('Content-type: image/png');
        imagepng($im, NULL, 0);
        imagedestroy($im);*/
    }
}