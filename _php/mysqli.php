<?php

    // $mysqli = new mysqli("estatistica.mysql.uhserver.com", "estatistica_tel", "estat.2017", "estatistica");
	$mysqli = new mysqli("localhost", "root", "ckbgdhppedp7p3a466p", "viewlog");
    $mysqli->query("SET NAMES 'utf8'");
    $mysqli->query("SET character_set_connection=utf8");
    $mysqli->query("SET character_set_client=utf8");
    $mysqli->query("SET character_set_results=utf8");
    if ($mysqli->connect_errno) {
        echo "Não foi possível conectar ao MySQL. <br><b>Código do erro: " . $mysqli->connect_error;
    }

 ?>