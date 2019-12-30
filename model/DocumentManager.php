<?php

require_once 'Document.php';
require_once 'FileManager.php';
require_once __DIR__.'/DatabaseConnector.php';
require_once 'User.php';

class DocumentManager extends DatabaseConnector
{
    public $path_project_connector = "doc";
    public $path_version_connector = "ver";

    public function getDocument(string $documentID): ?Document 
    {
        $stmt = $this->database->connect()->prepare('
        SELECT DISTINCT u.id_user, u.nick, d.id_document, v.words, d.title, v.version_number, v.id_version, v.create_time, d.path, 
            (SELECT category.name FROM category WHERE category.id_category = d.id_category_1) AS "category1",
            (SELECT category.name FROM category WHERE category.id_category = d.id_category_2) AS "category2",
            (SELECT category.name FROM category WHERE category.id_category = d.id_category_3) AS "category3",
            l.name AS "language"
            FROM documents AS d, version AS v, users AS u, languages as l, category AS c
            WHERE d.id_document = v.id_document 
            AND d.id_document = :documentID
            AND d.id_owner = u.id_user
            AND v.id_version = (SELECT max(version.id_version) FROM version, documents WHERE version.id_document = d.id_document)
            AND d.id_language = d.id_language
            ORDER BY v.id_version DESC
            ');
        $stmt->bindParam(':documentID', $documentID, PDO::PARAM_STR);
        $stmt->execute();

        $document = $stmt->fetch(PDO::FETCH_ASSOC);

        if($document == false) {
            return null;
        }

        //echo $user['id_user'];
        $owner = new User();
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

    public function getUserDocuments(string $userID)
    {        
            $stmt = $this->database->connect()->prepare('
            SELECT DISTINCT u.id_user, u.nick, d.id_document, v.words, d.title, v.version_number, v.id_version, v.create_time, d.path, 
            (SELECT category.name FROM category WHERE category.id_category = d.id_category_1) AS "category1",
            (SELECT category.name FROM category WHERE category.id_category = d.id_category_2) AS "category2",
            (SELECT category.name FROM category WHERE category.id_category = d.id_category_3) AS "category3",
            l.name AS "language"
            FROM documents AS d, version AS v, users AS u, languages as l, category AS c
            WHERE d.id_document = v.id_document 
            AND d.id_owner = :userID
            AND d.id_owner = u.id_user
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
                $doc->setWords($document['words']);
                
                $result[] = $doc;
            }
    
            if(isset($result))
                return $result;

            return null;
        
        
    }

    public function getOtherUsersDocuments(string $userID): array
    {
        
            $stmt = $this->database->connect()->prepare('
            SELECT DISTINCT u.id_user, u.nick, d.id_document, v.words, d.title, v.version_number, v.id_version, v.create_time, d.path, 
            (SELECT category.name FROM category WHERE category.id_category = d.id_category_1) AS "category1",
            (SELECT category.name FROM category WHERE category.id_category = d.id_category_2) AS "category2",
            (SELECT category.name FROM category WHERE category.id_category = d.id_category_3) AS "category3",
            l.name AS "language"
            FROM documents AS d, version AS v, users AS u, languages as l, category AS c
            WHERE d.id_document = v.id_document 
            AND d.id_owner <> :userID
            AND d.id_owner = u.id_user
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
                $doc->setWords($document['words']);
                
                $result[] = $doc;
            }
            if(isset($result))
                return $result;

            return $result = [];
        
        
    }

    public function createDocument($document, $content): void
    {
        $userID = $document->getOwnerId();
        $title = $document->getTitle();
        $cat1 = $document->getCategory(1);
        $cat2 = $document->getCategory(2);
        $cat3 = $document->getCategory(3);
        $docID = $document->getId();
        $path = "user" . $userID;
        $version = 1;
        $words = str_word_count($content);

        // DOCUMENT
        $stmt = $this->database->connect()->prepare('
                INSERT INTO `documents` (`id_owner`, `id_language`, `title`, `path`, `words`, `id_category_1`, `id_category_2`, `id_category_3`) 
                VALUES (:userID, 1, :title, :path, :words, :cat1, :cat2, :cat3);
            ');
            $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
            $stmt->bindParam(':title',$title, PDO::PARAM_STR);
            $stmt->bindParam(':path',$path, PDO::PARAM_STR);
            $stmt->bindParam(':words', $words, PDO::PARAM_STR);
            $stmt->bindParam(':cat1', $cat1, PDO::PARAM_STR);
            $stmt->bindParam(':cat2', $cat2, PDO::PARAM_STR);
            $stmt->bindParam(':cat3', $cat3, PDO::PARAM_STR);
            $stmt->execute();
            
        $documentId = $this->getUserLastDocumentId($userID);
        $create_time = date("Y-m-d");
        // VERSION
        $stmt = $this->database->connect()->prepare('
                INSERT INTO `version` (`id_document`, `version_number`, `create_time`, `words`) 
                VALUES (:id_document, :version_number, :create_time, :words);
            ');
        $stmt->bindParam(':id_document', $documentId, PDO::PARAM_STR);
        $stmt->bindParam(':version_number',$version, PDO::PARAM_STR);
        $stmt->bindParam(':create_time',$create_time, PDO::PARAM_STR);
        $stmt->bindParam(':words',$words, PDO::PARAM_STR);
        $stmt->execute();

        // WRITE CONTENT TO FILE
        $file_name =  "Documents/" . $path . $this->path_project_connector . $documentId . $this->path_version_connector . $version;
        $file_manager = new FileManager();
        $file_manager->writeFile($file_name, $content);
        
    }

    public function updateDocument($document, $content): void
    {
        $userID = $document->getOwnerId();
        $title = $document->getTitle();
        $cat1 = $document->getCategory(1);
        $cat2 = $document->getCategory(2);
        $cat3 = $document->getCategory(3);
        $docID = $document->getId();
        $path = "user" . $userID;
        $version = intval($this->getDocumentLastVersion($docID)) + 1;
        $words = str_word_count($content);

            
        $documentId = $this->getUserLastDocumentId($userID);
        $create_time = date("Y-m-d");
        // VERSION
        $stmt = $this->database->connect()->prepare('
                INSERT INTO `version` (`id_document`, `version_number`, `create_time`, `words`) 
                VALUES (:id_document, :version_number, :create_time, :words);
            ');
        $stmt->bindParam(':id_document', $documentId, PDO::PARAM_STR);
        $stmt->bindParam(':version_number',$version, PDO::PARAM_STR);
        $stmt->bindParam(':create_time',$create_time, PDO::PARAM_STR);
        $stmt->bindParam(':words',$words, PDO::PARAM_STR);
        $stmt->execute();

        // WRITE CONTENT TO FILE
        $file_name =  "Documents/" . $path . $this->path_project_connector . $documentId . $this->path_version_connector . $version;
        $file_manager = new FileManager();
        $file_manager->writeFile($file_name, $content);
        
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

    public function getUserLastDocumentId(string $userID): ?string 
    {
        $stmt = $this->database->connect()->prepare('
        SELECT documents.id_document AS "value"
        FROM documents 
        WHERE documents.id_owner = :userID
        ORDER BY documents.id_document DESC 
        LIMIT 1          
        ');
        $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
        $stmt->execute();

        $document = $stmt->fetch(PDO::FETCH_ASSOC);

        if($document == false) {
            return null;
        }

        
        return $document['value'];
    }

    public function getDocumentLastVersion(string $documentID): ?string 
    {
        $stmt = $this->database->connect()->prepare('
        SELECT MAX(v.version_number) AS "value"
        FROM version v
        WHERE v.id_document = :documentID          
        ');
        $stmt->bindParam(':documentID', $documentID, PDO::PARAM_STR);
        $stmt->execute();

        $document = $stmt->fetch(PDO::FETCH_ASSOC);

        if($document == false) {
            return null;
        }

        
        return $document['value'];
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

    public function deleteDocument($documentId): void
    {
        try {
            $stmt = $this->database->connect()->prepare('DELETE FROM documents WHERE id_document = :id;');
            $stmt->bindParam(':id', $documentId, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $this->database->connect()->prepare('DELETE FROM version WHERE id_document = :id;');
            $stmt->bindParam(':id', $documentId, PDO::PARAM_INT);
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