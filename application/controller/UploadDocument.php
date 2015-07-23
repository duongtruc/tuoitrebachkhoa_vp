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

class UploadDocument extends Controller
{
    public function index()
    {
        header("location: " . URL);
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

    public function myUserUpload()
    {
        $data = array();

        $data['type'] = "nam";
        $data['name'] = $_FILES['d-file']['name'];
//        print_r($_POST);
//        print_r($_FILES);
//        $images_model = $this->loadModel('imagesmodel');
//        define ("MAX_SIZE", "9000");
//        $type = 'documents';
//        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
//            $endPath = $type . "/" . date('d-m-Y');
//            $uploaddir = getcwd() . "/public/" . $endPath; //a directory inside
//            if (!file_exists($uploaddir)) {
//                @mkdir($uploaddir);
//            }
//            $filename = $_FILES['img']['name'];
//            $size = filesize($_FILES['photos']['tmp_name']);
//            if ($size < (MAX_SIZE * 1024)) {
//                $image_name = '[BKHCM_DTN]' . rand(1, 1000) . '_' . $filename;
//                echo "<div class='user-uploaded-image'>" . $image_name . "</div>";
//                $newname = $uploaddir . '/' . $image_name;
//
//                if (move_uploaded_file($_FILES['photos']['tmp_name'], $newname)) {
//                    $images_model->addOneImage($endPath . "/" . $image_name, $type);
//                } else {
//                    echo '<span class="imgList">Dung l??ng file quá l?n </span>';
//                }
//
//            } else {
//                echo '<span class="imgList">Dung l??ng file quá l?n</span>';
//            }
//        }

        echo json_encode($data);
    }


}
?>
