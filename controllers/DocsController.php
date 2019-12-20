<?php

require_once "AppController.php";

require_once __DIR__.'/../model/User.php';
require_once __DIR__.'/../model/UserManager.php';


class DocsController extends AppController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        //$text = 'Hello there! Its docs part';
        $text = 'Hello there 👋';
        $this->render('my', ['text' => $text]);
    }

    public function browseDocs()
    {
        /*
            pobieranie z bazy przykładowych dokumentów
        */
        $text = 'Hello there 👋';
        $this->render('browseDocs', ['text' => $text]);
        return;
    }

    public function createDoc()
    {
        $text = 'Hello there 👋';
        $this->render('createDoc', ['text' => $text]);
        return;
    }

    public function correctDoc()
    {
        $text = 'Hello there 👋';
        $this->render('correctDoc', ['text' => $text]);
        return;
    }

    public function editDoc()
    {
        $text = 'Hello there 👋';
        $this->render('editDoc', ['text' => $text]);
        return;
    }

    public function myDocs()
    {
        $text = 'Hello there 👋';
        $this->render('myDocs', ['text' => $text]);
        return;
    }
}