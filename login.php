<?php

    if(isset($_COOKIE["VIEWLOG_LOGIN"])){
        header("location: ./");
    }

?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="Acompanhamento de Visualização de Relatórios">
        <meta name="author" content="Paulo Cézar">
        <link rel="icon" href="_img/spy.ico">
        <title>ViewLog - Login</title>
        
        <link href="_css/bootstrap.min.css" rel="stylesheet" />
        <link href="_css/login.css" rel="stylesheet" />
    </head>
    <body>
        <div class="container">
            <div id="login-logo">
                <!--<img src="_img/spy-256.png" alt="ViewLog" height="80px" />-->
                <span>ViewLog</span>
            </div>
            <form id="login-form" action="_php/login_check.php" method="post">
                <?php
                
                    if(isset($_COOKIE["VIEWLOG_LOGIN_ERROR"])){
                        echo /** @lang html*/ "
                            <div class=\"alert alert-danger\" role=\"alert\">
                                {$_COOKIE["VIEWLOG_LOGIN_ERROR"]}
                            </div>
                        ";
                    }
                
                ?>
                <div class="form-group">
                    <label for="login-user">ID Tel</label>
                    <input class="form-control" id="login-user" name="login-user" aria-describedby="emailHelp" maxlength="5" placeholder="12345" value="<?=(isset($_COOKIE["VIEWLOG_LOGIN_ID"])? $_COOKIE["VIEWLOG_LOGIN_ID"] : "")?>">
                    <small id="emailHelp" class="form-text text-muted">Apenas os últimos 5 dígitos.</small>
                </div>
                <div class="form-group">
                    <label for="login-pwd">Senha</label>
                    <input type="password" class="form-control" id="login-pwd" name="login-pwd"  placeholder="Senha">
                </div>
                <button class="btn btn-primary">Submit</button>
            </form>
        </div>
        
        <script src="_js/jquery.min.js"></script>
        <script src="_js/bootstrap.min.js"></script>
    </body>
</html>