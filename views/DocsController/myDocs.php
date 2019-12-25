<!DOCTYPE html>
<html>

<?php include(dirname(__DIR__).'/head.html'); ?>

<body>
<?php include(dirname(__DIR__).'/navbar.php'); ?>
<?php include(dirname(__DIR__).'/footer.html'); ?>

<section class="jumpers">
    <h2 id="pageTitle">Twoje prace</h2>
    <div class="col-4 offset-4" id="createDocDiv"><a href="/?page=createDoc" id="createDocButton"><i class="fas fa-folder-plus"></i></a></div>
     
    <div id="pageContent" class="container">
        <div class="row">
           
        <?php 
        if(isset($docs))
        foreach($docs as $doc){
                $time = $doc->getLastEdit();
                $title = $doc->getTitle();
                $category1 = $doc->getCategory(1);
                $category2 = $doc->getCategory(2);
                $category3 = $doc->getCategory(3);
                $words = $doc->getWords();
                $language = $doc->getLanguage();
                $likes = $doc->getLikes();
                $comments =  $doc->getComments();
                $check_page = "/?page=editDoc&id=". $doc->getId();
                $delete_page = "/?page=deleteDoc&id=". $doc->getId();
                
                
                // HTML generator:
                echo '
                <div class="col-12 col-sm-6 col-lg-4 docs-columns">
                <div class="doc">
                    <div class="doc-header">
                        <a class="doc-delete-button" href="'. $delete_page . '"><i class="fas fa-times-circle"></i></a>
                        <h3 class="doc-create-time text">' . $time . '</h3>
                    </div>
                    <div class="doc-content">
                        
                        <div class="doc-content-up">
                            <h3 class="doc-title text">' . $title . '</h3>
                            <p class="doc-categories">Kategorie:</p>
                            <p>' . $category1 . '</p>
                            <p>' . $category2 . '</p>
                            <p>' . $category3 . '</p>
                        </div>

                        <div class="doc-content-down">
                            <p class="doc-wordcounter">Ilość słów: ' . $words . '</p>
                            <p class="doc-language">Język: ' . $language . '</p>
                        </div>

                    </div>

                    <div class="doc-footer">
                        <div class="stats">                        
                            <i class="fas fa-heart likes"></i>
                            <h3 class="stats text">' . $likes . '</h3>
                            <i class="fas fa-comment-alt comments"></i>
                            <h3 class="stats text">' . $comments . '</h3>
                        </div>
                        <a href="' . $check_page . '" class="inline start_correct">Edytuj pracę</a>
                    </div>
                </div>
            </div>
                '; // html end
            }
            ?>
        </div>
    </div>
</section>

</body>
</html>