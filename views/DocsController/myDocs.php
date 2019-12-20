<!DOCTYPE html>
<html>

<?php include(dirname(__DIR__).'/head.html'); ?>

<body>
<?php include(dirname(__DIR__).'/navbar.php'); ?>
<?php include(dirname(__DIR__).'/footer.html'); ?>

<section class="jumpers">
    <h2 id="pageTitle">Twoje prace</h2>
    <div id="pageContent" class="container">
        <div class="row">
            
            <?php
            for ($x = 6; $x <= 10; $x++){
                $time = "2h ago";
                $title = "Tytuł" . $x;
                $category1 = "kategoria 1";
                $category2 = "kategoria 2";
                $category3 = "kategoria 3";
                $words = 1234;
                $language = "Polski";
                $likes = $x * 100;
                $comments = $x * 123;
                $check_page = "/?page=correct&id=blabla";
                
                // HTML generator:
                echo '
                <div class="col-12 col-sm-6 col-lg-4 docs-columns">
                <div class="doc">
                    <div class="doc-header">
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
                        <a href="' . $check_page . '" class="inline start_correct">Oceń pracę</a>
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