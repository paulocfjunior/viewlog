<?php

    if (
        !isset($_POST["uid"]) ||
        !isset($_POST["rep_id"]) ||
        !isset($_POST["rep_name"])
    ) {
        echo json_encode([
            "status"  => 0,
            "message" => "Há informações em falta no pedido. (1)"
        ]);
        EXIT;
    } elseif (
        !is_numeric($_POST["rep_id"]) ||
        !is_numeric($_POST["uid"])
    ) {
        echo json_encode([
            "status"  => 0,
            "message" => "Há informações incorretas no pedido. (2)"
        ]);
        EXIT;
    }

    $inc_o = ["default"];
    require "include.php";

    $uid             = $_POST["uid"];
    $rep_id          = $_POST["rep_id"];
    $rep_name        = $_POST["rep_name"];
    $rep_description = (isset($_POST["rep_description"]) ? $_POST["rep_description"] : "");
    $shares          = (isset($_POST["shares"]) ? $_POST["shares"] : "");

    $check_if_exists = sql_execute_scalar("SELECT COUNT(`id`) AS QTD FROM `report` WHERE `owner` = '$uid' AND `id` = '$rep_id'");

    $oper_status   = $shares_status = $DEBUG         = "";

    if ((int) $check_if_exists === 1) {
        // EDIÇÃO
        $SQL         = "UPDATE `report` SET `name`='$rep_name',`description`='$rep_description' WHERE `id` = '$rep_id' AND `owner` = '$uid'";
        $oper_status = "O relatório foi atualizado com sucesso.";
    } else {
        // CRIAÇÃO
        $SQL         = "INSERT INTO `report`(`id`, `owner`, `name`, `description`) VALUES ('$rep_id', '$uid', '$rep_name', '$rep_description')";
        $oper_status = "Relatório criado com sucesso.";
    }

    $RESULT = sql_execute_non_query($SQL);

    if (($shares !== "") && (count($shares) > 0)) {
        $shares_sql   = [];
        $shares_sql[] = "DELETE FROM share WHERE owner_id = $uid AND report_id = '$rep_id'";
        foreach ($shares as $s) {
            $shares_sql[] = "INSERT INTO `share`(`owner_id`, `report_id`, `usr_id`) VALUES ($uid, $rep_id, $s)";
        }
    } else {
        $shares_sql[] = "DELETE FROM share WHERE owner_id = $uid AND report_id = '$rep_id'";
    }

    ob_start();
    $exec  = sql_transaction($shares_sql);
    $DEBUG = ob_get_clean();

    $shares_status = "";
    if ($exec === true) {
        $shares_status = "Compartilhamentos definidos com sucesso.";
    } else {
        $shares_status = "Falha na atualização dos compartilhamentos.";
    }


    if ($RESULT === true) {
        echo json_encode([
            "status"  => 1,
            "message" => "$oper_status $shares_status",
            "console" => "OK"
        ]);
    } else {
        echo json_encode([
            "status"  => 0,
            "message" => "Falha na operação. Informações de debug disponíveis no console.",
            "console" => [
                "SQL"   => $SQL,
                "DEBUG" => $DEBUG
            ]
        ]);
    }
