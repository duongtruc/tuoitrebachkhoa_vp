<?php
/**
 * Created by PhpStorm.
 * User: Duyen
 * Date: 7/21/14
 * Time: 12:26 AM
 */

class CheckList extends Controller
{
    public function index()
    {
        $users_model = $this->loadModel('usersmodel');
        $userLogged = $users_model->checkUserLogged();
        $this->render('checklist/index', array('userLogged' => $userLogged));
    }
}