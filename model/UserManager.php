<?php //>>>>>>>>>>>>>>>>>>>>

require_once 'User.php';
require_once __DIR__.'/DatabaseConnector.php';

class UserManager extends DatabaseConnector
{
    public function getUser(string $email): ?User 
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
            $user['password']
        );
    }

    public function getUsers(): array
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

    public function delete(int $id): void
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


    public function setUser(User $user): void
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
}