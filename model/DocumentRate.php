<?php

class DocumentRate{
    private $commentId;
    private $comment;
    private $rate;
    private $userId;
    private $userNick;
    private $reward;

    public function __construct($commentId, $comment, $rate, $userNick, $userId, $reward){
        $this->commentId = $commentId;
        $this->comment = $comment;
        $this->rate = $rate;
        $this->userId = $userId;
        $this->userNick = $userNick;
        $this->reward = $reward;
    }

    public function getCommentId(){
        return $this->commentId;
    }

    public function getComment(){
        return $this->comment;
    }

    public function getRate(){
        return intval ($this->rate);
    }
    
    public function getUserId(){
        return $this->userId;
    }
    
    public function getUserNick(){
        return $this->userNick;
    }

    public function getCommentReward(){
        return $this->reward;
    }
}