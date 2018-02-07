<?php

    if (
        !isset($_GET["o"]) ||
        !isset($_GET["r"])
    ) {
        echo json_encode([
            "status"  => 0,
            "console" => "Parâmetros incorretos: " . print_r($_GET, true)
        ]);
        EXIT;
    }

    if (
        !is_numeric($_GET["o"]) ||
        !is_numeric($_GET["r"])
    ) {
        echo json_encode([
            "status"  => 0,
            "console" => "Parâmetros incorretos: " . print_r($_GET, true)
        ]);
        EXIT;
    }

    $inc_o = ["default"];
    require "include.php";

    $OWNER  = $_GET["o"];
    $REPORT = $_GET["r"];

    $ssql  = "SELECT DATE_FORMAT(`dt`, '%H:%i:%s') AS 'hora', DATE_FORMAT(`dt`, '%d/%m/%Y') AS 'data', `ip`, `gccr_id`, `gccr_name` FROM `viewlog` WHERE `owner_id` = '$OWNER' AND `rep_id` = '$REPORT' ORDER BY `dt` DESC LIMIT 1";
    $query = sql_execute($ssql);

    $row = $query->fetch_assoc();

    echo json_encode([
        "status"  => 1,
        "result"  => "Último acesso às " . $row["hora"] . ((date("d/m/Y") == $row["data"]) ? " de hoje" : " do dia " . $row["data"]) . " com o IP " . $row["ip"] . (($row["gccr_id"] != 0) ? " - ID TEL " . $row["gccr_id"] : "") . ((($row["gccr_name"] !== "") && (!is_numeric($row["gccr_name"]))) ? " - " . $row["gccr_name"] : ""),
        "console" => ""
    ]);

