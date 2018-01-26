<?php

if(
    !isset($_POST["uid"]) ||
    !isset($_POST["rep_id"]) ||
    !isset($_POST["rep_name"])
){
    echo json_encode([
        "status" => 0,
        "message" => "Há informações em falta no pedido."
    ]);
    EXIT;
} elseif (
    !is_numeric($_POST["rep_id"]) ||
    !is_numeric($_POST["uid"])
){
    echo json_encode([
        "status" => 0,
        "message" => "Há informações incorretas no pedido."
    ]);
    EXIT;
}

$inc_o = ["default"];
require "include.php";

$uid = $_POST["uid"];
$rep_id = $_POST["rep_id"];
$rep_name = $_POST["rep_name"];
$rep_description = (isset($_POST["rep_description"])? $_POST["rep_description"] : "");
$shares = (isset($_POST["shares"])? $_POST["shares"] : "");

$update_report["SQL"] = "UPDATE `report` SET `name`='$rep_name',`description`='$rep_description' WHERE `id` = '$rep_id' AND `owner` = '$uid'";
$update_report["QUERY"] = sql_execute_non_query($update_report["SQL"]);
$shares_status = "";

if(count($shares) > 0) {
    $shares_sql = [];
    $shares_sql[] = "DELETE FROM share WHERE owner_id = $uid AND report_id = '$rep_id'";
    foreach ($shares as $s) {
        $shares_sql[] = "INSERT INTO `share`(`owner_id`, `report_id`, `usr_id`) VALUES ($uid, $rep_id, $s)";
    }
    $exec = sql_transaction($shares_sql);
    if($exec === true) {
        $shares_status = "Compartilhamentos definidos com sucesso.";
    } else {
        $shares_status = "Falha na atualização dos compartilhamentos.";
    }
}

if($update_report["QUERY"] === true){
    echo json_encode([
        "status" => 1,
        "message" => "Relatório atulizado com sucesso. $shares_status",
        "console" => "Atualizado."
    ]);
} else {
    echo json_encode([
        "status" => 0,
        "message" => "Falha na atualização do relatório.",
        "console" => $update_report["SQL"]
    ]);
}