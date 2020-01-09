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
            
            <form action="?page=login" method="POST" class="row">
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
              <div class="panel-input-div col-12 col-sm-10 offset-sm-1 col-lg-8 offset-lg-2">
                   <h3 class="panel-input-title">E-mail:</h3>
                </div>
               <div class="panel-input-div col-12 col-sm-10 offset-sm-1 col-lg-8 offset-lg-2">
                   <input type="email" name="email" class="panel-text-input" id="inputEmail" placeholder="john.wick@love.guns" required/>
                </div>
                <div class="panel-input-div col-12 col-sm-10 offset-sm-1 col-lg-8 offset-lg-2">
                   <h3 class="panel-input-title">Hasło:</h3>
                </div>
               <div class="panel-input-div col-12 col-sm-10 offset-sm-1 col-lg-8 offset-lg-2">
                   <input type="password" name="password" class="panel-text-input" id="inputPassword" placeholder="Doghunter123" type="password" required/>
               </div>
               <div class="panel-input-div col-8 offset-2 col-lg-4 offset-lg-4">
                   <input type="submit" class="panel-button-input" value="Zaloguj"/>
               </div>
               <div class="panel-input-div col-6 offset-3 col-lg-4 offset-lg-8">
                   <h3 class="panel-button-title">Nie posiadasz konta?</h3>
                </div>
               <div class="panel-input-div col-6 offset-3 col-lg-4 offset-lg-8">
                   <a href="/?page=register" class="panel-button-link">Zarejestruj</a>
               </div>
                
                
                
            </form>
        </div>
    </div>
</div>

</body>
</html>

