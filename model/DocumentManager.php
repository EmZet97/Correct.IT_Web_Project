<?php

require_once 'Document.php';
require_once __DIR__.'/DatabaseConnector.php';
require_once 'User.php';

class DocumentManager extends DatabaseConnector
{
    public function getDocument(string $documentID): ?Document 
    {
        $stmt = $this->database->connect()->prepare('
        SELECT u.id_user, u.nick, d.id_document, d.title, v.version_number, v.create_time, d.path, d.id_category_1, d.id_category_2, d.id_category_3, l.name AS "language"
        FROM documents AS d, version AS v, users AS u, languages as l
        WHERE d.id_document = :documentID 
        AND d.id_document = v.id_document 
        AND d.id_language = d.id_language
        ORDER BY v.id_version DESC 
        LIMIT 1
        ');
        $stmt->bindParam(':documentID', $documentID, PDO::PARAM_STR);
        $stmt->execute();

        $document = $stmt->fetch(PDO::FETCH_ASSOC);

        if($document == false) {
            return null;
        }

        //echo $user['id_user'];
        $doc = new Document(
            $document['id_user'],
            $document['nick'],
            $document['id_document'],
            $document['title'],
            $document['version_number'],
            $document['language'],
            $document['path'],
            $document['create_time']
        );
        $doc->setCategory(1, $this->getCategory($document['id_category_1']));
        $doc->setCategory(2, $this->getCategory($document['id_category_2']));
        $doc->setCategory(3, $this->getCategory($document['id_category_3']));

        return $doc;
    }

    private function getCategory(string $category) : string{
        $stmt = $this->database->connect()->prepare('
        SELECT category.name
        FROM category
        WHERE category.id_category = :category;
        ');
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->execute();

        $cat = $stmt->fetch(PDO::FETCH_ASSOC);

        if($cat == false) {
            return "";
        }
        
        return $cat["name"];
    }

    public function getUserDocuments(string $userID): array
    {
        
            $stmt = $this->database->connect()->prepare('
            SELECT DISTINCT u.id_user, u.nick, d.id_document, d.title, v.version_number, v.id_version, v.create_time, d.path, 
            (SELECT category.name FROM category WHERE category.id_category = d.id_category_1) AS "category1",
            (SELECT category.name FROM category WHERE category.id_category = d.id_category_2) AS "category2",
            (SELECT category.name FROM category WHERE category.id_category = d.id_category_3) AS "category3",
            l.name AS "language"
            FROM documents AS d, version AS v, users AS u, languages as l, category AS c
            WHERE d.id_document = v.id_document 
            AND d.id_owner = 1
            AND v.id_version = (SELECT max(version.id_version) FROM version, documents WHERE version.id_document = d.id_document)
            AND d.id_language = d.id_language
            ORDER BY v.id_version DESC
            ');
            $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
            $stmt->execute();

            $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($documents as $document) {
                $owner = new User($document['id_user'],$document['nick']);
                $doc = new Document(
                    $owner,
                    $document['id_document'],
                    $document['title'],
                    $document['version_number'],
                    $document['language'],
                    $document['path'],
                    $document['create_time'],
                    $document['category1'],
                    $document['category2'],
                    $document['category3']
                );
                $doc->setLikes($this->getVersionAverageRate($document['id_version']));
                $doc->setComments($this->getVersionCommentsCount($document['id_version']));
                
                $result[] = $doc;
            }
    
            return $result;
        
        
    }

    public function getVersionAverageRate(string $versionID): ?string 
    {
        $stmt = $this->database->connect()->prepare('
        SELECT CAST(AVG(r.rate) AS DECIMAL(2,1)) AS "average_rate" FROM rate r, version v
        WHERE v.id_version = :versionID
        AND r.id_version = v.id_version            
        ');
        $stmt->bindParam(':versionID', $versionID, PDO::PARAM_STR);
        $stmt->execute();

        $document = $stmt->fetch(PDO::FETCH_ASSOC);

        if($document == false) {
            return null;
        }

        
        return $document['average_rate'];
    }

    public function getVersionCommentsCount(string $versionID): ?string 
    {
        $stmt = $this->database->connect()->prepare('
        SELECT COUNT(c.id_comment) AS "comments_count" FROM comment c
        WHERE c.id_version = :versionID            
        ');
        $stmt->bindParam(':versionID', $versionID, PDO::PARAM_STR);
        $stmt->execute();

        $document = $stmt->fetch(PDO::FETCH_ASSOC);

        if($document == false) {
            return null;
        }

        
        return $document['comments_count'];
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