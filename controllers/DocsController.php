<?php

require_once "AppController.php";

require_once __DIR__.'/../model/Document.php';
require_once __DIR__.'/../model/DocumentManager.php';


class DocsController extends AppController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->checkSession();
        //$text = 'Hello there! Its docs part';
        $text = 'Hello there 👋';
        $this->render('my', ['text' => $text]);
    }

    public function browseDocs()
    {
        $this->checkSession();
        /*
            pobieranie z bazy przykładowych dokumentów
        */
        $text = 'Hello there 👋';
        $this->render('browseDocs', ['text' => $text]);
        return;
    }

    public function createDoc()
    {
        $this->checkSession();
        $text = 'Hello there 👋';
        $this->render('createDoc', ['text' => $text]);
        return;
    }

    public function correctDoc()
    {
        $this->checkSession();
        $text = 'Hello there 👋';
        $this->render('correctDoc', ['text' => $text]);
        return;
    }

    public function editDoc()
    {
        $this->checkSession();
        $text = 'Hello there 👋';
        $this->render('editDoc', ['text' => $text]);
        return;
    }

    public function myDocs()
    {
        $this->checkSession();
        $docManager = new DocumentManager();        
        $id = $_SESSION["id"];
        $docs = $docManager->getUserDocuments($id);

        $this->render('myDocs', ['docs' => $docs]);
        return;
        

    }

    private function checkSession(){
        if(!isset($_SESSION["id"]))
            header("Location: {$url}?page=login");
    }
}