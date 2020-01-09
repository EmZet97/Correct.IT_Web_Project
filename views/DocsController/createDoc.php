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
            <div class="col-12 docs-columns">
            <select name="language" class="docDropDownFields docCategories">
                    <?php foreach($languages as $lang){
                    echo "<option value=" . $lang['id_language'] . ">Język ". $lang['name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-4 docs-columns">
            <select name="c1" class="docDropDownFields docCategories" onchange="ChangeCategory()">
                    <option value="0"> </option>;
                    <?php foreach($categories as $cat){
                    echo "<option value=" . $cat->getId() . ">". $cat->getName() . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-4 docs-columns">
            <select name="c2" class="docDropDownFields docCategories" onchange="ChangeCategory()">
                    <option value="0"> </option>;
                    <?php foreach($categories as $cat){
                    echo "<option value=" . $cat->getId() . ">". $cat->getName() . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-4 docs-columns">
            <select name="c3" class="docDropDownFields docCategories" onchange="ChangeCategory()">
                    <option value="0"> </option>;
                    <?php foreach($categories as $cat){
                    echo "<option value=" . $cat->getId() . ">". $cat->getName() . "</option>";
                    }
                    ?>
                </select>
            </div>
            <!-- EDITOR -->
            <div class="col-12 docs-columns">
                <!-- Create the editor container -->
                <div>
                <div id="editor">
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

        </div>
    </div>
            
    <div id="buttonDiv" class="col-12">
        <input type="submit" id="saveButton" class="saveButtons" onclick="CreateDocument()" value="Stworz"/>
    </div>
    <div class="spacer"></div>
    </form>
</section>

<?php include(dirname(__DIR__).'/footer.html'); ?>
</body>
</html>