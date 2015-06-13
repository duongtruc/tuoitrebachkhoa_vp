<?php

/**
 * Created by PhpStorm.
 * User: minh
 * Date: 7/17/14
 * Time: 10:42 AM
 */
class UsersModel extends Controller
{
    public function __construct($db)
    {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit("Database couldn't established");
        }
    }

    /**
     * Ham kiem tra user co email la $email da to tai hay chua
     * @param $email
     * @return bool : TRUE neu user da ton tai, FALSE: user chua ton tai
     */
    public function checkUserExist($email)
    {
        $sql = "SELECT `id` FROM `user` WHERE email = '$email'";
        $query = $this->db->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        $count = sizeof($result);
        if ($count > 0)
            return true;
        else return false;
    }

    public function checkPasswordRecoveryCode($email, $code)
    {
        $sql = "SELECT id FROM `user` WHERE email = '$email' AND passwordRecoveryCode = '$code'";
        $query = $this->db->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        $count = sizeof($result);
        if ($count > 0)
            return true;
        else return false;
    }

    public function setRecoveryCode($email, $code)
    {
        if ($this->checkUserExist($email)) {
            $sql = "UPDATE user SET passwordRecoveryCode = '$code' WHERE email = '$email'";
            $query = $this->db->prepare($sql);
            $query->execute();
            return true;
        } else return false;
    }

    function updatePassword($email, $newPassword)
    {
        $sql = "UPDATE user SET password = '$newPassword', passwordRecoveryCode = NULL WHERE email = '$email'";
        if ($query = $this->db->prepare($sql)) {
            $query->execute();
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Ham add user vao database va tu sinh ma kich hoat
     * @param $fname
     * @param $lname
     * @param $email
     * @param $pw
     * @return int 1: Add user successful 0: unsuccessful
     */
    public function addUser($fname, $lname, $email, $pw, $activationCode)
    {
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        if (!($this->checkUserExist($email))) {
            $sql = "insert into user(fname,lname, fullnameASCII, email,password,activationCode) values('$fname','$lname','{$this->stringNormalize($fname . ' ' . $lname)}', '$email','$pw','$activationCode');";
            $query = $this->db->prepare($sql);
            if ($query->execute())
            return true;
            else return false;
        } else {
            return false;
        }
    }

    /**
     * ham check user co ton tai chua va ma kich hoat nhap vao co dung ko
     * @param $email
     * @param $code
     * @return bool
     */
    public function checkActivationUser($email, $code)
    {
        $sql = "SELECT id FROM `user` WHERE `email` = '$email' and `activationCode`='$code'";
        $query = $this->db->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        $count = sizeof($result);
        if ($count > 0)
            return true;
        else return false;
    }

    /**
     * ham kich hoat tai khoang, groupID=0 chuyen sang groupID=1, tai khoan da kich hoat
     * @param $email
     * @param $code
     * @return string
     */
    public function userActivation($email, $code)
    {
        if ($this->checkActivationUser($email, $code)) {
            $sql = "UPDATE user SET status=1, activeDate = NOW() WHERE email='$email'";
            $query = $this->db->prepare($sql);
            $query->execute();
            return 1;
        } else {
            return 0;
        }
    }

    /** User Login
     * @param $email
     * @param $password
     * @return int
     */
    public function userLogin($email, $password)
    {
        $sql = "SELECT u.name, u.level FROM users u
        WHERE email = '$email' AND password = '$password'";
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $query = $this->db->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
            if (!is_null($result)) {
                session_start();
                $_SESSION['email'] = (string)$result->email;
            $_SESSION['name'] = (string)$result->name;
            $_SESSION['level'] = (string)$result->level;
            if (isset($_SERVER['HTTP_REFERER'])) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                header('Location: ' . URL . '/home');
            }
            return 1;
            exit;
        } else {
            if (isset($_SERVER['HTTP_REFERER'])) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                header('Location: ' . URL . '/home');
            }
            exit;
        }
    }

    /**
     * Check if user is logged
     * @return array|int
     */
    public function checkUserLogged()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['level'])) {
            $userInfo = array('name' => $_SESSION['name'],
                'level' => $_SESSION['level'],
            );
            return $userInfo;
        } else {
            return 0;
        }
    }

    public function getNewUsers()
    {
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $sql = "SELECT u.id, u.activeDate, CONCAT(u.fname, ' ' , u.lname) AS fullname, u.dob, u.job, u.office, i.imageUrl  FROM `user` u LEFT JOIN images i ON u.avatarId = i.id ORDER BY u.activeDate LIMIT 8";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    public function searchUser($str)
    {
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $sql = "SELECT u.id, CONCAT(u.fname, ' ' , u.lname) AS fullname, i.imageUrl, g.`name`
        AS userGroup FROM `user` u JOIN images i ON u.avatarId = i.id
        LEFT JOIN `group` g ON u.groupId = g.id WHERE u.fullnameASCII LIKE '$str' LIMIT 2";
        $query = $this->db->prepare($sql);
        $query->execute();
        return json_encode($query->fetchAll());
    }

    public function getUserById($id)
    {
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $sql = "SELECT u.id, fname, lname, phone, email, ac.cityName AS city, ad.districtName AS district, aw.wardName AS ward, u.street, u.no, shortDes, job, imageUrl, dob, office,gender
        FROM `user` u LEFT JOIN address_city ac ON u.cityId = ac.id
        LEFT JOIN address_dist ad ON u.districtId = ad.id
        LEFT JOIN address_ward aw ON u.wardId = aw.id
        LEFT JOIN images i ON u.avatarId = i.id
        WHERE u.id = $id";
        $query = $this->db->query($sql);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function userFollow($userId, $friendId)
    {
        $sql = "INSERT INTO userFollow(userId, friendId) VALUES($userId, $friendId)";
        $query = $this->db->prepare($sql);
        if ($query->execute()) {
            return 1;
        } else {
            return 0;
        }
    }

}