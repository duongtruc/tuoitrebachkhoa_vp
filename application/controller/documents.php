<?php

/**
 * Created by PhpStorm.
 * User: Duyen
 * Date: 7/19/14
 * Time: 3:17 PM
 */
class Documents extends Controller
{
    public function index()
    {
        $articles_model = $this->loadModel('DocumentsModel');
        $documents = $articles_model->getSomeDocuments(6);

        $users_model = $this->loadModel('usersmodel');
        $userLogged = $users_model->checkUserLogged();

        $this->render('documents/index', array('documents' => $documents, 'userLogged' => $userLogged));
    }

    public function detail($id)
    {
        $users_model = $this->loadModel('usersmodel');
        $userLogged = $users_model->checkUserLogged();
        $articles_model = $this->loadModel('ArticlesModel');
        $article = $articles_model->getArticleById($id);

        $otherArticles = $articles_model->getSomeArticles(0, 2, 0);

        $comments_model = $this->loadModel('commentsmodel');
        $comments = $comments_model->getComments(1, $id);

        $this->render('articles/detail', array('article' => $article, 'comments' => $comments, 'otherArticles' => $otherArticles, 'userLogged' => $userLogged));
    }

    public function uploadNew()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        $dName = $_POST['d-name'];
        $dNo = $_POST['d-no'];
        $dSummary = $_POST['d-summary'];
        if (isset($_SESSION['email']))
            $dAuthor = $_SESSION['email'];
        else
            header("location: " . URL);
        $documentsmodel = $this->loadModel('documentsmodel');
        echo $documentsmodel->uploadNew($dName, $dNo, $dSummary, $dAuthor);
    }
}