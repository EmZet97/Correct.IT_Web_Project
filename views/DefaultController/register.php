<!DOCTYPE html>
<html>

<?php include(dirname(__DIR__).'/head.html') ?>

<body>

<div class="container">
    <div clas="row">
        <div class="col-12 col-sm-10 offset-sm-1 col-lg-8 offset-lg-2" id="loginPanel">
            <h1 class="panel-header">CORRECT.IT</h1>
            <h2 class="panel-bottom-header">Poprawiaj, komentuj, oceniaj</h2>
            <br>
            <br>
            <br>           
            
            <form action="?page=register" method="POST" class="row">
            <!-- MESSAGE -->
            <div class="panel-input-div col-12 col-sm-10 offset-sm-1 col-lg-8 offset-lg-2">
                   <h3 class="panel-input-title red-message">
                   <?php
                        if(isset($messages)){

                            foreach($messages as $message) {
                                echo $message;
                            }
                            
                        }
                    ?>
                   </h3>
                </div>
               <!-- EMAIL -->
                <div class="panel-input-div col-12 col-sm-10 offset-sm-1 col-lg-8 offset-lg-2">
                   <h3 class="panel-input-title">E-mail:</h3>
                </div>
               <div class="panel-input-div col-12 col-sm-10 offset-sm-1 col-lg-8 offset-lg-2">
                   <input type="email" name="email" class="panel-text-input" id="inputEmail" placeholder="john.wick@love.guns" required/>
                </div>
                <!-- NICK -->
                <div class="panel-input-div col-12 col-sm-10 offset-sm-1 col-lg-8 offset-lg-2">
                   <h3 class="panel-input-title">Nick:</h3>
                </div>
               <div class="panel-input-div col-12 col-sm-10 offset-sm-1 col-lg-8 offset-lg-2">
                   <input type="text" name="nick" class="panel-text-input" id="inputEmail" placeholder="wicky" required/>
                </div>
                <!-- PASSWORDS -->
                <div class="panel-input-div col-12 col-sm-10 offset-sm-1 col-lg-8 offset-lg-2">
                   <h3 class="panel-input-title">Hasło:</h3>
                </div>
               <div class="panel-input-div col-12 col-sm-10 offset-sm-1 col-lg-8 offset-lg-2">
                   <input type="password" name="password" class="panel-text-input" id="inputPassword" placeholder="Doghunter123" required eq/>
               </div>
               <div class="panel-input-div col-12 col-sm-10 offset-sm-1 col-lg-8 offset-lg-2">
                   <h3 class="panel-input-title">Powtórz hasło:</h3>
                </div>
               <div class="panel-input-div col-12 col-sm-10 offset-sm-1 col-lg-8 offset-lg-2">
                   <input type="password" name="password" class="panel-text-input" id="inputPassword" placeholder="Doghunter123" required/>
               </div>
               <!-- BUTTONS -->
               <div class="panel-input-div col-8 offset-2 col-lg-4 offset-lg-4">
                   <input type="submit" class="panel-button-input" value="Zarejestruj"/>
               </div>
               <div class="panel-input-div col-6 offset-3 col-lg-4 offset-lg-8">
                   <h3 class="panel-button-title">Posiadasz już konto?</h3>
                </div>
               <div class="panel-input-div col-6 offset-3 col-lg-4 offset-lg-8">
                   <a href="/?page=login" class="panel-button-link">Zaloguj</a>
               </div>
                
                
                
            </form>
        </div>
    </div>
</div>

</body>
</html>

