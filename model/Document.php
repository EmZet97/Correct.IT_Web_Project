<?php
require_once 'User.php';

class Document
{
    private $owner;
    
    private $id;
    private $title;
    private $category_1;
    private $category_2;
    private $category_3;
    private $language;
    private $last_edit = "0h";

    private $words = 0;
    private $likes = 0;
    private $comments = 0;

    private $path;
    private $content = "";

    private $version;
    private $versionId;
    
    private $checked;

    public function __construct($owner, $id, $title, $version, $language, $path, $last_edit, $category1, $category2, $category3, $versionId, $checked = false)
    {
        $this->owner = $owner;
        //$this->owner_nick = $owner_nick;
        $this->id = $id;
        $this->title = $title;
        $this->version = $version;
        $this->versionId = $versionId;
        $this->path = $path;
        //Set time /////////////////////////
        $now = time(); // or your date as well
        $your_date = strtotime($last_edit);
        $datediff = $now - $your_date;
        $days =  round($datediff / (60 * 60 * 24));

        $this->last_edit = $days . " dni temu";
        $this->language = $language;
        /////////////////////////////////////
        
        $this->category_1 = $category1;
        $this->category_2 = $category2;
        $this->category_3 = $category3;

        $this->checked = $checked;
    }

    public function setCategory($value, $category){
        switch($category){
            case 2:
                $this->category_2 = $value;
                break;
            case 3:
                $this->category_3 = $value;
                break;    
            default:
                $this->category_1 = $value;
        }
    }

    public function getCategory($category){
        switch($category){
            case 2:
                return $this->category_2;
            break;
            case 3:
                return $this->category_3;
                break;    
            default:
                return $this->category_1;
                break;
        }
    }

    public function setContent($content){
        $this->content = $content;
    }

    public function getContent(){
        return $this->content;
    }

    public function getOwnerId()
    {
        return $this->owner->getId();
    }    

    public function getOwnerNick()
    {
        return $this->owner->getNick();
    }

    public function getId()
    {
        return $this->id;
    }    

    public function getTitle()
    {
        return $this->title;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getVersionId()
    {
        return $this->versionId;
    }    

    public function getPath()
    {
        return $this->path;
    }

    public function getWords()
    {
        return $this->words;
    }

    public function setWords($value)
    {
        $this->words = $value;
    }

    public function getLikes()
    {
        return $this->likes;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setLikes($value)
    {
        $this->likes = $value;
    }

    public function setComments($value)
    {
        $this->comments = $value;
    }

    public function getLastEdit()
    {
        return $this->last_edit;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function isChecked(){
        return $this->checked;
    }

}

