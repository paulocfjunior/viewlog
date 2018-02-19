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

/**
 * Altera o formato de uma data
 * @param string $date
 * @param string $output_format
 * @param string $input_format
 * @return string
 */
function reformat_date($date, $output_format = "Y-m-d", $input_format = "Y-m-d H:i:s"){
    $date = date_create_from_format($input_format, $date);
    return $date->format($output_format);
}

/**
 * Obtém um array com os dados de acesso resumidos por
 *    data => ip => id usuário => Nome de usuário => horario
 *
 * @param int $owner
 * @param int $report
 * @return array
 */
function get_access_summary($owner, $report) {

    if(
        !is_numeric($owner) ||
        !is_numeric($report)
    ){
        return [];
    }

    $query = sql_execute("SELECT * FROM `viewlog` WHERE `owner_id` = '$owner' AND `rep_id` = '$report' GROUP BY `ip`, `gccr_id`, DATE_FORMAT(`dt`, '%Y-%m-%d %H:%i') ORDER BY `dt` DESC");

    $result = [];

    if($query !== false){
        while ($row = $query->fetch_assoc()){
            $dt = reformat_date($row["dt"]);
            $id = (((int)$row["gccr_id"] === 0)? "Sem dados" : $row["gccr_id"]);
            @$result[$dt]["count"] += 1;
            @$result[$dt]["ips"][$row["ip"]]["count"] += 1;
            @$result[$dt]["ips"][$row["ip"]]["users"][$id][$row["gccr_name"]]["count"] += 1;
            $result[$dt]["ips"][$row["ip"]]["users"][$id][$row["gccr_name"]]["dates"][] = reformat_date($row["dt"], "H:i:s");
        }
//        ksort($result, SORT_DESC);
        foreach ($result as &$r){
            ksort($r["ips"]);
        }
    }

    return $result;
}

/**
 * @param int $owner
 * @param int $report
 * @return array
 */
function access_tree($owner, $report){
    $inc_o = ["default"];
    require "include.php";

    $result = get_access_summary($owner, $report);
    echo "<ul data-level='0'>";
    foreach ($result as $k => $r){
        echo "<li><button class='btn-tree' data-toggle='1'>-</button>" . reformat_date($k, "d/m/Y", "Y-m-d") . " ({$r["count"]})";
        echo "<ul data-level='1' style='display: block'>";
        foreach ($r["ips"] as $ip => $ip_meta) {
            echo "<li><button class='btn-tree' data-toggle='2'>+</button>$ip ({$ip_meta["count"]})";
            echo "<ul data-level='2' style='display: none'>";
            foreach ($ip_meta["users"] as $id => $id_meta) {
                echo "<li><button class='btn-tree' data-toggle='3'>-</button>$id";
                echo "<ul data-level='3' style='display: block'>";
                foreach ($id_meta as $user => $user_meta) {
                    echo "<li><button class='btn-tree' data-toggle='4'>-</button>$user";
                    echo "<ul data-level='4' style='display: block'>";
                    foreach ($user_meta["dates"] as $time) {
                        echo "<li>$time</li>";
                    }
                    echo "</li></ul>";
                }
                echo "</li></ul>";
            }
            echo "</li></ul>";
        }
        echo "</li></ul>";
    }
    echo "</ul>";

    echo <<<HTML
    <script type="text/javascript">
        if(typeof jQuery === "undefined"){
            document.write('<script src="_js/jquery-3.3.1.min.js">');
        };

        $(".btn-tree").click(function () {
            var ul = $(this).next("ul[data-level=" + $(this).data("toggle") + "]");
            ul.toggle();
            $(this).html((ul.is(":visible"))? "-" : "+");
        });
    </script>
HTML;

    return $result;
}

/**
 * Move um elemento de um array da posição $from para a posição $to
 * @param &$array
 * @param $from
 * @param $to
 */
function moveElement(&$array, $from, $to) {
    $out = array_splice($array, $from, 1);
    array_splice($array, $to, 0, $out);
}

/**
 * Retorna os dados para o gráfico de radar da tela Detalhes
 * @param bool $return_json
 * @return string
 */
function radarData($return_json = true) {
    global $OWNER, $REPORT_ID;

    $CHART["SQL"] = "SELECT hour12, ampm, AVG(qtd) AS 'avg' FROM (SELECT DATE_FORMAT(`dt`, \"%Y-%m-%d\") as 'date', DATE_FORMAT(`dt`, \"%H\") as 'hour', DATE_FORMAT(`dt`, \"%h\") as 'hour12', DATE_FORMAT(`dt`, \"%p\") as 'ampm', COUNT(`id`) as 'qtd' FROM `viewlog` WHERE `owner_id` = '$OWNER' AND `rep_id` = '$REPORT_ID' GROUP BY DATE_FORMAT(`dt`, \"%Y-%m-%d\"), DATE_FORMAT(`dt`, \"%H\")) AS subq GROUP BY hour12, ampm ORDER BY ampm, hour12";

    $CHART["QUERY"] = sql_execute($CHART["SQL"]);

    for ($i = 1; $i < 13; $i++){
        $hourAM = str_pad((($i == 12)? 0 : $i), 2, "0", STR_PAD_LEFT);
        $hourPM = str_pad(((($i + 12) == 24)? 12 : $i + 12), 2, "0", STR_PAD_LEFT);
        $result[(int)$hourAM] = [
            "hour" => "{$hourAM}h (AM)\n{$hourPM}h (PM)",
            "AM" => 0,
            "PM" => 0
        ];
    }

    while ($row = $CHART["QUERY"]->fetch_assoc()){
        $result[((int)$row["hour12"] - 1)][$row["ampm"]] = round($row["avg"], 2);
    }

    moveElement($result, 11, 0);

    if($return_json){
        return json_encode(array_values($result), JSON_PRETTY_PRINT);
    } else {
        return $result;
    }
}

/**
 * Retorna os dados para o gráfico principal da tela Detalhes
 * @param $access_summary
 * @param bool $return_json
 * @return array|string
 */
function principalChardData($access_summary, $return_json = true) {
    $chartData_array = [];
    foreach ($access_summary as $date => $date_meta) {
        $ips = [];
        $ips_meta = [];
        foreach($date_meta["ips"] as $ip => $ip_meta) {
            $ips[] = "[" . $ip_meta["count"] . "] " . $ip ;
            $ips_meta[$ip] = $ip_meta;
        }
        $chartData_array[$date] = [
            "date" =>  $date,
            "ip" =>  $ips_meta,
            "ipSTR" => "IPs \n" . implode("\n", $ips),
            "ips" => count($ips),
            "views" => (int)$date_meta["count"]
        ];
    }
    ksort($chartData_array, SORT_DESC);

    if($return_json){
        return json_encode(array_values($chartData_array), JSON_PRETTY_PRINT);
    } else {
        return array_values($chartData_array);
    }
}