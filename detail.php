<?php
    $UID   = $UNAME = "";
    if (!isset($_COOKIE["VIEWLOG_LOGIN"])) {
        header("location: login.php");
    } else {
        $COOKIE = json_decode($_COOKIE["VIEWLOG_LOGIN"], true);
        $UID    = $COOKIE["uid"];
        $UNAME  = $COOKIE["name"];
    }

    $inc_o = ["default"];
    require "_php/include.php";

    $OWNER = $UID;
    if (isset($_GET["own"])) {
        $getOwner = $_GET["own"];
        if (is_numeric($getOwner)) {
            $check_if_id_exists = sql_execute_scalar("SELECT COUNT(`uid`) FROM `usr` WHERE `uid` = '$getOwner'");
            if (((int) $check_if_id_exists) === 1) {
                $OWNER = $getOwner;
            }
        }
    }

    if (!isset($_GET["id"])) {
        header("location: ./");
        EXIT;
    } else {
        $REPORT_ID   = $_GET["id"];
        $REPORT_NAME = sql_execute_scalar("SELECT `name` FROM `report` WHERE `owner` = '$OWNER' AND `id` = '$REPORT_ID'");

        if ($REPORT_NAME === NULL) {
            header("location: ./");
            EXIT;
        } else {
            $REPORT_DESC = sql_execute_scalar("SELECT `description` FROM `report` WHERE `owner` = '$OWNER' AND `id` = '$REPORT_ID'");
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="Acompanhamento de Visualização de Relatórios">
        <meta name="author" content="Paulo Cézar">
        <link rel="shortcut icon" href="_img/spy.ico">
        <link rel="manifest" href="manifest.json">
        <!-- Add to homescreen for Chrome on Android -->
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="icon" sizes="512x512" href="_img/spy-256.png">
        <!-- Add to homescreen for Safari on iOS -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="ViewLog">
        <link rel="apple-touch-icon-precomposed" href="_img/spy-256.png">
        <!-- Tile icon for Win8 (144x144 + tile color) -->
        <meta name="msapplication-TileImage" content="_img/spy-256.png">
        <meta name="msapplication-TileColor" content="#3372DF">

        <title>ViewLog - <?= $REPORT_NAME ?></title>

        <!--Stylesheets-->
        <link href="_css/jquery.toast.min.css" rel="stylesheet"/>
        <link href="_css/bootstrap.min.css" rel="stylesheet"/>
        <link href="_css/bootstrap-select.min.css" rel="stylesheet"/>
        <link href="_css/detail.css" rel="stylesheet"/>
        <link href="_css/access-tree.css" rel="stylesheet"/>

        <!--Scripts-->
        <script src="_js/jquery-3.3.1.min.js"></script>
        <script src="_js/respond.min.js"></script>
        <script src="_js/popper.min.js"></script>
        <script src="_js/jquery.toast.min.js"></script>
        <script src="_js/bootstrap.min.js"></script>
        <script src="_js/bootstrap-select.min.js"></script>

        <!-- Resources -->
        <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
        <script src="https://www.amcharts.com/lib/3/serial.js"></script>
        <script src="https://www.amcharts.com/lib/3/radar.js"></script>
        <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
        <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
        <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>

        <!--<script src="https://js.pusher.com/4.1/pusher.min.js"></script>
        <script>
            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true;

            var pusher = new Pusher('7c379d1619b8599e7b23', {
                cluster: 'us2',
                encrypted: true
            });

            var channel = pusher.subscribe('viewlog-visualizations');
            channel.bind('new-visualization', function (data) {
                $.toast({
                    heading: 'Notificação Pusher',
                    text: data.message,
                    hideAfter: 2000,
                    icon: 'info',
                    loader: true
                });
            });
        </script>-->

        <?php

            ob_start();
            $access_summary = access_tree($OWNER, $REPORT_ID);
            $tree = ob_get_clean();
            
        ?>

        <script type="text/javascript">
            var owner = '<?= $OWNER ?>';
            var report = '<?= $REPORT_ID ?>';

            var radarData = <?= radarData() ?>;
            var chartData = <?= principalChardData($access_summary) ?>;

        </script>
        <script src="_js/detail.js"></script>
    </head>

    <body>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./">Início</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $REPORT_NAME ?></li>
            </ol>
        </nav>
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-6"><?= $REPORT_NAME ?></h1>
                <p class="lead"><?= $REPORT_DESC ?></p>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-sm-12">
                <div id="chart-card-principal" class="card text-center">
                    <!--<div class="card-header">
                        Featured
                    </div>-->
                    <div class="card-body">
                        <h5 class="card-title">Histórico completo de visualizações</h5>
                        <p class="card-text"><div id="chartdiv"></div></p>
                    </div>
                    <div class="card-footer text-muted">
                        <span>Buscando dados do último acesso...</span>
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <div id="chart-card-split-1" class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><span>Média de acessos por hora</span></h5>
                        <p class="card-text"><div id="chartdiv2"></div></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card" id="tree">
                    <div class="card-body">
                        <h5 class="card-title"><span>Todos os acessos</span></h5>
                        <p class="card-text" id="tree-list-container">
                            <?=$tree?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <script type="text/javascript">
            function changeText(selector, newText) {
                $(selector).fadeOut(200, function () {
                    $(selector).html(newText).fadeIn(200);
                });
            }
            var update = 0;

            setInterval(function () {
                $.get("_php/get_last_view.php?o=" + owner + "&r=" + report, function (res) {
                    try {
                        var r = JSON.parse(res);
                        var sel = "#chart-card-principal .card-footer span";

                        if ($(sel).text() !== r.result) {
                            changeText(sel, r.result);
                            if(update !== 2) {
                                update = 1;
                            }
                        }
                    } catch (exception) {
                        console.log(res);
                        console.log(exception);
                    }
                });
            }, 500);

            setInterval(function () {
                if(update === 1){
                    update = 2;
                    var toastUpdate = $.toast({heading: 'Novo acesso detectado!',
                        text: "Atualizando gráficos...",
                        icon: "info",
                        showHideTransition: "slide",
                        hideAfter: false,
                        loader: true,
                        stack: 3
                    });
                    $.post("_php/get_detail_data_updated.php?o=" + owner + "&r=" + report, function(r){
                        try {
                            var response = JSON.parse(r);
                            for(var chartData in response.charts){
                                if (response.hasOwnProperty(chartData)) {
                                    console.log(window[chartData]);
                                    window[chartData].dataProvider = response[chartData];
                                    window[chartData].validateData();
                                }
                            }

                            if(typeof response.list !== 'undefined'){
                                $("#tree-list-container").html(response.list);
                            }

                            toastUpdate.reset();
                            $.toast({
                                heading: 'Atualizar gráficos',
                                text: "Gráficos atualizados com sucesso",
                                icon: "success",
                                hideAfter: 1000,
                                loader: true,
                                stack: 3
                            });
                        } catch (e) {
                            toastUpdate.reset();
                            $.toast({
                                heading: 'Atualizar gráficos',
                                text: "Falha na atualização dos gráficos<br>" + e,
                                hideAfter: 4000,
                                icon: "error",
                                loader: true,
                                stack: 3
                            });
                            console.error(e);
                            console.log(r);
                        }
                        update = 0;
                    });
                }
            }, 1000);
        </script>
    </body>
</html>
