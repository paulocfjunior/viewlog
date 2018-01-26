<?php

$inc_o = ["default"];
require "include.php";

if( !isset($_POST["login-user"]) || !isset($_POST["login-pwd"])){
    header("location: ../login.php");
}

if(!is_numeric($_POST["login-user"])){
    setcookie("VIEWLOG_LOGIN_ERROR", null, -1, "/");
    setcookie("VIEWLOG_LOGIN_ERROR", "ID Inválido", time()+(60 * 60 * 10), "/");
    header("location: ../login.php");
} else {
    $uid = $_POST["login-user"];
    $pwd = $_POST["login-pwd"];
    setcookie("VIEWLOG_LOGIN_ID", $uid, time()+(60 * 60 * 10), "/");
    
    $check = sql_execute_scalar("SELECT `pwd` FROM `usr` WHERE `uid` = '$uid'");
    
    if($check === false){
        // Falha na conexão com o banco de dados
        setcookie("VIEWLOG_LOGIN_ERROR", "Falha na conexão com o banco de dados", time()+(60 * 60 * 10), "/");
        header("location: ../login.php");
    } elseif($check === null){
        // Usuário não existe
        setcookie("VIEWLOG_LOGIN_ERROR", "Usuário não cadastrado", time()+(60 * 60 * 10), "/");
        header("location: ../login.php");
    } elseif(
        (strcmp($check, $pwd) !== 0) &&
        (strcmp($check, hash("sha256", $pwd)) !== 0)
    ) {
        // Senha incorreta
        setcookie("VIEWLOG_LOGIN_ERROR", "Senha incorreta", time()+(60 * 60 * 10), "/");
        header("location: ../login.php");
    } else {
        $uname = sql_execute_scalar("SELECT `name` FROM `usr` WHERE `uid` = '$uid'");
        setcookie("VIEWLOG_LOGIN",
                  json_encode([
                                  "uid" => $uid,
                                  "name" => $uname
                              ]), time()+(60 * 60 * 10), "/");
        
        setcookie("VIEWLOG_LOGIN_ERROR", null, -1, "/");
        
        header("location: ../");
    }
}


