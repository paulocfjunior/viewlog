<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

$login_check_conditions[0] = true;
// $login_check_conditions[0] = isset($_SESSION["token"]);
// $login_check_conditions[1] = ($_SESSION["token"] == "123");

if (count(array_unique($login_check_conditions)) === 1 &&
    end($login_check_conditions) === TRUE) {

    if (isset($req) and count($req) > 0) {
        if (is_array($req)) {
            foreach ($req as $key => $value)
                require __DIR__ . "/$value.php";
        } else {
            require __DIR__ . "/$req.php";
        }
    }

    if (isset($req_o) and count($req_o) > 0) {
        if (is_array($req_o)) {
            foreach ($req_o as $key => $value)
                require_once __DIR__ . "/$value.php";
        } else {
            require_once __DIR__ . "/$req_o.php";
        }
    }

    if (isset($inc) and count($inc) > 0) {
        if (is_array($inc)) {
            foreach ($inc as $key => $value)
                include(__DIR__ . "/$value.php");
        } else {
            include(__DIR__ . "/$inc.php");
        }
    }

    if (isset($inc_o) and count($inc_o) > 0) {
        if (is_array($inc_o)) {
            foreach ($inc_o as $key => $value)
                include_once(__DIR__ . "/$value.php");
        } else {
            include_once(__DIR__ . "/$inc_o.php");
        }
    }
} else {

    // header("Location: _login");
    // die();
    echo "Você não está logado";
}

