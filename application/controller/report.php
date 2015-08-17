<?php

/**
 * Created by PhpStorm.
 * User: Duyen
 * Date: 7/24/14
 * Time: 9:57 AM
 */
class Report extends Controller
{
    public function index()
    {
        $report_model = $this->loadModel('reportmodel');
        $stages = $report_model->getSomeStages(6, 0);

        $users_model = $this->loadModel('usersmodel');
        $userLogged = $users_model->checkUserLogged();
        $this->render('report/index', array('stages' => $stages, 'userLogged' => $userLogged));
    }
    public function newStage()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        $dName = $_POST['d-name'];
        $dDate = $_POST['d-date'];
        $dSummary = $_POST['d-summary'];
        $dRelation = $_POST['d-relation'];
        
        if ($_POST['d-id']!='')
        {
            $dId = $_POST['d-id'];
            $withFile = true;
        }
        else
        {
            $query = $this->db->prepare("SET NAMES UTF8");
            $query->execute();
            $query = $this->db->prepare("SELECT MAX(id) AS newid FROM stage");
            $query->execute();
            $result = $query->fetch(PDO::FETCH_OBJ);
            $dId = intval($result->newid)+1;
            $withFile = false;
        }
        if (isset($_SESSION['email']))
            $dAuthor = $_SESSION['email'];
        else
            header("location: " . URL);
        $report_model = $this->loadModel('reportmodel');
        echo  $report_model->newStage($withFile, $dId, $dName, $dDate, $dSummary, $dAuthor, $dRelation);
        
        $users_model = $this->loadModel('usersmodel');
        $nameList = $users_model->findName($dRelation);
        $mailer_model = $this->loadModel('mailermodel');
        $mailer_model->announceMailer($dRelation, $nameList);
    }
    
    
    public function detail($id)
    {
        if (isset($_GET['tab'])) {
            $tab = $_GET['tab'];
        } else {
            $tab = 'info';
        }
        $users_model = $this->loadModel('usersmodel');
        $userLogged = $users_model->checkUserLogged();

        $places_model = $this->loadModel('PlacesModel');
        $place = $places_model->getOnePlace($id);
        $activities = $places_model->getActivities($id);

        $images_model = $this->loadModel('imagesmodel');
        if ($place->albumId)
            $images = $images_model->getImagesOfAlbum($place->albumId);
        else
            $images = null;

        $samePlaces = $places_model->getSamePlaces($place->type);

        $comments_model = $this->loadModel('commentsmodel');
        $comments = $comments_model->getComments(2, $id);

        $this->render('places/detail', array('place' => $place, 'comments' => $comments, 'samePlaces' => $samePlaces, 'userLogged' => $userLogged, 'images' => $images, 'tab' => $tab, 'activities' => $activities));
    }

    public function addressUpdate()
    {
        session_start();
        $addressId = $_SESSION['addressId'];
        $field = $_POST['field'];
        $value = $_POST['value'];
        $place_model = $this->loadModel('placesmodel');
        echo $place_model->updateAddress($addressId, $field, $value);
    }

    public function getComments()
    {
        $id = $_POST['id'];
        $comments_model = $this->loadModel('commentsmodel');
        echo json_encode($comments_model->getComments(2, $id));
    }

    public function addComment()
    {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $comment = $_POST['comment'];
        $office = $_POST['office'];
        $comments_model = $this->loadModel('commentsmodel');
        $comments_model->addComment(2, $id, $comment, $name, $office);
    }
    public function getAllType() {
        $places_model = $this->loadModel('placesmodel');
        echo $places_model->getAllType();
    }
    public function addPlace()
    {
        session_start();
        $creatorId = $_SESSION['id'];
        $albumId = $_SESSION['albumId'];
        $name = $_POST['name'];
        $nameASCII = $this->stringNormalize($name);
        $type = $_POST['type'];
        $organization = $_POST['organization'];
        $presenter = $_POST['presenter'];
        $no = $_POST['no'];
        $lat = $_POST['lat'];
        $long = $_POST['long'];
        $street = $_POST['street'];
        $wardId = $_POST['wardId'];
        $districtId = $_POST['districtId'];
        $cityId = $_POST['cityId'];
        $phone = $_POST['phone'];
        $description = $_POST['description'];
        $email = $_POST['email'];
        $website = $_POST['website'];
        $place_model = $this->loadModel('placesmodel');
        echo($place_model->addOnePlace($name, $nameASCII, $organization,
            $presenter, $type, $description, $phone, $email, $website, $cityId,
            $districtId, $wardId, $street, $no, $lat, $long, $creatorId, $albumId));
    }

}