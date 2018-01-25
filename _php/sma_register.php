<?php

function register_visitor($referece = "null", $user = 32114) {

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(!function_exists("ipCheck")){
        function ipCheck() {
            if (getenv('HTTP_CLIENT_IP')) {
                $ip = getenv('HTTP_CLIENT_IP');
            } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_X_FORWARDED')) {
                $ip = getenv('HTTP_X_FORWARDED');
            } elseif (getenv('HTTP_FORWARDED_FOR')) {
                $ip = getenv('HTTP_FORWARDED_FOR');
            } elseif (getenv('HTTP_FORWARDED')) {
                $ip = getenv('HTTP_FORWARDED');
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            return $ip;
        }
    }

    $CLIENT_DATA = [
        // "COMPUTERNAME" => gethostname(),
        // "COMPUTERDATA" => php_uname(),
        // "DOMAIN/USER"  => $user[1],
        "IP_CLIENT"    => ipCheck(),
        "BROWSER"      => $_SERVER['HTTP_USER_AGENT'],
        "GCCR_IDTEL"   => (isset($_SESSION["login"])? $_SESSION["login"] : ""),
        "GCCR_NOME"    => (isset($_SESSION["nome"])? $_SESSION["nome"] : ""),
        "SCRIPT_NAME"  => $_SERVER["SCRIPT_NAME"],
        "PHP_SELF"     => $_SERVER["PHP_SELF"],
        "REQUEST_URI"  => $_SERVER["REQUEST_URI"]
    ];

    $mysqli = new mysqli("localhost", "root", "ckbgdhppedp7p3a466p", "dados_relatorios");
    $mysqli->query("SET NAMES 'utf8'");
    $mysqli->query("SET character_set_connection=utf8");
    $mysqli->query("SET character_set_client=utf8");
    $mysqli->query("SET character_set_results=utf8");
    if ($mysqli->connect_errno) {
        echo "Não foi possível conectar ao MySQL. <br><b>Código do erro: " . $mysqli->connect_error;
        EXIT;
    }

    $query = $mysqli->query("INSERT INTO `log_visualizacao`(`log_id`, `log_ref`, `log_datetime`, `log_ip_client`, `log_browser`, `log_gccr_login`, `log_gccr_nome`, `log_script_name`, `log_self_name`, `log_request_uri`, `log_owner`) VALUES (NULL, '$referece', CURRENT_TIMESTAMP, '{$CLIENT_DATA["IP_CLIENT"]}', '{$CLIENT_DATA["BROWSER"]}', '{$CLIENT_DATA["GCCR_IDTEL"]}', '{$CLIENT_DATA["GCCR_NOME"]}', '{$CLIENT_DATA["SCRIPT_NAME"]}', '{$CLIENT_DATA["PHP_SELF"]}', '{$CLIENT_DATA["REQUEST_URI"]}', '$user')");

    if($query === false) {

    }
}