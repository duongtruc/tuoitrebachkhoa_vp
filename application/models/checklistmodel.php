<?php

/**
 * Created by PhpStorm.
 * User: Nguyen
 * Date: 7/17/14
 * Time: 9:39 AM
 */
class student
{
    public $id;
    public $fname;
    public $lname;
    public $fac;
    public $warning = 0;
}

class ChecklistModel
{
    public function __construct(PDO $db)
    {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit("Database couldn't established");
        }
    }

    public function getStudent($studentCodes)
    {
        $list = array();
        $query = $this->db->prepare("SET NAMES 'UTF8'");
        $query->execute();
        foreach($studentCodes as $stu) {
            $element = new student();
            $sql = "SELECT * FROM student WHERE id = '$stu'";
            $query = $this->db->prepare($sql);
            $query->execute();
            $result = $query->fetch();
            $element->id = $stu;
            if ($result != null) {
                $element->fname = $result->fname;
                $element->lname = $result->lname;
                $element->fac = $result->fac;
            }
            else {
                $id = substr($stu, -3);
                $sql = "SELECT * FROM student WHERE id LIKE('%"."$id')";
                $query = $this->db->prepare($sql);
                $query->execute();
                $result2 = $query->fetch();
                if ($result2 != null) {
                    $element->fname = $result2->fname;
                    $element->lname = $result2->lname;
                    $element->fac = $result2->fac;
                    $element->warning = 1;
                }
                else
                    $element->warning = 1;
            }
            array_push($list, $element);
        }
        return $list;
    }
}