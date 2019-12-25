<?php

require_once "AppController.php";

require_once __DIR__.'/../model/User.php';
require_once __DIR__.'/../model/UserManager.php';


class DefaultController extends AppController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $text = 'Hello there 👋';

        $this->render('index', ['text' => $text]);
    }

    public function login()
    {   
        $userManager = new UserManager();

        if ($this->isPost()) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $userManager->getUser($email);

            if (!$user) {
                $this->render('login', ['messages' => ['Incorrect nick or email']]);
                return;
            }

            if ($user->getPassword() !== $password) {
                $this->render('login', ['messages' => ['Incorrect password']]);
                return;
            }

            session_start();
            $_SESSION["id"] = $user->getId();
            $_SESSION["nick"] = $user->getNick();
            $_SESSION["points"] = $user->getPoints();

            $url = "http://$_SERVER[HTTP_HOST]/";
            header("Location: {$url}?page=myDocs");
            return;
        }

        $this->render('login');
    }

    public function logout()
    {
        session_unset();
        session_destroy();

        $this->render('index', ['text' => 'You have been successfully logged out!']);
    }

    public function register()
    {
        
        $user = null;

        if ($this->isPost()) {
            
            
            $userManager = new UserManager();

            if($userManager->checkIfEmailExist($_POST['email'])){
                $this->render('register', [
                    'messages' => ['Wpisany email jest zajęty :(']
                    ]);
                return;
            }

            if($userManager->checkIfNickExist($_POST['nick'])){
                $this->render('register', [
                    'messages' => ['Wpisany nick jest zajęty :(']
                    ]);
                    return;
                }

                $user = new User(
                    null,
                  $_POST['nick'],
                  $_POST['email'],
                  $_POST['password']
                );
            $userManager->createUser($user);
            $this->render('login', [
                'messages' => ['Konto pomyślnie utworzone']
                ]);

            return;
        }

        $this->render('register');
    }

    public function fileNotFound()
    {
        $this->render('fileNotFound');
    }
}