<?php

if (!isset($_COOKIE["VIEWLOG_LOGIN"])) {
    header("location: login.php");
} else {
    $COOKIE = json_decode($_COOKIE["VIEWLOG_LOGIN"], true);
    $UID    = $COOKIE["uid"];
}

$req_o = "default";
include "include.php";

$REPORTS["SQL"]   = "SELECT * FROM `report` WHERE `owner` = '$UID'";
$REPORTS["QUERY"] = sql_execute($REPORTS["SQL"]);

$RETURN = [];
while ($REPORTS["ROW"]   = $REPORTS["QUERY"]->fetch_assoc()) {
    $REPORT_DATA["SQL"]   =
        "SELECT DATE_FORMAT(`dt`, '%Y-%m-%d') AS 'date', COUNT(id) AS 'value' FROM `viewlog` WHERE `owner_id` = '$UID' AND `rep_id` = '{$REPORTS["ROW"]["id"]}' GROUP BY DATE_FORMAT(`dt`, '%Y-%m-%d')";
    $REPORT_DATA["QUERY"] = sql_execute($REPORT_DATA["SQL"]);
    
    if ($REPORT_DATA["QUERY"]->num_rows === 0) {
        $RETURN["chart_{$REPORTS["ROW"]["owner"]}_{$REPORTS["ROW"]["id"]}"] = [
            "data" => [],
            "class" => "fail-message"
        ];
    } else {
        $REPORT_DATA["JS"] = [];
        
        while ($REPORT_DATA["ROW"] = $REPORT_DATA["QUERY"]->fetch_object()) {
            $REPORT_DATA["JS"][] = ["date" => $REPORT_DATA["ROW"]->date, "value" => (int)$REPORT_DATA["ROW"]->value];
        }
        
        // $REPORT_DATA["JS"]   = implode(",", $REPORT_DATA["JS"]);
        $RETURN["chart_{$REPORTS["ROW"]["owner"]}_{$REPORTS["ROW"]["id"]}"] = [
            "data" => array_values($REPORT_DATA["JS"]),
            "class" => "graph"
        ];
    }
}

echo json_encode($RETURN);