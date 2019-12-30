<!DOCTYPE html>
<html>

<?php include(dirname(__DIR__).'/head.html'); ?>

<body onload="">
<?php include(dirname(__DIR__).'/navbar.php'); ?>

<section class="jumpers">
    <h2 id="pageTitle">Oceń pracę</h2>
    <form action="?page=correctDoc_Execute" method="POST">
    <div id="pageContent" class="container">
        <div class="row">
            <!-- Left column -->
            <div class="col-8 docs-columns" id="leftColumn">
            <h4 id="ColumnTitle"><?php echo $doc->getTitle()?></h4>
                <div id="docContent"><?php echo $doc->getContent() ?></div>
            </div>
            
            
            <!-- Right column -->
            <div class="col-4 docs-columns">
                <h4 id="ColumnTitle">Twój komentarz</h4>
                <!-- Create the editor container -->
                <div id="editor">
                </div>

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
                <div id="stars">
                    <i class="far fa-star"></i>
                    <i class="far fa-star"></i>
                    <i class="far fa-star"></i>
                    <i class="far fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                </div>
            </div>

            <input type="hidden" name="content" id="contentPacker"/>
            <input type="hidden" name="docID" value = <?php echo "'" . $doc->getId() . "'" ?>/>
            <input type="hidden" name="contentBank" id="contentBank" value = <?php echo "'" . $doc->getContent() . "'" ?>/>

        </div>
    </div>
            

    <input type="submit" id="saveButton" class="fixedButton" onclick="CreateDocument()" value="Zapisz nową wersję"/>
    </form>
</section>

<?php include(dirname(__DIR__).'/footer.html'); ?>
</body>
</html>