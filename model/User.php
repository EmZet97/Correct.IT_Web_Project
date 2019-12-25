<?php

class User
{
    private $id;
    private $nick;
    private $email;
    private $password;
    private $avatar;
    private $points;

    public function __construct($id, $nick = "", $email = "", $password = "", $points="0", $avatar = "avatar.jpg")
    {
        $this->id = $id;
        $this->nick = $nick;
        $this->email = $email;
        $this->password = $password;
        $this->points = $points;
        $this->avatar = $avatar;
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

    public function getPoints()
    {
        return $this->points;
    }

    public function getAvatar(){
        return $this->avatar;
    }

}