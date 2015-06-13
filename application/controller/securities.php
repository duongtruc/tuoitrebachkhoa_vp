<?php
/**
 * Created by PhpStorm.
 * User: DUONG_TRUC
 * Date: 8/21/14
 * Time: 5:51 PM
 */
class Securities extends Controller {
    public function checkCaptcha() {
        $userInput = $_POST['user-input'];
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if ($userInput == $_SESSION['captcha']){
            unset($_SESSION['captcha']);
            echo true;
        }
        else {
            echo false;
        }
    }
}