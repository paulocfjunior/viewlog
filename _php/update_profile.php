<?php

    if(!isset($_COOKIE["VIEWLOG_LOGIN"])){
        header("location: ../login.php");
    } else {
        $COOKIE = json_decode($_COOKIE["VIEWLOG_LOGIN"], true);
        if(
            !isset($_POST["new_name"]) &&
            !isset($_POST["new_pwd"]) &&
            !isset($_POST["new_pwd_confirm"])
        ){
            setcookie("VIEWLOG_UPDATE_PROFILE_ERROR", "Faltaram parâmetros", time() + 60, "/");
            header("location: ../");
        } else {
            
            $new_name = $_POST["new_name"];
            $new_pwd = $_POST["new_pwd"];
            $new_pwd_confirm = $_POST["new_pwd_confirm"];
        
            if(strcmp($new_pwd, $new_pwd_confirm) !== 0){
                setcookie("VIEWLOG_UPDATE_PROFILE_ERROR", "As senhas não coincidem", time() + 60, "/");
                header("location: ../#v-pills-profile");
            } else {
                $inc = ["default"];
                require "include.php";
                
                if($pwd !== ""){
                    $update_pwd = "";
                 } else {
                    $new_pwd = hash("sha256", $new_pwd);
                    $update_pwd = ",`pwd`='$new_pwd'";
                }
                
                $check = sql_execute_non_query("UPDATE `usr` SET `name`='$new_name' $update_pwd WHERE `uid` = '{$COOKIE["uid"]}'");
                
                setcookie("VIEWLOG_LOGIN",
                          json_encode([
                              "uid" => $COOKIE["uid"],
                              "name" => $new_name
                          ]), time() + 60 * 60 * 24, "/");
                
                setcookie("VIEWLOG_UPDATE_PROFILE_ERROR", null, -1, "/");
                header("location: ../#v-pills-profile");
            }
        }
    }

    
    
