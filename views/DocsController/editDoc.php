<!DOCTYPE html>
<html>

<?php include(dirname(__DIR__).'/head.html'); ?>

<body onload="SetContent()">
<?php include(dirname(__DIR__).'/navbar.php'); ?>


<section class="jumpers">
    <h2 id="pageTitle">Edytuj pracę</h2>
    <div id="leftPanel" class="col-12 col-lg-8 inline">
    <form action="?page=editDoc_Execute" method="POST">
    <div id="pageContent" class="container">
        <div class="row">
            
            <!-- Header of page -->
            <div class="col-12  docs-columns">
                <input type="text" class="docFields" id="docTitle" name="title" readonly value=<?php echo "'" . $doc->getTitle() . "'"?>/>
            </div>
            <div class="col-4 docs-columns">
                <input type="text" class="docFields docCategories" name="c1" readonly  value=<?php echo "'" . $doc->getCategory(1) . "'"?>/>
            </div>
            <div class="col-4 docs-columns">
                <input type="text" class="docFields docCategories" name="c2" readonly value=<?php echo "'" . $doc->getCategory(2) . "'"?>/>
            </div>
            <div class="col-4 docs-columns">
                <input type="text" class="docFields docCategories" name="c3" readonly value=<?php echo "'" . $doc->getCategory(3) . "'"?>/>
            </div>
            <!-- EDITOR -->
            <div class="col-12 docs-columns">
                <!-- Create the editor container -->
                <div>
                    <div id="editor" onkeypress="typeText()">
                    </div>
                </div>

                <!-- Include the Quill library -->
                <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

                <!-- Initialize Quill editor -->
                <script>
                var quill = new Quill('#editor', {
                    theme: 'snow'
                });
                </script>
            </div>
            <input type="hidden" name="content" id="contentPacker"/>
            <input type="hidden" name="docID" id="docIdPacker" value = <?php echo "'" . $doc->getId() . "'" ?>/>
            <input type="hidden" name="userID" id="userIdPacker" value = <?php echo "'" . $_SESSION["id"] . "'" ?>/>
            <input type="hidden" name="points" value = "1"/>
            <input type="hidden" name="contentBank" id="contentBank" value = <?php echo "'" . $doc->getContent() . "'" ?>/>


            <div id="buttonDiv" class="col-12">
                <input type="submit" id="saveButton" class="saveButtons" onclick="CreateDocument()" value="Utwórz nową wersję"/>
            </div>
        </div>
        
    </div>
          

    
    </form>
    <div id="buttonDiv" class="col-12">
        <button id="saveButton2" class="saveButtons" onclick="SaveMyDoc()">Zapisz<i class="fas fa-check-circle" id="save_img"></i></button>
    </div>
    <div class="spacer"></div>
    
    
    </div>
    
    <div id="rightPanel" class="col-12 col-lg-4 inline">

    <div id="commentsPanel" class="container">
        <h2 class="comments-title">
            Oceny użytkowników:
        </h2>
        
        <?php         
        if(isset($comments)){
            echo '<div id="commentsContainer" >';
        foreach($comments as $comment){
        $reward = intval($comment -> getCommentReward());
        $nick = $comment->getUserNick();
        $userId = $comment->getUserId();
        $content = $comment->getComment();
        $commentId = $comment->getCommentId();
        $rate = $comment->getRate();
        $s1 = "fas";
        $s2 = $comment->getRate()>1 ? "fas" : "far";
        $s3 = $comment->getRate()>2 ? "fas" : "far";
        $s4 = $comment->getRate()>3 ? "fas" : "far";
        $s5 = $comment->getRate()>4 ? "fas" : "far";
        $g = "";
        $r = "";
        if($reward == 1)
            {
                
            }

        echo '
        <section class="commentElement">
        <h2 class="comments-userNick">
            '. $nick . '
        </h2>
        <div class="comments-commentContent">
            '. $content .'
        </div>
        <div class="col-12 comments-commentRate">
            <div>
                <i class="'. $s1 .' fa-star"></i>
                <i class="'. $s2 .' fa-star"></i>
                <i class="'. $s3 .' fa-star"></i>
                <i class="'. $s4 .' fa-star"></i>
                <i class="'. $s5 .' fa-star"></i>
            </div>
        </div>
        <div class="col-12 comments-rewardUser">
            <div>
                <i class="left">Oceń komentarz:</i>';
                if($reward == 1){
                    echo 
                    '<i class="fas fa-grin-alt ok green" onclick="RewardUser(' . $userId . ', ' . $commentId . ' , this)"></i>
                    <i> vs </i>
                    <i class="fas fa-angry notok" onclick="Spam(' . $userId . ', ' . $commentId . ' , this)"></i>';
                }
                else if($reward == -1){
                    echo 
                    '<i class="fas fa-grin-alt ok" onclick="RewardUser(' . $userId . ', ' . $commentId . ' , this)"></i>
                    <i> vs </i>
                    <i class="fas fa-angry notok red" onclick="Spam(' . $userId . ', ' . $commentId . ' , this)"></i>';
                }
                else{
                    echo 
                    '<i class="fas fa-grin-alt ok" onclick="RewardUser(' . $userId . ', ' . $commentId . ' , this)"></i>
                    <i> vs </i>
                    <i class="fas fa-angry notok" onclick="Spam(' . $userId . ', ' . $commentId . ' , this)"></i>';
                }
                
                echo '
            </div>
        </div>
    </section>
    ';
        }
        echo '</div>';
    }
    ?>

</div>
    </div>
</section>




<?php include(dirname(__DIR__).'/footer.html'); ?>
</body>
</html>