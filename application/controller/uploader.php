<?php
/**
 * upload.php
 *
 * Copyright 2013, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */

#!! IMPORTANT: 
#!! this file is just an example, it doesn't incorporate any security checks and 
#!! is not recommended to be used in production environment as it is. Be sure to 
#!! revise it and customize to your needs.
class Uploader extends Controller
{
    public function index()
    {
        header("location: " . URL);
    }

    public function endAlbum()
    {
        session_start();
        if (isset($_SESSION['albumId']))
            unset($_SESSION['albumId']);
    }

    public function startAlbum($images_model)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['albumId'])) {
            $albumId = $images_model->createNewAlbum();
            $_SESSION['albumId'] = $albumId;
        }
    }

    function getExtension($str)
    {
        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }

    public function checklist()
    {
        $images_model = $this->loadModel('imagesmodel');
        define ("MAX_SIZE", "9000");
        $type = 'checklist';
        $valid_formats = array("txt", "csv");
        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            $endPath = $type . "/" . date('d-m-Y');
            $uploaddir = getcwd() . "/public/" . $endPath; //a directory inside
            if (!file_exists($uploaddir)) {
                @mkdir($uploaddir);
            }
            foreach ($_FILES['photos']['name'] as $name => $value) {

                $filename = stripslashes($_FILES['photos']['name'][$name]);
                $size = filesize($_FILES['photos']['tmp_name'][$name]);
                //get the extension of the file in a lower case format
                $ext = $this->getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {
                    if ($size < (MAX_SIZE * 1024)) {
                        $image_name = '[BKHCM_DTN]' . uniqid() . '.' . $ext;
                        //file process
                        $newname = $uploaddir . '/' . $image_name;

                        if (move_uploaded_file($_FILES['photos']['tmp_name'][$name], $newname)) {
                            $images_model->addOneImage($endPath . "/" . $image_name, $type);
                        } else {
                            echo '<span class="imgList">You have exceeded the size limit! so moving unsuccessful! </span>';
                        }

                        $file = fopen(URL . 'public/' . $endPath . "/" . $image_name, 'r');
                        $studentCodes = array();
                        while (!feof($file)) {
                            $line = fgets($file);
                            $line = explode(',', $line);
                            $code = ltrim($line[0], '0');
                            if (!in_array($code, $studentCodes))
                                array_push($studentCodes, $code);
                        }
                        $checklistmodel = $this->loadModel('checklistmodel');
                        $result = $checklistmodel->getStudent($studentCodes);
                        $index = 1;
                        echo '<table>';
                        echo '<tr>';
                        echo '<td>STT</td><td>MSSV</td><td>Họ lót</td><td>Tên</td><td>Khoa</td><td>Sai mã SV</td>';
                        echo '</tr>';
                        foreach ($result as $student) {
                            echo '<tr>';
                            echo '<td>' . $index++ . '</td>';
                            echo '<td>' . $student->id . '</td>';
                            echo '<td style="text-align: left !important">' . $student->fname . '</td>';
                            echo '<td>' . $student->lname . '</td>';
                            echo '<td>' . $student->fac . '</td>';
                            echo '<td>' . $student->warning . '</td>';
                            echo '</tr>';
                        }
                        echo '</table>';


                    } else {
                        echo '<span class="imgList">You have exceeded the size limit!</span>';

                    }

                } else {
                    echo '<span class="imgList">Unknown extension!</span>';

                }

            }
        }
    }

    public function userUpload()
    {
        $upload_model = $this->loadModel('imagesmodel');
        define ("MAX_SIZE", "9000");
        $type = $_GET['type'];
        $valid_formats = array("doc", "docx", "zip", "rar", "pdf");
        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            $endPath = $type . "/" . date('d-m-Y');
            $uploaddir = getcwd() . "/public/" . $endPath; //a directory inside
            if (!file_exists($uploaddir)) {
                @mkdir($uploaddir);
            }
            foreach ($_FILES['photos']['name'] as $name => $value) {

                $filename = stripslashes($_FILES['photos']['name'][$name]);
                $size = filesize($_FILES['photos']['tmp_name'][$name]);
                //get the extension of the file in a lower case format
                $ext = $this->getExtension($filename);
                $ext = strtolower($ext);

                if (in_array($ext, $valid_formats)) {
                    if ($size < (MAX_SIZE * 1024)) {
                        $image_name = '[BKHCM_DTN]' . rand(1, 1000) . '_' . $filename;
                        echo "<div class='user-uploaded-image'>" . $image_name . "</div>";
                        $newname = $uploaddir . '/' . $image_name;

                        if (move_uploaded_file($_FILES['photos']['tmp_name'][$name], $newname)) {
                            $result = $upload_model->addOneImage($endPath . "/" . $image_name, $type);
                            echo "<div style='font-size: 0' class='id-new-report'>".$result."</div>";
                        } else {
                            echo '<span class="imgList">You have exceeded the size limit! so moving unsuccessful! </span>';
                        }

                    } else {
                        echo '<span class="imgList">You have exceeded the size limit!</span>';
                    }

                } else {
                    echo '<span class="imgList">Unknown extension!</span>';

                }

            }
        }
    }
}

