<?php

require_once "AppController.php";

require_once __DIR__.'/../model/User.php';
require_once __DIR__.'/../model/UserMapper.php';


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

    public function browse()
    {
        /*
            pobieranie z bazy przykładowych dokumentów
        */
        $text = 'Hello there 👋';
        $this->render('browse', ['text' => $text]);
        return;
    }

    public function create()
    {
        $text = 'Hello there 👋';
        $this->render('create', ['text' => $text]);
        return;
    }

    public function correct()
    {
        $text = 'Hello there 👋';
        $this->render('correct', ['text' => $text]);
        return;
    }

    public function edit()
    {
        $text = 'Hello there 👋';
        $this->render('edit', ['text' => $text]);
        return;
    }

    public function myDocs()
    {
        $text = 'Hello there 👋';
        $this->render('myDocs', ['text' => $text]);
        return;
    }
}