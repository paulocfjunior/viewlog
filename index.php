<?php

if(isset($_GET["USER"])){
    $owner = $_GET["USER"];
    if(!in_array($owner, [
        32114,
        10000,
        41263,
        43294
    ])){
        die("Owner não registrado.");
    }
} else {
    EXIT;
}

$EXCLUDE = "";
if(isset($_GET["EXCLUDE"])){
    $EXCLUDE = $_GET["EXCLUDE"];
}

$GET_HISTORY = 8;
if(isset($_GET["HISTORY"])){
    if(is_numeric($_GET["HISTORY"])){
        $GET_HISTORY = $_GET["HISTORY"];
    }
}

 ?>

<html>
    <head>
        <title>Dashboard Relatórios</title>
        <link rel="manifest" href="manifest.json">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Sistema de Monitoramento de Acessos aos Relatórios">
        <meta name="author" content="Paulo Cézar Francisco Júnior">
        <script type="text/javascript" src="_js/Chart.bundle.min.js"></script>

        <!-- Add to homescreen for Chrome on Android -->
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="icon" sizes="512x512" href="_img/spy-256.png">


        <!-- Add to homescreen for Safari on iOS -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="SMA Relatórios">
        <link rel="apple-touch-icon-precomposed" href="_img/spy-256.png">

        <!-- Tile icon for Win8 (144x144 + tile color) -->
        <meta name="msapplication-TileImage" content="_img/spy-256.png">
        <meta name="msapplication-TileColor" content="#3372DF">

        <link rel="shortcut icon" href="_img/spy-256.ico">

        <script type="text/javascript" src="_js/default.js"></script>
        <script type="text/javascript" src="_js/charts.lib.js"></script>
        <style type="text/css">
            #chart1 {
                height: 500px !important;
                width: 1000px !important;
                margin: 10px auto;
            }

            #history {
                font-family: Consolas;
                font-size: 10pt;
                margin: 10px auto;
            }

            #history th {
                background-color: #222;
                color: #fff;
            }

            #history th,
            #history td {
                padding: 5px 10px;
                white-space: nowrap;
            }

            #history td.val {
                text-align: center;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <pre>
        <?php

        $req = ["default"];
        include "_php/include.php";

        $SQL = "
        SELECT `QUERY`.`INSTANT`, `QUERY`.`REFERENCIA`, GROUP_CONCAT(DISTINCT `QUERY`.`IP_NOME`) AS IPS_NOMES, COUNT(`QUERY`.`IP_NOME`) AS QTD FROM (
            SELECT
                CONCAT(DATE_FORMAT(`log_datetime`, '%d/%m/%Y %H'), 'h') AS INSTANT,
                `log_ref` AS REFERENCIA,
                CONCAT(CONCAT(`log_ip_client`, ' '), IF(`log_gccr_nome`='', 'Sem dados de sessão', `log_gccr_nome`)) AS IP_NOME
            FROM `log_visualizacao`
            RIGHT JOIN (
                SELECT DISTINCT
                    CONCAT(DATE_FORMAT(`log_datetime`, '%d/%m/%Y %H'), 'h') AS INSTANT
                FROM `log_visualizacao`
                ORDER BY `INSTANT` DESC LIMIT $GET_HISTORY
            ) `DATES` ON CONCAT(DATE_FORMAT(`log_datetime`, '%d/%m/%Y %H'), 'h') = `DATES`.`INSTANT`
            WHERE
                `log_owner` = '$owner'
        ) AS QUERY
        GROUP BY
            `QUERY`.`INSTANT`,
            `QUERY`.`REFERENCIA`";

        $query = sql_execute($SQL);

        $ARR = [];
        $arr_labels = [];
        $htm_labels = "";
        $max = 0;
        while ($row = $query->fetch_assoc()) {
            if($EXCLUDE !== ""){
                if(preg_match("($EXCLUDE)", $row["REFERENCIA"]) === 1){
                    continue;
                }
            }
            if(!in_array($row["INSTANT"], $arr_labels)){
                $arr_labels[] = $row["INSTANT"];
                $htm_labels .= "<th>{$row["INSTANT"]}</th>";
            }
            @$ARR[$row["REFERENCIA"]][$row["INSTANT"]]["QTD"] += $row["QTD"];
            @$ARR[$row["REFERENCIA"]][$row["INSTANT"]]["IPS"] .= $row["IPS_NOMES"];
            if($ARR[$row["REFERENCIA"]][$row["INSTANT"]]["QTD"] > $max) {
                $max = $ARR[$row["REFERENCIA"]][$row["INSTANT"]]["QTD"];
            }
        }

        if($ARR !== []){
            $DATASET = [];
            $COUNT = 1;
            $html = "
                <table id='history' cellspacing=0>
                    <tr>
                        <th>Relatório</th>
                        $htm_labels
                    </tr>
            ";
            foreach ($ARR as $key => &$row) {
                $html .= "<tr><td>$key</td>";
                foreach ($arr_labels as $time) {
                    if(!isset($row[$time]["QTD"])){
                        $row[$time]["QTD"] = 0;
                    }

                    if(!isset($row[$time]["IPS"])){
                        $IPS = "Sem informação";
                    } else {
                        $IPS = str_replace(",", "\n", $row[$time]["IPS"]);
                    }
                    $opacity = ($max === 0)? 0 : round($row[$time]["QTD"]/$max, 2);
                    $color = ($opacity > 0.7)? "#fff" : "#000";
                    if($row[$time]["QTD"] === 0) {
                        $color = "#ccc";
                    }
                    $html .= "<td class='val' title='{$IPS}' style='color: $color; background-color: rgba(100, 100, 255, $opacity);'>{$row[$time]["QTD"]}</td>";
                }
                $html .= "</tr>";
                ksort($row);
                $repeat = count($row);
                $row_qtd = [];
                foreach ($row as $rkey => $rvalue) { $row_qtd[] = $rvalue["QTD"]; }
                $row = implode(", ", $row_qtd);
                $DATASET[] = "{data: [$row], label: '$key', color: [" . str_repeat("$COUNT, ", $repeat) . "]}";
                $COUNT++;
            }
            $html .= "</table>";

            $labels = implode("', '", $arr_labels);
            $DATASETS = implode(", ", $DATASET);

            echo <<<HTML
            </pre>
            <canvas id='chart1'></canvas>
                $html
            <script>
                var chart2 = draw_chart("Log de Acessos", 'line', 'chart1', ['$labels'], [
                    $DATASETS
                ]);
            </script>
HTML;
        } else {
            echo <<<HTML
                <h3>Sem dados de acesso nas últimas $GET_HISTORY horas.</h3>
HTML;
        }

        echo <<<HTML
    </body>
</html>
HTML;
