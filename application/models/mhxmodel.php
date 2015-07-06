<?php
/**
 * Created by PhpStorm.
 * User: Samuel Richesson
 * Date: 04/07/2015
 * Time: 11:29
 */

class mhxmodel {
    public function __construct(PDO $db)
    {
        try {
            $this->db = $db;
        } catch
        (PDOException $e) {
            exit ("Database is not established!");
        }
    }

    /**
     * Ham tra ve mot mang cac activities
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
    public function getDoneActivities($quantity)
    {
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $sql = "SELECT * FROM mhxactivities
        WHERE date < date(now()) ORDER BY `date` DESC";
        if ($quantity > 0) {
            $sql .= " LIMIT $quantity";
        }
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    public function getNewActivities($quantity)
    {
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $sql = "SELECT * FROM mhxactivities m
        WHERE m.date > date(now()) ORDER BY `date` DESC";
        if ($quantity > 0) {
            $sql .= " LIMIT $quantity";
        }
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    public function newStage($dName, $dDate, $dContent, $dQuantity, $dResult){
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $dQuan = (string)$dQuantity;
        $sql = "insert into mhxactivities(`name`, `date`, `content`, `quantity`, `result`) values
        ('$dName','$dDate','$dContent','$dQuan','$dResult')";
        $query = $this->db->prepare($sql);
        if ($query->execute())
            return 1;
        else
            return 0;
    }
} 