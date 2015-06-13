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
            $sql = "INSERT INTO document (url, creator) VALUES('$imageUrl', '$dAuthor')";
        else if($type== 'report')
            $sql = "INSERT INTO stage (fileurl, creator) VALUES('$imageUrl', '$dAuthor')";
        else
            $sql = "INSERT INTO checklistfile (fileurl, creator) VALUES('$imageUrl', '$dAuthor')";
        $query = $this->db->prepare($sql);
        if ($query->execute())
            return 1;
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
}