<?php

require_once 'Document.php';
require_once 'FileManager.php';
require_once __DIR__.'/DatabaseConnector.php';
require_once 'User.php';

class DocumentManager extends DatabaseConnector
{
    public $path_project_connector = "doc";
    public $path_version_connector = "ver";

    public function checkIfIsDocumentOwner($documentID, $userID){
        $stmt = $this->database->connect()->prepare('
        SELECT d.id_document
            FROM documents AS d
            AND d.id_document = :documentID
            AND d.id_owner = :userID
            ');
        $stmt->bindParam(':documentID', $documentID, PDO::PARAM_STR);
        $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
        $stmt->execute();

        $document = $stmt->fetch(PDO::FETCH_ASSOC);

        if($document == false) {
            return false;
        }
  
        return true;
    }
    
    public function getDocument(string $documentID) {
        // SQL used in View
        $sql1 = '
        SELECT DISTINCT u.id_user, u.nick, d.id_document, v.words, d.title, v.version_number, v.id_version, v.create_time, d.path, 
            (CategoryIdToName(d.id_category_1)) AS "category1",
            (CategoryIdToName(d.id_category_2)) AS "category2",
            (CategoryIdToName(d.id_category_3)) AS "category3",
            l.name AS "language"
            FROM documents AS d, version AS v, users AS u, languages as l, category AS c
            WHERE d.id_document = v.id_document 
            AND d.id_document = :documentID
            AND d.id_owner = u.id_user
            AND v.id_version = (SELECT max(version.id_version) FROM version, documents WHERE version.id_document = d.id_document)
            AND l.id_language = d.id_language
            ORDER BY v.id_version DESC
            ';
        // View Select query
        $sql2 = '
            SELECT * FROM userdocumentsview
            WHERE userdocumentsview.documentId = :documentID
            ';
        $stmt = $this->database->connect()->prepare($sql2);
        $stmt->bindParam(':documentID', $documentID, PDO::PARAM_STR);
        $stmt->execute();

        $document = $stmt->fetch(PDO::FETCH_ASSOC);

        if($document == false) {
            return null;
        }

        //echo $user['id_user'];
        $owner = new User($document['userId']);
        $doc = new Document(
            $owner,
            $document['documentId'],
            $document['title'],
            $document['versionNumber'],
            $document['language'],
            $document['docPath'],
            $document['createTime'],
            $document['c1'],
            $document['c2'],
            $document['c3'],
            $document['versionId']
    );
        
        
        return $doc;
    }

    private function getCategory(string $category) {
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
        // SQL used in SQL VIEW
            $sql1 = '
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
            AND l.id_language = d.id_language
            ORDER BY v.id_version DESC
            ';

            // USE of SQL VIEW
            $sql2 = '
            SELECT * FROM userdocumentsview
            WHERE userdocumentsview.userId = :userID
            ';

            $stmt = $this->database->connect()->prepare($sql2);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
            $stmt->execute();

            $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($documents as $document) {
                $owner = new User($document['userId'],$document['nick']);
                $doc = new Document(
                    $owner,
                    $document['documentId'],
                    $document['title'],
                    $document['versionNumber'],
                    $document['language'],
                    $document['docPath'],
                    $document['createTime'],
                    $document['c1'],
                    $document['c2'],
                    $document['c3'],
                    $document['versionId']
                );
                $doc->setLikes($this->getVersionAverageRate($document['versionId']));
                $doc->setComments($this->getVersionCommentsCount($document['versionId']));
                $doc->setWords($document['words']);
                
                $result[] = $doc;
            }
    
            if(isset($result))
                return $result;

            return null;
        
        
    }

    public function getOtherUsersDocuments(string $userID)
    {
        $sql1 = '
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
        AND l.id_language = d.id_language
        ORDER BY v.id_version DESC
        ';
        // View Select query
        $sql2 = '
            SELECT * FROM userdocumentsview
            WHERE userdocumentsview.userId <> :userID
            ';
            $stmt = $this->database->connect()->prepare($sql2);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
            $stmt->execute();

            $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

            

            foreach ($documents as $document) {
                $commented = $this->getUserComment($document['versionId'], $userID) == null ? false : true;
                $owner = new User($document['userId'],$document['nick']);
                $doc = new Document(
                    $owner,
                    $document['documentId'],
                    $document['title'],
                    $document['versionNumber'],
                    $document['language'],
                    $document['docPath'],
                    $document['createTime'],
                    $document['c1'],
                    $document['c2'],
                    $document['c3'],
                    $document['versionId'],
                    $commented
                );
                $doc->setLikes($this->getVersionAverageRate($document['versionId']));
                $doc->setComments($this->getVersionCommentsCount($document['versionId']));
                $doc->setWords($document['words']);
                
                $result[] = $doc;
            }
            if(isset($result))
                return $result;

            return $result = [];
        
        
    }

    public function getOtherUsersDocumentsNotChecked(string $userID)
    {   
        // View Select query
        $sql = '
            SELECT * FROM userdocumentsview
            WHERE userdocumentsview.userId <> :userID
            ';
            $stmt = $this->database->connect()->prepare($sql);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
            $stmt->execute();

            $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

            

            foreach ($documents as $document) {
                $commented = $this->getUserComment($document['versionId'], $userID) == null ? false : true;
                if($commented)
                    continue;
                $owner = new User($document['userId'],$document['nick']);
                $doc = new Document(
                    $owner,
                    $document['documentId'],
                    $document['title'],
                    $document['versionNumber'],
                    $document['language'],
                    $document['docPath'],
                    $document['createTime'],
                    $document['c1'],
                    $document['c2'],
                    $document['c3'],
                    $document['versionId'],
                    $commented
                );
                $doc->setLikes($this->getVersionAverageRate($document['versionId']));
                $doc->setComments($this->getVersionCommentsCount($document['versionId']));
                $doc->setWords($document['words']);
                
                $result[] = $doc;
            }
            if(isset($result))
                return $result;

            return $result = [];
        
        
    }
        
        

    public function getUserComment($versionId, $userId){
        $stmt = $this->database->connect()->prepare('
        SELECT c.id_comment, u.id_user, u.nick, c.comment, r.rate, c.reward
        FROM users u, comment c, rate r
        WHERE u.id_user = c.id_user
        AND u.id_user = r.id_user
        AND u.id_user = :userId
        AND c.id_version = :versionId
        AND c.id_version = r.id_version
            ');
        $stmt->bindParam(':versionId', $versionId, PDO::PARAM_STR);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
        $stmt->execute();

        $comment = $stmt->fetch(PDO::FETCH_ASSOC);

        if($comment == false) {
            return null;
        }
  
        $documentRate = new DocumentRate($comment['id_comment'],$comment['comment'],$comment['rate'],$comment['nick'],$comment['id_user'], $comment['reward']);

        return $documentRate;
    }

    public function rewardComment($commentId, $reward){
        $stmt = $this->database->connect()->prepare('
        UPDATE comment
        SET reward = :reward
        WHERE id_comment = :commentId;
            ');
        $stmt->bindParam(':reward', $reward, PDO::PARAM_STR);
        $stmt->bindParam(':commentId', $commentId, PDO::PARAM_STR);
        $stmt->execute();

    }

    public function getVersionComments($versionId){

        // Query from view
        $sql = '
        SELECT * FROM commentsview
        WHERE commentsview.versionId = :versionId
        ';

        $sql_old = '
        SELECT c.id_comment, u.id_user, u.nick, c.comment, r.rate, c.reward
        FROM users u, comment c, rate r
        WHERE u.id_user = c.id_user
        AND u.id_user = r.id_user
        AND c.id_version = :versionId
        AND c.id_version = r.id_version
            ';
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->bindParam(':versionId', $versionId, PDO::PARAM_STR);
        $stmt->execute();

        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($comments as $comment) {
            $rate = new DocumentRate(
                $comment['commentId'],
                $comment['comment'],
                $comment['rate'],
                $comment['nick'],
                $comment['userId'],
                $comment['reward']
            );
            
            $result[] = $rate;
        }
        if(isset($result))
            return $result;

        return $result = [];
    }

    public function rateDocument($userId, $versionId, $comment, $rate){

        $connection = $this->database->connect();
        $connection->beginTransaction();

        // EXECUTE TRANSACTION
        try{
            $stmt = $connection->prepare('
                INSERT INTO `rate` (`id_version`, `id_user`, `rate`) 
                VALUES (:id_version, :userId, :rate);
            ');
            $stmt->bindParam(':id_version',$versionId, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
            $stmt->bindParam(':rate', $rate, PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $connection->prepare('
                INSERT INTO `comment` (`id_version`, `id_user`, `comment`) 
                VALUES (:id_version, :userId, :comment);
            ');
            $stmt->bindParam(':id_version',$versionId, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
            $stmt->bindParam(':comment',$comment, PDO::PARAM_STR);
            $stmt->execute();

            $connection->commit();
        }
        catch(PDOException $e){
            $connection->rollBack();
            return;
        }

        
    }  
    
    public function changeDocumentRate($userId, $versionId, $comment, $rate){

        $connection = $this->database->connect();
        $connection->beginTransaction();

        // EXECUTE TRANSACTION
        try{
            $stmt = $connection->prepare('
                UPDATE `rate` 
                SET `rate` = :rate 
                WHERE id_user = :userId 
                AND id_version = :id_version
            ');
            $stmt->bindParam(':id_version',$versionId, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
            $stmt->bindParam(':rate', $rate, PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $connection->prepare('
                UPDATE `comment` 
                SET `comment` = :comment 
                WHERE id_user = :userId 
                AND id_version = :id_version
            ');
            $stmt->bindParam(':id_version',$versionId, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
            $stmt->bindParam(':comment',$comment, PDO::PARAM_STR);
            $stmt->execute();

            $connection->commit();
        }
        catch(PDOException $e){
            $connection->rollBack();
            return;
        }

        
    }

    public function createDocument($document, $content)
    {
        $userID = $document->getOwnerId();
        $title = $document->getTitle();
        $cat1 = $document->getCategory(1);
        $cat2 = $document->getCategory(2);
        $cat3 = $document->getCategory(3);
        $docID = $document->getId();
        $language = $document->getLanguage();
        $path = "user" . $userID;
        $version = 1;
        $words = str_word_count($content);

       
        $connection = $this->database->connect();
        $connection->beginTransaction();

        // EXECUTE TRANSACTION
        try{
            $stmt = $connection->prepare('
                INSERT INTO `documents` (`id_owner`, `id_language`, `title`, `path`, `words`, `id_category_1`, `id_category_2`, `id_category_3`) 
                VALUES (:userID, :language, :title, :path, :words, :cat1, :cat2, :cat3);
            ');
            $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
            $stmt->bindParam(':title',$title, PDO::PARAM_STR);
            $stmt->bindParam(':path',$path, PDO::PARAM_STR);
            $stmt->bindParam(':words', $words, PDO::PARAM_STR);
            $stmt->bindParam(':cat1', $cat1, PDO::PARAM_STR);
            $stmt->bindParam(':cat2', $cat2, PDO::PARAM_STR);
            $stmt->bindParam(':cat3', $cat3, PDO::PARAM_STR);
            $stmt->bindParam(':language', $language, PDO::PARAM_STR);
            $stmt->execute();
            
            $documentId = $this->getUserLastDocumentId($userID);
            $create_time = date("Y-m-d");
            // VERSION
            $stmt = $connection->prepare('
                    INSERT INTO `version` (`id_document`, `version_number`, `create_time`, `words`) 
                    VALUES (:id_document, :version_number, :create_time, :words);
                ');
            $stmt->bindParam(':id_document', $documentId, PDO::PARAM_STR);
            $stmt->bindParam(':version_number',$version, PDO::PARAM_STR);
            $stmt->bindParam(':create_time',$create_time, PDO::PARAM_STR);
            $stmt->bindParam(':words',$words, PDO::PARAM_STR);
            $stmt->execute();

            //COMMIT TRANSACTION
            $connection->commit();
        }
        catch(PDOException $e){
            $connection->rollBack();
            return;
        }

        // WRITE CONTENT TO FILE
        $file_name =  "Documents/" . $path . $this->path_project_connector . $documentId . $this->path_version_connector . $version;
        $file_manager = new FileManager();
        $file_manager->writeFile($file_name, $content);
        
    }

    public function createNewDocumentVersion($document, $content)
    {
        $userID = $document->getOwnerId();
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

    public function updateDocumentVersion($document, $content)
    {
        $userID = $document->getOwnerId();
        $docID = $document->getId();
        $path = "user" . $userID;
        $version = $document->getVersion();
        $words = str_word_count($content);
        $versionId = $document->getVersionId();

        // VERSION
        $stmt = $this->database->connect()->prepare('
                UPDATE `version`
                SET `words` = :words 
                WHERE id_version = :versionId;
            ');
        $stmt->bindParam(':words', $words, PDO::PARAM_STR);
        $stmt->bindParam(':versionId',$versionId, PDO::PARAM_STR);
        $stmt->execute();

        // WRITE CONTENT TO FILE
        $file_name =  "Documents/" . $path . $this->path_project_connector . $docID . $this->path_version_connector . $version;
        $file_manager = new FileManager();
        $file_manager->writeFile($file_name, $content);
        
    }

    public function getVersionAverageRate(string $versionID) 
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

    public function getUserLastDocumentId(string $userID) 
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

    public function getDocumentLastVersion(string $documentID) 
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

    public function getVersionCommentsCount(string $versionID) 
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

    public function getLanguages(){
        $sql = 'SELECT * FROM languages';
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute();

        $languages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($languages == false) {
            return null;
        }

        
        return $languages;
    }

    public function deleteDocument($documentId)
    {
        $connection = $this->database->connect();
        $connection->beginTransaction();

        // EXECUTE TRANSACTION
        try{
            $stmt = $connection->prepare('DELETE FROM documents WHERE id_document = :id;');
            $stmt->bindParam(':id', $documentId, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $connection->prepare('DELETE FROM version WHERE id_document = :id;');
            $stmt->bindParam(':id', $documentId, PDO::PARAM_INT);
            $stmt->execute();
            //COMMIT TRANSACTION
            $connection->commit();
        }
        catch(PDOException $e){
            $connection->rollBack();
            return;
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
}