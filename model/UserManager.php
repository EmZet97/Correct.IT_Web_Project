<?php //>>>>>>>>>>>>>>>>>>>>

require_once 'User.php';
require_once __DIR__.'/DatabaseConnector.php';

class UserManager extends DatabaseConnector
{
    public function getUser(string $email)
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM users WHERE email = :email
        ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user == false) {
            return null;
        }

        //echo $user['id_user'];
        return new User(
            $user['id_user'],
            $user['nick'],
            $user['email'],
            $user['password'],
            $user['points']
        );
    }

    public function getUsers()
    {
        try {
            $stmt = $this->database->connect()->prepare('SELECT * FROM users');
            $stmt->execute();

            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($users as $user) {
                $result[] = new User(
                    $user['id_user'],
                    $user['nick'],
                    $user['email'],
                    $user['password']
                );
            }
    
            return $result;
        }
        catch(PDOException $e) {
            die();
        }
    }

    public function createUser($user)
    {
        $nick = $user->getNick();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $points = $user->getPoints();
        $avatar = $user->getAvatar();
        // DOCUMENT
        $stmt = $this->database->connect()->prepare('
                INSERT INTO `users` (`nick`, `email`, `password`, `points`, `avatar`) 
                VALUES (:nick, :email, :password, :points, :avatar);
            ');
            $stmt->bindParam(':nick', $nick, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':points', $points, PDO::PARAM_STR);
            $stmt->bindParam(':avatar', $avatar, PDO::PARAM_STR);
            $stmt->execute();            
    }

    public function checkIfEmailExist(string $email) 
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM users WHERE email = :email
        ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user == false) {
            return false;
        }

        return true;
    }

    
    public function checkIfNickExist(string $nick) 
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM users WHERE nick = :nick
        ');
        $stmt->bindParam(':nick', $nick, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user == false) {
            return false;
        }
        
        return true;
    }

    public function delete(int $id)
    {
        try {
            $stmt = $this->database->connect()->prepare('DELETE FROM users WHERE id = :id;');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        catch(PDOException $e) {
            die();
        }
    }


    public function setUser(User $user)
    {
        $name = $user->getName();
        $surname = $user->getSurname();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $role = $user->getRole();

        try {
            $stmt = $this->database->connect()->prepare('
                INSERT INTO `users` (`name`, `surname`, `email`, `password`, `role`) 
                VALUES (:name, :surname, :email, :password, :role);
            ');
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':surname',$surname, PDO::PARAM_STR);
            $stmt->bindParam(':email',$email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            $stmt->execute();
        }
        catch(PDOException $e) {
            die();
        }
    }

    public function rewardUser($userId, $points){
        $stmt = $this->database->connect()->prepare('
                UPDATE `users`
                SET `points` = `points` + :points
                WHERE `id_user` = :userId;
            ');
            $stmt->bindParam(':points', $points, PDO::PARAM_STR);
            $stmt->bindParam(':userId',$userId, PDO::PARAM_STR);
            
            $stmt->execute();
    }
}