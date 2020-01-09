<?php

require_once "AppController.php";

require_once __DIR__.'/../model/DocumentRate.php';
require_once __DIR__.'/../model/Document.php';
require_once __DIR__.'/../model/DocumentManager.php';
require_once __DIR__.'/../model/CategoriesManager.php';


class DocsController extends AppController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->checkSession();
        //$text = 'Hello there! Its docs part';
        $text = 'Hello there 👋';
        $this->render('my', ['text' => $text]);
    }

    // PANEL DISPLAYING DOCUMENTS OF OTHER USERS
    public function browseDocs()
    {
        $this->checkSession();

        $docManager = new DocumentManager(); 
        
        
        $id = $_SESSION["id"];
        $checked = true;
        
        if(isset($_GET['checked']) && $_GET['checked'] == 'false'){
            $docs = $docManager->getOtherUsersDocumentsNotChecked($id);
            $checked = false;
        }
        else{
            // GET DOCUMENTS OF OTHER USERS
            $docs = $docManager->getOtherUsersDocuments($id);
        }

        // START PANEL
        $this->render('browseDocs', ['docs' => $docs, 'checked' => $checked]);
        return;
    }

    // DOCUMENT CREATION PANEL
    public function createDoc()
    {
        $this->checkSession();

        // GET CATEGORIES FROM DATABASE
        $categoriesManager = new CategoriesManager();
        $categories = $categoriesManager->getCategories();
        $docManager = new DOcumentManager();
        $languages = $docManager->getLanguages();

        // SEND CATEGORIES TO PANEL
        $this->render('createDoc', ['categories' => $categories, 'languages' => $languages]);

        return;
    }

    // DOCUMENT DELETION FUNCTION
    public function deleteDoc()
    {
        $this->checkSession();

        // GET USER ID
        $docId = $_GET['id'];
        $docManager = new DocumentManager();
        
        // DELETE DOCUMENT FROM DATABASE
        $docManager->deleteDocument($docId);

        // START MY DOCS PANEL
        $this->myDocs();
        return;
    }

    // DOCUMENT CREATION FUNCTION
    public function createDoc_Execute(){

        $this->checkSession();

        if (true) {
            
            // GET POSTED DATA
            $title = $_POST['title'];
            $category1 = $_POST['c1'];
            $category2 = $_POST['c2'];
            $category3 = $_POST['c3'];
            $content = $_POST['content'];
            $language = $_POST['language'];
            
            // GET USER ID FROM SESSION
            $id = $_SESSION["id"];         

            $docManager = new DocumentManager();

            // CREATE NEW USER OBJECT
            $owner = new User($id);

            //CREATE NEW DOCUMENT OBJECT
            $document = new Document($owner, null, $title, null, $language, null, null, $category1, $category2, $category3, false);

            // CREATE DOCUMENT IN DATABASE
            $docManager->createDocument($document, $content);            

        }
        else{
            
        }

        //OPEN MY DOCUMENTS PANEL AFTER ALL
        header("Location: {$url}?page=myDocs");
        return;
    }


    // DOCUMENT EDITION FUNCTION
    public function editDoc_Execute(){

        $this->checkSession();

        if (true) {

            // GET POSTED DATA
            $content = $_POST['content'];
            $docId = $_POST['docID'];
            
            // GET USER ID FROM SESSION
            $id = $_SESSION["id"];     

            $docManager = new DocumentManager();
            // CREATE USER OBJECT
            $owner = new User($id);

            // CREATE DOCUMENT OBJECT
            $document = $docManager->getDocument($docId);
            //$document = new Document($owner, $docId, "", null, 1, null, null, "", "", "", false);

            // UPDATE DOCUMENT IN DATABASE
            $docManager->createNewDocumentVersion($document, $content);            

        }
        else{
            //echo "false";
        }

        // OPEN MY DOCUMENTS PAGE
        header("Location: {$url}?page=myDocs");
        return;
    }


    // DOCUMENT SAVE FUNCTION - AJAX
    public function saveDoc_Execute(){
        
        //$this->checkSession();


        // GET POSTED DATA
        $content = $_POST['content'];
        $docId = $_POST['docID'];
        $id = $_POST['userID'];

        $docManager = new DocumentManager();
        
        // CREATE DOCUMENT OBJECT
        $document = $docManager->getDocument($docId);
        //$document = new Document($owner, $docId, "", null, 1, null, null, "", "", "", false);
        
        // UPDATE DOCUMENT IN DATABASE
        $docManager->updateDocumentVersion($document, $content);            
        
        
        //http_response_code(404);
        return;
    }

    // Reward USER FOR COMMENT
    public function reward_Execute(){

        $userId = $_POST['userID'];
        $points = $_POST['points'];
        $id = $_POST['commentID'];
        $dif = $_POST['dif'];

        $userManager = new UserManager();

        $userManager->rewardUser($userId, $points);

        $docManager = new DocumentManager();
        $docManager->rewardComment($id, $dif);

        // OPEN MY DOCUMENTS PAGE
        
        //header("Location: {$url}?page=myDocs");
        //echo $userId . " - " . $points;
        return;
    }

    // DOCUMENT CORRECTION FUNCTION
    public function correctDoc_Execute(){

        $this->checkSession();

        if (true) {
            
            // GET POSTED DATA
            $docId = $_POST['docID'];
            $comment = $_POST['comment'];
            $rate = $_POST['rate'];
            $version = $_POST['docVersion'];

            // GET USER ID FROM SESSION
            $id = $_SESSION["id"];

            $docManager = new DocumentManager();

            // CHECK IF USER CHECKED THAT DOCUMENT VERSION BEFORE
            if($docManager->getUserComment($version, $id) == null){
                // CREATE COMMENT AND RATE IN DATABASE
                $docManager->rateDocument($id, $version, $comment, $rate);                
            }
            else{
                // UPDATE COMMENT IN DATABASE
                $docManager->changeDocumentRate($id, $version, $comment, $rate);                        
            }
                

        }
        else{
            //echo "false";
        }

        // OPEN BROWSE DOCUMENTS PANEL
        header("Location: {$url}?page=browseDocs");
        return;
    }

    // DOCUMENT CORRECTION PANEL
    public function correctDoc()
    {

        $this->checkSession();

        // GET USER ID FROM SESSION
        $docId = $_GET['id'];

        $docManager = new DocumentManager();

        //GET DOCUMENT DATA FROM DATABASE
        $doc = $docManager->getDocument($docId);

        // GET FILE CONTENT
        $file_name =  "Documents/" . $doc->getPath() . $docManager->path_project_connector . $docId . $docManager->path_version_connector . $doc->getVersion();
        $file_manager = new FileManager();
        $content = $file_manager->readFile($file_name);

        //GET VERSION COMMENT & CHECK IF HAS COMMENT
        $docRate = $docManager->getUserComment($doc->getVersionId(), $_SESSION["id"]);
        if($docRate == null){
            // CREATE DOCUMENT RATE OBJECT
            $docRate = new DocumentRate(0, "", 1, "", "", 0);
        }

        // FINALIZE DOCUMENT OBJECT
        $doc->setContent($content);

        // SEND DOCUMENT & RATE OBJECT TO PANEL
        $this->render('correctDoc', ['doc' => $doc, 'rate' => $docRate]);
        return;
    }

    // DOCUMENT EDITION PANEL
    public function editDoc()
    {
        $this->checkSession();

        // GET USER ID FROM SESSION
        $docId = $_GET['id'];

        $docManager = new DocumentManager();

        // GET DOCUMENT FROM DATABASE
        $doc = $docManager->getDocument($docId);

        // CHECK IF USER IS OWNER OF DOCUMENT
        if($doc->getOwnerId() != $_SESSION["id"]){
            // IF NOT REDIRECT TO MY DOCS PAGE
            header("Location: {$url}?page=myDocs");
            return;
        }

        // GET COMMENTS
        $comments = $docManager->getVersionComments($doc->getVersionId());

        // GET FILE CONTENT
        $file_name =  "Documents/" . $doc->getPath() . $docManager->path_project_connector . $docId . $docManager->path_version_connector . $doc->getVersion();
        $file_manager = new FileManager();
        $content = $file_manager->readFile($file_name);

        // SET THAT CONTENT TO DOC OBJECT
        $doc->setContent($content);
        
        // START PANEL AND SEND DOCUMENT OBJECT
        $this->render('editDoc', ['doc' => $doc, 'comments' => $comments]);

        return;
    }


    // MY DOCUMENTS PANEL
    public function myDocs()
    {
        $this->checkSession();

        $docManager = new DocumentManager();      
        
        // GET USER ID FROM SESSION
        $id = $_SESSION["id"];

        // GET DOCUMENTS FROM DATABASE
        $docs = $docManager->getUserDocuments($id);

        // START PANEL AND SEND DOCUMENTS ARRAY
        $this->render('myDocs', ['docs' => $docs]);

        return;
    }

    private function checkSession(){
        // CHECK ID USER SESSION IS STARTED
        if(!isset($_SESSION["id"]))
            //IF NOT REDIRECT TO LOGIN PANEL
            header("Location: {$url}?page=login");


        // Refresh user points
        $userManager = new UserManager();
        $user = $userManager->getUser($_SESSION["email"]);        
        $_SESSION["points"] = $user->getPoints();
        
    }
}