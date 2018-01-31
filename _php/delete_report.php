<?php

if(
    !isset($_POST["uid"]) ||
    !isset($_POST["rep_id"])
){
    echo json_encode([
        "status" => 0,
        "message" => "Há informações em falta no pedido.",
        "console" => ($_POST["uid"]) . " | " . $_POST["rep_id"]
    ]);
    EXIT;
} elseif (
    !is_numeric($_POST["rep_id"]) ||
    !is_numeric($_POST["uid"])
){
    ob_start();
    var_dump($_POST["uid"]);
    var_dump($_POST["rep_id"]);
    $DEBUG = ob_get_clean();
    echo json_encode([
        "status" => 0,
        "message" => "Há informações incorretas no pedido.",
        "console" => $DEBUG
    ]);
    EXIT;
}

$inc_o = ["default"];
require "include.php";

$uid = $_POST["uid"];
$rep_id = $_POST["rep_id"];

$delete_report = [
    "DELETE FROM `report` WHERE `id` = '$rep_id' AND `owner` = '$uid'",
    "DELETE FROM `share` WHERE `report_id` = '$rep_id' AND `owner_id` = '$uid'"
];
$exec = sql_transaction($delete_report);
if($exec === true) {
    echo json_encode([
        "status" => 1,
        "message" => "Relatório e compartilhamentos excluiídos com sucesso.",
        "console" => "Excluído."
    ]);
} else {
    echo json_encode([
        "status" => 0,
        "message" => "Falha na exclusão do relatório.",
        "console" => $delete_report
    ]);
}