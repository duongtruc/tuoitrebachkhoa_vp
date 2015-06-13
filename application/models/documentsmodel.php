<?php

/**
 * Created by PhpStorm.
 * User: Nguyen
 * Date: 7/17/14
 * Time: 11:36 AM
 */
class DocumentsModel
{
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
     * Ham tra ve mot mang cac article theo mot so tieu chuan nhat dinh
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
    public function getSomeDocuments($quantity)
    {
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $sql = "SELECT d.*, u.name as creator
        FROM document d LEFT JOIN users u
        ON d.creator = u.email WHERE d.status = 1 ORDER BY `date` DESC";
        if ($quantity > 0) {
            $sql .= " LIMIT $quantity";
        }
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    public function getSamePlaces($type)
    {

        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $sql = "SELECT id, `name` FROM v_place WHERE type = $type";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    public function uploadNew($dName, $dNo, $dSummary, $dAuthor)
    {
        $sql = "SELECT MAX(`id`) AS `id` FROM document";
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $query = $this->db->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        if (!is_null($result)) {
            $id = (string)$result->id;
            $sql = "UPDATE document SET
        `status` = 1, `no` = '$dNo', `name` = '$dName', `summary` = '$dSummary'
        WHERE `id` = '$id' AND creator = '$dAuthor'";
            $query = $this->db->prepare($sql);
            if ($query->execute())
                return 1;
            else
                return 0;
        }
    }

    /**
     * Tra ve v_place co $id
     * @param $id
     * @return int : Ton tai: array chua thong tin cua place; Khong ton tai: 0;
     */
    public function getOnePlace($id)
    {
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $sql = "SELECT v.id, v.FBShare, v.GPlusShare, v.name, v.type, v.website, v.description, v.presenter, v.phone, v.email, v.albumId,
        ac.cityName AS city, ad.districtName AS district, aw.wardName AS ward, v.street, v.no
        FROM v_place v LEFT JOIN address_city ac ON v.cityId = ac.id
        LEFT JOIN address_dist ad ON v.districtId = ad.id
        LEFT JOIN address_ward aw ON v.wardId = aw.id
        WHERE v.id = $id";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }


    public function getActivities($id)
    {
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $sql = "SELECT a.id, a.name, a.rating, a.startday, i.imageUrl FROM activities a JOIN images i ON a.thumbnailImageId = i.id WHERE a.id IN (SELECT activityId FROM activity_vplace WHERE vplaceId = $id)";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /** Ham insert vao csdl thong tin 1 dia diem moi
     * @param $name
     * @param $description
     * @param $phone
     * @param $email
     * @param $addressId
     * @param $locationId
     * @param $authorId
     * @return int tra ve 1 neu insert thanh cong, nguoc lai tra ve 0
     */
    public function addOnePlace($name, $nameASCII, $organization,
                                $presenter, $type, $description, $phone, $email, $website,
                                $cityId, $districtId, $wardId, $street, $no, $lat, $long, $creatorId, $albumId)
    {
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $sql = "INSERT INTO v_place (`name`, `nameASCII`, `organization`, `presenter`, `type`,
        description, phone,  email, website, cityId, districtId, wardId, street, `no`, `lat`, `long`, `creatorId`,
        albumId)
        VALUES('$name', '$nameASCII', '$organization', '$presenter', '$type', '$description', '$phone', '$email', '$website',
           $cityId, $districtId, $wardId, '$street', '$no',  $lat, $long, $creatorId, $albumId)";
        $query = $this->db->prepare($sql);
        if ($query->execute()) {
            if (!isset($_COOKIE["PHPSESSID"])) {
                session_start();
                unset($_SESSION['albumId']);
            }
            return 1;
        } else
            return 0;
    }

    public function searchPlace($str)
    {
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $sql = "SELECT p.id, p.name, i.imageUrl FROM `v_place` p JOIN images i ON p.thumbnailImageId = i.id  WHERE p.nameASCII LIKE '$str' LIMIT 2";
        $query = $this->db->prepare($sql);
        $query->execute();
        return json_encode($query->fetchAll());
    }

    public function getAllType()
    {
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $sql = "SELECT * FROM `placetype`";
        $query = $this->db->prepare($sql);
        $query->execute();
        return json_encode($query->fetchAll());
    }

    public function updateAddress($id, $field, $value)
    {
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        $sql = "UPDATE address SET $field = '$value' WHERE id = $id";
        $query = $this->db->prepare($sql);
        if ($query->execute()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function addShare($id, $type)
    {
        $sql = "UPDATE v_place SET ";
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