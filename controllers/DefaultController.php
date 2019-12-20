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
        $this->render('register');
        return;
        $mapper = new UserMapper();
        $user = null;

        if ($this->isPost()) {
            $user = new User(
              $_POST['name'],
              $_POST['surname'],
              $_POST['email'],
              md5($_POST['password'])
            );
            $mapper->setUser($user);

            $this->render('login', [
                'message' => ['You have been successful registrated! Please login.']
                ]);
        }

        $this->render('register');
    }

    public function fileNotFound()
    {
        $this->render('fileNotFound');
    }
}