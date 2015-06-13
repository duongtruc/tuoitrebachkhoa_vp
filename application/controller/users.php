<?php

/**
 * Class Users
 */
class Users extends Controller
{
    /**
     * PAGE: register
     * This method handles what happens when you move to http://URL/users/register
     */
    public function register()
    {
        $users_model = $this->loadModel('usersmodel');
        if ($users_model->checkUserLogged()) {
            header("location: ".URL);
        } else {
            $this->render('users/register');
        }
    }
    public function userRegister() {
        $users_model = $this->loadModel('usersmodel');
        $mailer_model = $this->loadModel('mailerModel');
        if (isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['email']) && isset($_POST['password'])) {
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $email = $_POST['email'];
            $password = md5($_POST['password']);
            $activationCode = md5(rand());
            if ($users_model->addUser($fname, $lname, $email, $password, $activationCode)) {
                $mailer_model->confirmMailer($email, $lname, $activationCode);
                echo 1;
            } else {
                echo 0;
            }

        }
    }
    public function detail($id) {
        if (isset($_GET['tab'])) {
            $tab = $_GET['tab'];
        } else {
            $tab = 'profile';
        }
        $user_model = $this->loadModel('usersmodel');
        $userDetails = $user_model->getUserById($id);
        $userLogged = $user_model->checkUserLogged();
        $this->render('users/detail', array('userDetails' => $userDetails, 'userLogged' => $userLogged, 'tab' =>$tab));
    }

    public function userFollow() {
        $userId = $_POST['userId'];
        $friendId = $_POST['friendId'];
        $users_model = $this->loadModel('usersmodel');
        echo $users_model->userFollow($userId, $friendId);
    }

    public function userConfirm()
    {
        $email = $_GET['email'];
        $code = $_GET['activationCode'];
        $users_model = $this->loadModel('usersmodel');
        $result = 0;
        if ($users_model->userActivation($email, $code))
            $result = 1;
        $this->render('users/userConfirm', array('result' => $result));
    }

    public function passwordRecoveryMail()
    {
        $users_model = $this->loadModel('usersmodel');
        $mailer_model = $this->loadModel('mailermodel');
        $email = $_POST["email"];
        $code = md5(rand());
        if ($users_model->setRecoveryCode($email, $code)) {
            if ($mailer_model->recoveryMailer($email, $code))
                echo 1;
            else echo 0;
        } else echo 0;
    }
    public function userUpdate(){
        $field = $_POST['field'];
        $value = $_POST['value'];
        $user_model = $this->loadModel('usersmodel');
        echo $user_model->userUpdate($field, $value);
    }
    /**
     * Kiem tra duong dan kich hoat co ton tai hay khong
     * @return :
     *          + 0: Nguoi dung nhap email de xac nhan
     *          + 1: Nguoi dung nhap vao duong dan xac nhan ton tai tren he thong
     *          + 2: User click vao duong dan kich hoat sai
     */
    public function passwordRecovery()
    {
        $recoveryUI = 0;
        $email = '';
        $users_model = $this->loadModel('usersmodel');
        if (isset($_GET['email']) && isset($_GET['recoveryCode'])) {
            $email = $_GET['email'];
            $code = $_GET['recoveryCode'];
            if ($users_model->checkPasswordRecoveryCode($email, $code))
                $recoveryUI = 1;
            else
                $recoveryUI = 2;
        }
        $this->render('users/passwordRecovery', array('UI'=>$recoveryUI, 'email' =>$email));
    }
    public function updatePassword(){
        $users_model = $this->loadModel('usersmodel');
        $email = $_POST['email'];
        $newPassword = md5($_POST['newPassword']);
        echo ($users_model->updatePassword($email, $newPassword));
    }

    public function userLogin()
    {
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        $users_model = $this->loadModel('usersmodel');
        $users_model->userLogin($email, $password);
    }


    public function userLogOut()
    {
        session_start();
        session_destroy();
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header('Location: ' . URL . '/home');
        }
        exit;
    }
}