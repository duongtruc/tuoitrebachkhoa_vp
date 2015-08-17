<?php

/**
 * Created by PhpStorm.
 * User: Nguyen
 * Date: 7/16/14
 * Time: 10:17 PM
 */
class ReportModel
{
    public function __construct(PDO $db)
    {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit("Database couldn't established");
        }
    }
    public function newStage($withFile, $dId, $dName, $dDate, $dSummary, $dAuthor, $dRelation)
    {
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        if ($withFile){
            $sql = "UPDATE stage SET
            stagename = '$dName', description = '$dSummary', expiredate = '$dDate' 
            WHERE id = '$dId'";
        }
        else
            $sql = "INSERT INTO stage VALUES('$dId','$dName','$dSummary','$dDate','', '$dAuthor')";
        $query = $this->db->prepare($sql);
        if ($query->execute())
        {
            foreach($dRelation as $email)
            {
                $sql = "INSERT INTO reportrelation(id, email) VALUES ('$dId','$email')";
                $query = $this->db->prepare($sql);
                $query->execute();
            }
            return intval($dId);
        }
        else
            return 0;
    }
    /**
     * Ham tra ve mot mang cac article theo mot so tieu chuan nhat dinh
     *      * @param $status
     *      = 1 : articles da duoc duyet
     *      = 2 : articles chua duyet
     *      = 0: khong rang buoc ve trang thai
     * @param $condition :
     *      = 1 : theo thoi gian tu xa den gan
     *      = 2 : theo thoi gian tu gan den xa
     *      = 3 : theo luong like tu it den nhieu
     *      = 4 : theo luong like tu nhieu den it
     *      = 0: Khong order
     * @param $quantity
     *      = 0 neu muon lay tat ca
     *      = n (n > 0): Lay n articles dau tien
     * @return mixed
     */
    public function getSomeStages($quantity)
    {
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $sql = "SELECT * FROM stage ORDER BY ID DESC";
        if ($quantity > 0) {
            $sql .= " LIMIT $quantity";
        }
        //echo $sql ."</br>"; //If you wanna show sql statement, let's uncomment this line
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getArticleById($id)
    {
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $sql = "SELECT a.*, CONCAT(u.fname, ' ', u.lname) AS author, i.imageUrl
        FROM articles a LEFT JOIN `user` u ON a.authorId = u.id
        LEFT JOIN images i ON a.thumbnailImageId = i.id
        WHERE a.id = $id";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function searchArticle($str)
    {
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $sql = "SELECT a.id, a.title, i.imageUrl FROM articles a JOIN images i ON a.thumbnailImageId = i.id WHERE a.titleASCII LIKE '$str' LIMIT 2";
        $query = $this->db->prepare($sql);
        $query->execute();
        return json_encode($query->fetchAll());
    }

    public function addShare($id, $type)
    {
        $sql = "UPDATE articles SET ";
        if ($type == 1)
            $sql .= "FBShare = FBShare + 1";
        else
            $sql .= "GPlusShare = GPlusShare + 1";
        $sql .= " WHERE id = $id";
        var_dump($sql);
        $query = $this->db->prepare($sql);
        $query->execute();
    }

}