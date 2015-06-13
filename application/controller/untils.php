<?php

/**
 * Created by PhpStorm.
 * User: DUONG_TRUC
 * Date: 8/8/14
 * Time: 8:31 PM
 */
class untils extends Controller
{
    public function addShare()
    {
        $objectType = $_POST['objectType'];
        $type = $_POST['type'];
        $id = $_POST['id'];
        switch ($objectType) {
            case 1:
                $articles_model = $this->loadModel('articlesmodel');
                $articles_model->addShare($id, $type);
                break;
            case 2:
                $places_model = $this->loadModel('placesmodel');
                $places_model->addShare($id, $type);
                break;
            case 3:
                $activities_model = $this->loadModel('activitiesmodel');
                $activities_model->addShare($id, $type);
                break;
        }
    }
    public function createNewAlbum() {
        $albumName = $_POST['albumName'];
        $images_model = $this->loadModel('imagesmodel');
        $images_model->createNewAlbum($albumName);
    }
}