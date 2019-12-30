<!DOCTYPE html>
<html>

<?php include(dirname(__DIR__).'/head.html'); ?>

<body onload="SetContent()">
<?php include(dirname(__DIR__).'/navbar.php'); ?>

<section class="jumpers">
    <h2 id="pageTitle">Edytuj pracę</h2>
    <form action="?page=editDoc_Execute" method="POST">
    <div id="pageContent" class="container">
        <div class="row">
            <!-- Header of page -->
            <div class="col-12 docs-columns">
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