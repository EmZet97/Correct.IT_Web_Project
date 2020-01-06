<!DOCTYPE html>
<html>

<?php include(dirname(__DIR__).'/head.html'); ?>

<body onload="SetComment()">
<?php include(dirname(__DIR__).'/navbar.php'); ?>

<section class="jumpers">
    <h2 id="pageTitle">Oceń pracę</h2>
    <form action="?page=correctDoc_Execute" method="POST">
    <div id="pageContent" class="container">
        <div class="row">
            <!-- Left column -->
            <div class="col-12 col-lg-8 docs-columns" id="leftColumn">
                <h4 id="ColumnTitle"><?php echo $doc->getTitle()?></h4>
                <div id="docContent"><?php echo $doc->getContent() ?></div>
            </div>
            
            
            <!-- Right column -->
            <div class="col-12 col-lg-4 docs-columns" id="rightColumn">
                <div>
                    <h4 id="ColumnTitle">Twój komentarz</h4>
                    <!-- Create the editor container -->
                    <div id="editor"></div>

                    <!-- Include the Quill library -->
                    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

                    <!-- Initialize Quill editor -->
                    <script>
                    var quill = new Quill('#editor', {
                        theme: 'snow'
                    });
                    </script>
                    <div id="RateContainer">
                        <h3 class="h3_titles">Ocena:</h3>
                        <div id="stars" onmouseleave="starLeave()">
                            <i class="fas fa-star" onmouseenter="starEnter(1)" onclick="starClick(1)"></i>
                            <i class="far fa-star" onmouseenter="starEnter(2)" onclick="starClick(2)"></i>
                            <i class="far fa-star" onmouseenter="starEnter(3)" onclick="starClick(3)"></i>
                            <i class="far fa-star" onmouseenter="starEnter(4)" onclick="starClick(4)"></i>
                            <i class="far fa-star" onmouseenter="starEnter(5)" onclick="starClick(5)"></i>
                        </div>
                        <h3 class="h3_titles" id="last">1</h3>
                    </div>
                </div>
            </div>

            <input type="hidden" name="comment" id="contentPacker"/>
            <input type="hidden" name="rate" id="starsPacker" value="1"/>

            <input type="hidden" name="docID" value = <?php echo "'" . $doc->getId() . "'" ?>/>
            <input type="hidden" name="docVersion" value = <?php echo "'" . $doc->getVersionId() . "'" ?>/>
            <input type="hidden" name="contentBank" id="contentBank" value = <?php echo '"' . $doc->getContent() . '"' ?>/>
            
            <input type="hidden" id="commentBank" value = <?php echo '"' . $rate->getComment() . '"' ?>/>
            <input type="hidden" id="rateBank" value = <?php echo "'" . $rate->getRate() . "'" ?>/>
            
            <div id="buttonDiv" class="col-12">
                <input type="submit" id="saveButton" class="saveButtons" onclick="CorrectDocument()" value="Oceń pracę"/>
            </div>
            <div class="spacer"></div>

        </div>
    </div>
            
            
    </form>
</section>

<?php include(dirname(__DIR__).'/footer.html'); ?>
</body>
</html>