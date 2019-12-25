<!DOCTYPE html>
<html>

<?php include(dirname(__DIR__).'/head.html'); ?>

<body>
<?php include(dirname(__DIR__).'/navbar.php'); ?>

<section class="jumpers">
    <h2 id="pageTitle">Stwórz pracę do oceny</h2>
    <form action="?page=createDoc_Execute" method="POST">
    <div id="pageContent" class="container">
        <div class="row">
            <!-- Header of page -->
            <div class="col-12 docs-columns">
                <input type="text" class="docFields" id="docTitle" name="title" placeholder="Tytuł dokumentu" required/>
            </div>
            <div class="col-4 docs-columns">
                <input type="text" class="docFields docCategories" name="c1" placeholder="Kategoria 1" required/>
            </div>
            <div class="col-4 docs-columns">
                <input type="text" class="docFields docCategories" name="c2" placeholder="Kategoria 2"/>
            </div>
            <div class="col-4 docs-columns">
                <input type="text" class="docFields docCategories" name="c3" placeholder="Kategoria 3"/>
            </div>
            <!-- EDITOR -->
            <div class="col-12 docs-columns">
                <!-- Create the editor container -->
                <div id="editor">
                    <p>Hello World!</p>
                    <p>Some initial <strong>bold</strong> text</p>
                    <p><br></p>
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

        </div>
    </div>
            

    <input type="submit" id="saveButton" class="fixedButton" onclick="CreateDocument()" value="Stworz"/>
    </form>
</section>

<?php include(dirname(__DIR__).'/footer.html'); ?>
</body>
</html>