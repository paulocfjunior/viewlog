<?php

spl_autoload_register(function($class_name) {
    $inc = $class_name;
    require "/include.php";
});

date_default_timezone_set("America/Sao_Paulo");

function oci_query($ssql, $oci_nested = "") {
    if ($oci_nested === "") {
        $req = "oci";
        require "/include.php";
    } else {
        $oci = $oci_nested;
    }

    $stid = oci_parse($oci, $ssql);
    if (!$stid) {
        $e = oci_error($oci);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        return false;
    }

    $r = oci_execute($stid);
    if (!$r) {
        $e = oci_error($stid);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        return false;
    }

    return $stid;
}

function sql_server_query($ssql, $pdo_nested = "") {
    if ($sql_connection_nested === "") {
        $req = "sql_server";
        require "/include.php";
    } else {
        $pdo = $sql_connection_nested;
    }

    $sth = $pdo->prepare($ssql);
    $sth->execute();
}

function sql_server_transaction($query_list) {
    $req = "sql_server";
    require "/include.php";

    if (sqlsrv_begin_transaction($pdo) === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if ($debug)
        myprint("Iniciando SQL Server Transaction \n\n");
    foreach ($query_list as $ssql) {
        if (($ssql !== "") && ($ssql !== null)) {
            if (!sqlsrv_query($ssql)) {
                if ($debug)
                    myprint("\n\nErro no SQL $ssql \n Nenhuma tabela foi afetada. \n");
                sqlsrv_rollback($pdo);
                return false;
            } else {
                if ($debug)
                    myprint("[OK] $ssql \n");
            }
        }
    }
    sqlsrv_commit($pdo);

    $mysqli->close();

    if ($debug)
        myprint("Concluído com sucesso. Todas as queries foram Executadas.\n\n");
    return true;
}

function sql_execute($ssql, $mysqli = "") {
    if ($mysqli === "") {
        $req = "mysqli";
        require "/include.php";
    }

    $result;
    if ($select = $mysqli->query($ssql)) {
        $result = $select;
    } else {
        $result = false;
    }
    $mysqli->close();
    return $result;
}

function sql_execute_scalar($ssql, $mysqli = "") {
    if ($mysqli === "") {
        $req = "mysqli";
        require "/include.php";
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
    if ($mysqli === "") {
        $req = "mysqli";
        require "/include.php";
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
    if ($mysqli === "") {
        $req = "mysqli";
        require "/include.php";
    }

    $count = 0;

    $mysqli->autocommit(FALSE);

    if ($debug)
        echo_flush("Iniciando MySQLi Transaction \n\n", true);
    foreach ($query_list as $ssql) {
        if (($ssql !== "") && ($ssql !== null)) {
            $count++;
            $count_str = str_pad($count, 5, "0", STR_PAD_LEFT);
            if (!$mysqli->query($ssql)) {
                if ($debug)
                    echo_flush("\n\nErro no SQL $count_str: $ssql \n Nenhuma tabela foi afetada. \n", true);
                $mysqli->rollback();
                $mysqli->autocommit(TRUE);
                return false;
            } else {
                if ($debug)
                    echo_flush("[OK] $count_str: $ssql", true);
            }
        }
    }
    $mysqli->commit();
    $mysqli->autocommit(TRUE);
    $mysqli->close();

    if ($debug)
        echo_flush("Concluído com sucesso. Todas as queries foram Executadas.\n\n", true);
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

function php_mail($nome_para, $email, $mensagem, $assunto, $anexo_path = "") {
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');

    if (PATH_SEPARATOR == ';') {
        $quebra_linha = "\r\n";
    } elseif (PATH_SEPARATOR == ':') {
        $quebra_linha = "\n";
    } elseif (PATH_SEPARATOR != ';' and PATH_SEPARATOR != ':') {
        echo ('Esse script não funcionará corretamente neste servidor, a função PATH_SEPARATOR não retornou o parâmetro esperado.');
    }


    $email_from = "adsource.dev@gmail.com";

    //formato o campo da mensagem
    $mensagem = wordwrap($mensagem, 50, "<br>", 1);

    if ($anexo_path !== "") {
        if (file_exists($anexo_path)) {

            $fp = fopen($anexo_path, "rb");
            $anexo = fread($fp, filesize($anexo_path));
            $anexo = base64_encode($anexo);

            fclose($fp);

            $anexo = chunk_split($anexo);

            $boundary = "XYZ-" . date("dmYis") . "-ZYX";

            $mens = "--$boundary" . $quebra_linha;
            $mens .= "Content-Transfer-Encoding: 8bits" . $quebra_linha;
            $mens .= "Content-Type: text/html; charset=\"ISO-8859-1\"" . $quebra_linha . $quebra_linha;
            $mens .= $mensagem . $quebra_linha;
            $mens .= "--$boundary" . $quebra_linha;
            $mens .= "Content-Type: " . pathinfo($anexo_path, PATHINFO_EXTENSION) . "" . $quebra_linha;
            $mens .= "Content-Disposition: attachment; filename=\"" . pathinfo($anexo_path, PATHINFO_BASENAME) . "\"" . $quebra_linha;
            $mens .= "Content-Transfer-Encoding: base64" . $quebra_linha . $quebra_linha;
            $mens .= $anexo . $quebra_linha;
            $mens .= "--$boundary--" . $quebra_linha;

            $headers = "MIME-Version: 1.0" . $quebra_linha;
            $headers .= "From: $email_from " . $quebra_linha;
            $headers .= "Return-Path: $email_from " . $quebra_linha;
            $headers .= "Content-type: multipart/mixed; boundary=\"$boundary\"" . $quebra_linha;
            $headers .= $boundary . $quebra_linha;


            //envio o email com o anexo
            mail($email, $assunto, $mens, $headers, "-r" . $email_from);

            return true;
        } else {
            return false;
        }
    } else {

        $headers = "MIME-Version: 1.0" . $quebra_linha . "";
        $headers .= "Content-type: text/html; charset=iso-8859-1" . $quebra_linha . "";
        $headers .= "From: $email_from " . $quebra_linha . "";
        $headers .= "Return-Path: $email_from " . $quebra_linha . "";

        //envia o email sem anexo
        mail($email, $assunto, $mensagem, $headers, "-r" . $email_from);


        return true;
    }
}

function busca_string($busca, $str) {
    $busca_u = mb_strtoupper($busca, mb_internal_encoding());
    $str_u = mb_strtoupper($str, mb_internal_encoding());

    $testes[0] = (strpos($str, $busca) !== FALSE);
    $testes[1] = (strpos($str_u, $busca_u) !== FALSE);

    $pos[0] = strpos($str, $busca);
    $pos[1] = strpos($str_u, $busca_u);

    if (in_array(TRUE, $testes, true)) {
        $x = $pos[array_search(TRUE, $testes)];
        $x = substr($str, $x, strlen($busca));
        $str = str_replace($x, "<strong><u>" . $x . "</u></strong>", $str);
        return $str;
    }

    return FALSE;
}

function lista_select_tabela_value_texto($tabela, $value, $texto, $preselect_val = "") {
    $req = "mysqli";
    require "/include.php";
    $select = $mysqli->query("SELECT $value, $texto FROM $tabela ORDER BY $texto ASC");
    if (!$select) {
        echo $mysqli->error;
    }
    while ($row = $select->fetch_array()) {
        $texto_select = utf8_decode($row[$texto]);
        if ($row[$value] == $preselect_val)
            $selected = "selected";
        else
            $selected = "";
        echo '<option value="' . $row[$value] . '" ' . $selected . '>' . $texto_select . '</option>';
    }
    $mysqli->close();
}

function lista_select_tabela_value_texto_where($tabela, $value, $texto, $where_c, $where_r, $preselect_val = "") {
    $req = "mysqli";
    require "/include.php";
    $select = $mysqli->query("SELECT $value, $texto FROM $tabela WHERE $where_c = $where_r ORDER BY $texto ASC");
    while ($row = $select->fetch_array()) {
        $texto_select = utf8_decode($row[$texto]);
        if ($row[$value] == $preselect_val)
            $selected = "selected";
        else
            $selected = "";
        echo '<option value="' . $row[$value] . '" ' . $selected . '>' . $texto_select . '</option>';
    }
    $mysqli->close();
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

function array_to_insert($array, $table) {
    $columns = implode(", ", array_keys($array));
    $escaped_values = array_map('mysql_real_escape_string', array_values($array));
    foreach ($escaped_values as &$value)
        $value = "'" . str_replace("\\x", "", $value) . "'";
    $values = implode(", ", $escaped_values);
    return "INSERT INTO `$table`($columns) VALUES ($values);";
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

/**
 * Verifica se as 8h de um dia se encontra entre duas datas
 * @param  int    $day   Numero do dia do mes atual
 * @param  string $begin Data de inicio do periodo
 * @param  string $end   Data de fim do periodo
 * @return bool            Verdadeiro se as 10h do dia estiver contida no periodo
 */
function in_period($day_number, $begin, $end) {
    if(is_null($end)) {
        $dateEnd = date_create_from_format('d/m/Y H:i:s', date('d/m/Y H:i:s'));
    } else {
        $dateEnd = date_create_from_format('d/m/Y H:i:s', $end);
    }
    $dateEnd = $dateEnd->format('Y-m-d H:i:s');

    $day = date_create_from_format("d/m/Y H:i:s", str_pad(($day_number + 1), 2, "0", STR_PAD_LEFT) . "/" . date("m/Y") . " 10:00:00");
    $dayDate = $day->format('Y-m-d H:i:s');

    $dateBegin = date_create_from_format('d/m/Y H:i:s', $begin);
    $dateBegin = $dateBegin->format('Y-m-d H:i:s');

    if (($dayDate > $dateBegin) && ($dayDate < $dateEnd)) {
      return true;
  } else {
      return false;
  }
}

/**
 * Função para contar tempo no gestor até uma data de referência
 * @param  int   $ta            umero do TA
 * @param  int   $dia           umero do dia de referência, apenas o numero. Será montada uma data com o mês e ano atual
 * @param  array $base          ista das Vidas do TA
 * @param  bool  $RETURN_DAYS   Se verdadeiro, retorna o valor em dias
 * @return int                  Tempo do gestor em Segundos
 */
function conta_tempo($ta, $dia, $base, $RETURN_DAYS = false) {
    $BASE_TA = $base[$ta];

    $dtREFERENCIA = date_create_from_format("d/m/Y H:i:s", str_pad($dia, 2, "0", STR_PAD_LEFT) . "/" . date("m/Y") . " 10:00:00");
    $REFERENCIA = $dtREFERENCIA->format('Y-m-d H:i:s');

    $TEMPO_GESTOR = 0;

    $DEBUG = false;

    if($DEBUG) {echo "<pre>";}

    foreach ($BASE_TA as $tuple) {
        # TODO Calcular tempo no gestor com base no vetor $BASE_TA até o dia $dia

        $dtDATA_INICIAL = date_create_from_format("d/m/Y H:i:s", $tuple['DATA_INICIO_VIDA']);
        $DATA_INICIAL = $dtDATA_INICIAL->format("Y-m-d H:i:s");

        if(is_null($tuple['DATA_FIM_VIDA'])) {
            $dtDATA_FINAL = date_create_from_format('d/m/Y H:i:s', date('d/m/Y H:i:s'));
        } else {
            $dtDATA_FINAL = date_create_from_format('d/m/Y H:i:s', $tuple['DATA_FIM_VIDA']);
        }
        $DATA_FINAL = $dtDATA_FINAL->format('Y-m-d H:i:s');

        $diff = 0;
        if($dtREFERENCIA > $dtDATA_INICIAL){
            if($dtDATA_FINAL > $dtREFERENCIA){
                // REFERENCIA - DATA_INICIO_VIDA
                $diff = $dtREFERENCIA->getTimestamp() - $dtDATA_INICIAL->getTimestamp(); // diferença em segundos

                if($DEBUG) {
                    echo "<br>REFERENCIA - DATA_INICIO_VIDA = $diff";
                    echo "<br>{$REFERENCIA}->getTimestamp(): " . $dtREFERENCIA->getTimestamp();
                    echo "<br>{$DATA_INICIAL}->getTimestamp(): " . $dtDATA_INICIAL->getTimestamp();
                }
            } else {
                // DATA_FIM_VIDA - DATA_INICIO_VIDA
                $diff = $dtDATA_FINAL->getTimestamp() - $dtDATA_INICIAL->getTimestamp(); // idem
                if($DEBUG) {
                    echo "<br>DATA_FIM_VIDA - DATA_INICIO_VIDA = $diff";
                    echo "<br>{$DATA_FINAL}->getTimestamp(): " . $dtDATA_FINAL->getTimestamp();
                    echo "<br>{$DATA_INICIAL}->getTimestamp(): " . $dtDATA_INICIAL->getTimestamp();
                }
            }
            $TEMPO_GESTOR += $diff;
        }

        if($DEBUG) {
            echo "<br>$REFERENCIA > $DATA_INICIAL = " . (($dtREFERENCIA > $dtDATA_INICIAL)? "true" : "false");
            echo "<br>$DATA_FINAL > $REFERENCIA = " . (($dtDATA_FINAL > $dtREFERENCIA)? "true > REFERENCIA - DATA_INICIO_VIDA" : "false > DATA_FIM_VIDA - DATA_INICIO_VIDA");
            echo "<br>(+$diff) $TEMPO_GESTOR";
        }
    }

    if($DEBUG) {echo "</pre>";}

    return ($TEMPO_GESTOR / ($RETURN_DAYS? (24 * 60 * 60) : 1));
}

function processa_ativos_backlog($regiao) {
    $inc = ["oci"];
    require "include.php";

    $qtd_days = (int) date("t");

    $oracle = new SQLDeveloperClient($oci);

    $id_regiao = [
        "CO" => 6395,
        "NO" => 6396,
        "SPI" => 6397
    ];

    $SQL["ATIVO_8H"] = "
    SELECT VDA_TA AS TA,
        VDA_TA_STATUS AS STATUS_VIDA,
        VDA_DATA_INITIAL AS DATA_INICIO_VIDA,
        VDA_DATA_FINAL AS DATA_FIM_VIDA
    FROM SIGITM3.TBL_TA_VIDA INNER JOIN SIGITM3.TBL_TA ON VDA_TA = TQA_CODIGO
    WHERE
        TQA_ORIGEM IS NULL AND
        VDA_RESPONSAVELPOR_GRUPO = {$id_regiao[$regiao]} AND
        VDA_TA_STATUS = 10 AND
        TQA_STATUS != 91 AND
        (VDA_DATA_INITIAL >= ADD_MONTHS((LAST_DAY(SYSDATE)+1),-1) OR
        ((VDA_DATA_FINAL BETWEEN ADD_MONTHS((LAST_DAY(SYSDATE)+1),-1) AND ADD_MONTHS((LAST_DAY(SYSDATE)+1),0)) OR VDA_DATA_FINAL IS NULL))
    ORDER BY VDA_TA, VDA_DATA_INITIAL";

    $ARR["ATIVO_8H"] = $oracle->runSQL($SQL["ATIVO_8H"]);

    $DIAS = [];
    $BASE_TG = [];
    foreach ($ARR["ATIVO_8H"] as $key => $tuple) {
        $BASE_TG[$tuple["TA"]][] = $tuple;
    // echo $tuple["TA"] . "({$tuple["DATA_INICIO_VIDA"]}, {$tuple["DATA_FIM_VIDA"]}) - ";
        for ($i = 0; $i < $qtd_days; $i++) {
            if($i >= date("d")) continue;
        // $dt_ref = str_pad(($i + 1), 2, "0", STR_PAD_LEFT) . "/" . date("m/Y") . " 08:00:00";
            if(in_period($i, $tuple["DATA_INICIO_VIDA"], $tuple["DATA_FIM_VIDA"])) {
            // echo " [" . ($i + 1) . "]";
                if(isset($DIAS[($i + 1)][$tuple["TA"]])) {
                    $DIAS[($i + 1)][$tuple["TA"]]++;
                } else {
                    $DIAS[($i + 1)][$tuple["TA"]] = 1;
                }
            }
        }
    // echo "<br>";
    }

    ksort($DIAS);

    foreach ($DIAS as $key => $value) {
        $ATIVOS[$key] = [
            "QTD" => count(array_keys($value)),
            // "TAS" => array_keys($value)
        ];

        $BACKLOG[$key] = [
            "QTD" => 0,
            // "TAS" => []
        ];

        foreach (array_keys($value) as $ta) {
            $TG_SUM = conta_tempo($ta, $key, $BASE_TG, true);

        // $tastr = str_pad($ta, 8, "0", STR_PAD_LEFT);
        // echo "<pre>$tastr | TG = $TG_SUM</pre>";
            if($TG_SUM > 1) {
                $BACKLOG[$key]["QTD"] += 1;
                // $BACKLOG[$key]["TAS"][] = $ta;
            }
        }
    }

    return [
        "BACKLOG" => $BACKLOG,
        "ATIVO" => $ATIVOS
    ];
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