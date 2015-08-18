<?php

/**
 * Created by PhpStorm.
 * User: Nguyen
 * Date: 7/17/14
 * Time: 3:39 PM
 */
class ImagesModel
{
    public function __construct($db)
    {
        try {
            $this->db = $db;
        } catch
        (PDOException $e) {
            exit ("Database is not established!");
        }
    }

    /**Insert vao table images 1 image moi
     * @param $creatorId
     * @param $caption
     * @param $imageUrl
     * @return int tra ve 1 neu insert thanh cong va tra ve 0 neu nguoc lai
     */
    public function addOneImage($imageUrl, $type)
    {
        /*if (isset($creatorId) && isset($caption) && isset($imageUrl))*/
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        if (isset($_SESSION['email']))
            $dAuthor = $_SESSION['email'];
        else
            header("location: " . URL . '/documents');
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        if ($type == 'documents')
            $query = $this->db->prepare("SELECT MAX(id) as id FROM  document");
        else if($type== 'report')
            $query = $this->db->prepare("SELECT MAX(id) as id FROM  stage");
        else
            $query = $this->db->prepare("SELECT MAX(id) as id FROM  checklistfile");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        if (!is_null($result))
            $id = (string)(intval($result->id)+1);
        else $id = '1';
        if ($type == 'documents')
            $sql = "INSERT INTO document (id, url, creator) VALUES('$id', '$imageUrl', '$dAuthor')";
        else if($type== 'report')
            $sql = "INSERT INTO stage (id, fileurl, creator) VALUES('$id', '$imageUrl', '$dAuthor')";
        else
            $sql = "INSERT INTO checklistfile (id, fileurl, creator) VALUES('$id', '$imageUrl', '$dAuthor')";
        $query = $this->db->prepare($sql);
        if ($query->execute())
            return $id;
        else
            return 0;
    }

    public function CreateNewAlbum()
    {
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $sql = "INSERT INTO imagesalbum(name, description) VALUES('Chưa có tên', 'Chưa có mô tả')";
        $query = $this->db->prepare($sql);
        if ($query->execute()) {
            $sql = "SELECT MAX(id) AS albumId FROM imagesalbum";
            $query = $this->db->prepare($sql);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_OBJ);
            return (string)$result->albumId;
        } else return 0;

    }

    /**
     * Ham lay tat ca cac image thuoc album co $id
     * @param $id
     * @return int Tra ve mang chua cac image thuoc album hoac tra ve 0 neu
     * album khong ton tai hoac khong chua bat ki anh nao
     */
    public function getImagesOfAlbum($id)
    {
        $sql = "SELECT * FROM images WHERE albumId  = $id";
        $query = $this->db->prepare($sql);
        if (!$query) {
            return 0;
        } else {
            $query->execute();
            return $query->fetchAll(PDO::FETCH_OBJ);
        }

    }
    public function abortUpload($type, $id)
    {
        $file = fopen('delete.log','w');
        fwrite($file,'get in');
        fclose($file);
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        if ($type == 'report'){
            $getsql = "SELECT fileurl as url FROM stage WHERE id = '$id'";
            $delelesql = "DELETE FROM stage WHERE id = '$id'";
        }
        elseif ($type == 'documents'){
            $getsql = "SELECT url FROM document WHERE id = '$id'";
            $delelesql = "DELETE FROM document WHERE id = '$id'";
        }
        else {
            $getsql = "SELECT fileurl as url FROM checklistfile WHERE id = '$id'";
            $delelesql = "DELETE FROM checklistfile WHERE id = '$id'";
        }
        $query = $this->db->prepare($getsql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        $path = $result->url;
        $query = $this->db->prepare($delelesql);
        $query->execute();
        return $path;
    }
}