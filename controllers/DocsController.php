<?php

require_once "AppController.php";

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

    public function browseDocs()
    {
        $this->checkSession();
        $docManager = new DocumentManager();        
        $id = $_SESSION["id"];
        $docs = $docManager->getOtherUsersDocuments($id);

        $this->render('browseDocs', ['docs' => $docs]);
        return;
    }

    public function createDoc()
    {
        $this->checkSession();
        $categoriesManager = new CategoriesManager();
        $categories = $categoriesManager->getCategories();
        $this->render('createDoc', ['categories' => $categories]);
        return;
    }

    public function deleteDoc()
    {
        $this->checkSession();
        $docId = $_GET['id'];
        $docManager = new DocumentManager();
        //echo $docId;
        $docManager->deleteDocument($docId);
        $this->myDocs();
        return;
    }

    public function createDoc_Execute(){
        $this->checkSession();
        if (true) {
            //echo "true";
            $title = $_POST['title'];
            $category1 = $_POST['c1'];
            $category2 = $_POST['c2'];
            $category3 = $_POST['c3'];
            $content = $_POST['content'];
            //echo $content;
            $id = $_SESSION["id"];            
            $docManager = new DocumentManager();
            $owner = new User($id);
            $document = new Document($owner, null, $title, null, 1, null, null, $category1, $category2, $category3);
            $docManager->createDocument($document, $content);            

        }
        else{
            //echo "false";
        }

        header("Location: {$url}?page=myDocs");
        return;
    }


    public function editDoc_Execute(){
        $this->checkSession();
        if (true) {
            $content = $_POST['content'];
            $docId = $_POST['docID'];
            //echo $content;
            $id = $_SESSION["id"];            
            $docManager = new DocumentManager();
            $owner = new User($id);
            $document = new Document($owner, $docId, "", null, 1, null, null, "", "", "");
            $docManager->updateDocument($document, $content);            

        }
        else{
            //echo "false";
        }

        header("Location: {$url}?page=myDocs");
        return;
    }

    public function correctDoc()
    {
        $this->checkSession();
        $docId = $_GET['id'];
        $docManager = new DocumentManager();
        $doc = $docManager->getDocument($docId);
        //echo "document" . $doc;
        $this->render('correctDoc', ['doc' => $doc]);
        return;
    }

    public function editDoc()
    {
        $this->checkSession();
        $docId = $_GET['id'];
        $docManager = new DocumentManager();
        $doc = $docManager->getDocument($docId);

        if($doc->getOwnerId() != $_SESSION["id"]){
            header("Location: {$url}?page=myDocs");
            return;
        }
        // GET FILE CONTENT
        $file_name =  "Documents/" . $doc->getPath() . $docManager->path_project_connector . $docId . $docManager->path_version_connector . $doc->getVersion();
        $file_manager = new FileManager();
        $content = $file_manager->readFile($file_name);

        $doc->setContent($content);
        
        $this->render('editDoc', ['doc' => $doc]);
        return;
    }

    public function myDocs()
    {
        $this->checkSession();
        $docManager = new DocumentManager();        
        $id = $_SESSION["id"];
        $docs = $docManager->getUserDocuments($id);

        $this->render('myDocs', ['docs' => $docs]);
        return;
    }

    private function checkSession(){
        if(!isset($_SESSION["id"]))
            header("Location: {$url}?page=login");
    }
}