<?php

spl_autoload_register(function($class_name) {
    $inc = $class_name;
    require __DIR__ . "/include.php";
});

date_default_timezone_set("America/Sao_Paulo");

function sql_execute($ssql, $mysqli = "") {
    /* @var mysqli $mysqli*/
    if ($mysqli === "") {
        $req = "mysqli";
        require __DIR__ . "/include.php";
    }

    $result = "";
    if ($select = $mysqli->query($ssql)) {
        $result = $select;
    } else {
        $result = false;
    }
    $mysqli->close();
    return $result;
}

function sql_execute_scalar($ssql, $mysqli = "") {
    /* @var mysqli $mysqli*/
    if ($mysqli === "") {
        $req = "mysqli";
        require __DIR__ . "/include.php";
    }

    $result = "";
    if ($select = $mysqli->query($ssql)) {
        $row = $select->fetch_array();
        $result = $row[0];
    } else {
        $result = false;
    }
    $mysqli->close();
    return $result;
}

function sql_execute_non_query($ssql, $mysqli = "") {
    /* @var mysqli $mysqli*/
    if ($mysqli === "") {
        $req = "mysqli";
        require __DIR__ . "/include.php";
    }

    $result = "";
    if ($mysqli->query($ssql)) {
        $result = true;
    } else {
        $result = false;
    }
    $mysqli->close();
    return $result;
}

function sql_transaction($query_list, $debug = false, $mysqli = "") {
    /* @var mysqli $mysqli*/
    if ($mysqli === "") {
        $req = "mysqli";
        require __DIR__ . "/include.php";
    }

    $count = 0;

    $mysqli->autocommit(FALSE);

    if ($debug)
        print_r("Iniciando MySQLi Transaction \n\n");
    foreach ($query_list as $ssql) {
        if (($ssql !== "") && ($ssql !== null)) {
            $count++;
            $count_str = str_pad($count, 5, "0", STR_PAD_LEFT);
            if (!$mysqli->query($ssql)) {
                if ($debug) {
                    print_r("\n\nErro no SQL $count_str: $ssql \n Nenhuma tabela foi afetada. \n");
                    print_r($mysqli->errno . ": " . $mysqli->error);
                }
                $mysqli->rollback();
                $mysqli->autocommit(TRUE);
                return false;
            } else {
                if ($debug)
                    print_r("[OK] $count_str: $ssql");
            }
        }
    }
    $mysqli->commit();
    $mysqli->autocommit(TRUE);
    $mysqli->close();

    if ($debug)
        print_r("Concluído com sucesso. Todas as queries foram Executadas.\n\n");
    return true;
}

function myprint($var) {
    print "<pre>";
    print_r($var);
    print "</pre>";
}

function tira_acentos($str) {
    $unwanted_array = array('Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
        'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
        'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
        'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y');
    return strtr($str, $unwanted_array);
}

function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function _log($msg, $data_ini = null, $prefix_path = "") {
    date_default_timezone_set("America/Sao_Paulo");
    if ($data_ini == null)
        $data_ini = new DateTime;
    $duracao = ((new DateTime)->format("U") - $data_ini->format("U")) . "s";

    $log = "[" . (new DateTime)->format("d/m/Y H:i:s") . "] ";
    $log .= $msg;
    $log .= " - $duracao \r\n\r\n";

    $origem = debug_backtrace();
    return file_put_contents($prefix_path . "__log." . basename($origem[0]["file"], ".php") . ".txt", $log, FILE_APPEND | LOCK_EX);
}

class RunTimeCounter {

    var $start;
    var $duration;

    function __construct() {
        $this->start = microtime(true);
    }

    function stop($rounded = 2) {
        $this->duration = (microtime(true) - $this->start) * 1000;
        if ($rounded !== false) {
            return round($this->duration, $rounded);
        } else {
            return $this->duration;
        }
    }

    function restart() {
        $this->start = microtime(true);
        $duration = null;
    }

}

function echo_date($message) {
    echo_flush("[" . date("d/m/Y H:i:s") . "] " . $message . "<br>");
}

function echo_flush($message, $pre = false) {
    ob_implicit_flush(true);

    if ($pre) {
        myprint($message);
    } else {
        echo $message;
    }

    ob_flush();
    flush();
}

function print_debug_maker($DEBUG) {
    return function($var) use ($DEBUG) {
        if ($DEBUG) {
            print_r($var);
        }
    };
}

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