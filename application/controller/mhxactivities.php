<?php

/**
 * Created by PhpStorm.
 * User: Duyen
 * Date: 7/24/14
 * Time: 9:57 AM
 */
class mhxactivities extends Controller
{
    public function index()
    {
        $MHX_model = $this->loadModel('mhxmodel');
        $doneActivities = $MHX_model->getDoneActivities(6);
        $newActivities = $MHX_model->getNewActivities(6);

        $users_model = $this->loadModel('usersmodel');
        $userLogged = $users_model->checkUserLogged();

        $this->render('mhxactivities/index', array('doneActivities' => $doneActivities, 'newActivities' => $newActivities, 'userLogged' => $userLogged));
    }

    public function newStage()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        $dName = $_POST['d-name'];
        $dDate = $_POST['d-date'];
        $dContent = $_POST['d-content'];
        $dQuantity = $_POST['d-quantity'];
        $dResult = $_POST['d-result'];
        $mhxmodel = $this->loadModel('mhxmodel');
        echo $mhxmodel->newStage($dName, $dDate, $dContent, $dQuantity, $dResult);
    }
}