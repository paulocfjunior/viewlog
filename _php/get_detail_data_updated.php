<?php

if (!isset($_COOKIE["VIEWLOG_LOGIN"])) {
    header("location: login.php");
}

if(
    !isset($_GET["o"]) ||
    !isset($_GET["r"])
) {
    echo json_encode([
        "status" => 0,
        "message" => "Parâmetros incorretos (indefinidos)" . print_r($_GET, true)
    ]);
} else if(
        !is_numeric($_GET["o"]) ||
        !is_numeric($_GET["r"])
    ) {
    echo json_encode([
        "status" => 0,
        "message" => "Parâmetros incorretos (tipos) " . print_r($_GET, true)
    ]);
}

$req_o = "default";
include "include.php";

$OWNER = $_GET["o"];
$REPORT_ID = $_GET["r"];

ob_start();
$access_summary = access_tree($OWNER, $REPORT_ID);
$tree_list = ob_get_clean();

echo json_encode([
    "charts" => [
        "chart" => principalChardData($access_summary, false),
        "chart2" => radarData(false)
    ],
    "list" => $tree_list
]);
