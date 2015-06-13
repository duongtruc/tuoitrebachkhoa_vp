<?php
/**
 * Created by PhpStorm.
 * User: DUONG_TRUC
 * Date: 7/29/14
 * Time: 7:23 PM
 */
class navSearch extends Controller  {
    public function  articleSearch () {
        $str = $_GET['query'];
        $article_model = $this->loadModel('articlesmodel');
        $str = $this->convertSearchString($str);
        echo $article_model->searchArticle($str);
    }

    public function  userSearch () {
        $str = $_GET['query'];
        $user_model = $this->loadModel('usersmodel');
        $str = $this->convertSearchString($str);
        echo $user_model->searchUser($str);
    }
    public function  placeSearch () {
        $str = $_GET['query'];
        $place_model = $this->loadModel('placesmodel');
        $str = $this->convertSearchString($str);
        echo $place_model->searchPlace($str);
    }
    public function  activitySearch () {
        $str = $_GET['query'];
        $activity_model = $this->loadModel('activitiesmodel');
        $str = $this->convertSearchString($str);
        echo $activity_model->searchActivity($str);
    }
}