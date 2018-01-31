<?php

    
    if(
        !isset($_GET["uid"]) ||
        !isset($_GET["rep_id"])
    ){
        echo json_encode([
            "status" => 0,
            "message" => "Há informações em falta no pedido.",
            "console" => ($_GET["uid"]) . " | " . $_GET["rep_id"]
        ]);
        EXIT;
    } elseif (
        !is_numeric($_GET["rep_id"]) ||
        !is_numeric($_GET["uid"])
    ){
        ob_start();
        var_dump($_GET["uid"]);
        var_dump($_GET["rep_id"]);
        $DEBUG = ob_get_clean();
        echo json_encode([
            "status" => 0,
            "message" => "Há informações incorretas no pedido.",
            "console" => $DEBUG
        ]);
        EXIT;
    } else {
        $inc_o = ["default"];
        require "include.php";
        
        $uid = $_GET["uid"];
        $rep_id = $_GET["rep_id"];
        
        $DATA["SQL"] = "SELECT *, DATE_FORMAT(`dt`, '%Y-%m-%d') AS 'dt_formated', DATE_FORMAT(`dt`, '%H') AS 'hour' FROM `viewlog` WHERE `owner_id` = '$uid' AND `rep_id` = '$rep_id' ORDER BY `dt` DESC";
        $DATA["QUERY"] = sql_execute($DATA["SQL"]);
        if($DATA["QUERY"] !== false){
            while($DATA["ROW"] = $DATA["QUERY"]->fetch_assoc()){
                $RESULT[$DATA["ROW"]["dt_formated"]][$DATA["ROW"]["hour"]][] = [
                    "dt" => $DATA["ROW"]["dt"],
                    "ref" => $DATA["ROW"]["ref"],
                    "ip" => $DATA["ROW"]["ip"],
                    "gccr_id" => $DATA["ROW"]["gccr_id"],
                    "gccr_name" => $DATA["ROW"]["gccr_name"]
                ];
            }
            echo json_encode([
                "status" => 1,
                "console" => "",
                "rows" => $DATA["QUERY"]->num_rows,
                "data" => $RESULT
            ]);
        } else {
            echo json_encode([
                "status" => 0,
                "console" => "Falha ao tentar executar SQL: " . $DATA["SQL"],
                "rows" => 0,
                "data" => []
            ]);
        }
    }
    