<?php
require_once 'User.php';

class Document
{
    private $owner_id;
    private $owner_nick;
    
    private $id;
    private $title;
    private $category1;
    private $category2;
    private $category3;
    private $content;

    private $version;
    

    public function __construct($id, $nick, $email, $password)
    {
        $this->id = $id;
        $this->nick = $nick;
        $this->email = $email;
        $this->password = $password;
    }

    public function getNick()
    {
        return $this->nick;
    }

    public function setNick($nick): void
    {
        $this->nick = $nick;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password): void
    {
        $this->password = md5($password);
    }

    public function getId()
    {
        return $this->id;
    }

}