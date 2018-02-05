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

    $SQL            = <<<SQL
        SELECT IF(MAX(`id`) IS NULL, 0, MAX(`id`)+1) AS NEXT_ID FROM `report` WHERE `owner` = '$UID'
SQL;
    $NEXT_REPORT_ID = sql_execute_scalar($SQL);

    $REDIRECT_PAGE = [
        "v-pills-detail" => "v-pills-dashboard"
    ];

    $PAGE_TITLES = [
        "v-pills-dashboard"         => "Dashboard",
        "v-pills-dashboard-sharing" => "Compartilhados",
        "v-pills-detail"            => "Detalhes",
        "v-pills-profile"           => "Perfil",
        "v-pills-reports"           => "Meus relatórios"
    ];

    if (isset($_COOKIE["CURRENT_PAGE"]) && $_COOKIE["CURRENT_PAGE"] !== "undefined") {
        if (array_key_exists($_COOKIE["CURRENT_PAGE"], $REDIRECT_PAGE)) {
            $CURRENT_PAGE = $REDIRECT_PAGE[$_COOKIE["CURRENT_PAGE"]];
        } else {
            $CURRENT_PAGE = $_COOKIE["CURRENT_PAGE"];
        }
    } else {
        $CURRENT_PAGE = "v-pills-dashboard";
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

        <title>ViewLog <?= (" - " . $PAGE_TITLES[$CURRENT_PAGE]) ?></title>

        <!--Stylesheets-->
        <link href="_css/jquery.toast.min.css" rel="stylesheet"/>
        <link href="_css/bootstrap.min.css" rel="stylesheet"/>
        <link href="_css/bootstrap-select.min.css" rel="stylesheet"/>
        <link href="_css/app.css" rel="stylesheet"/>

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
        <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
        <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
        <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>

        <script src="_js/amchart.js"></script>
    </head>

    <body>

        <div class="row">
            <div class="nav flex-column nav-pills col-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <div id="owner" db-id="<?= $UID ?>">
                    <?= $UNAME ?>
                </div>
                <a class="nav-link <?= ($CURRENT_PAGE == "v-pills-dashboard" ? "active" : "") ?>"
                   id="v-pills-dashboard-tab"
                   data-toggle="pill"
                   href="#v-pills-dashboard"
                   role="tab"
                   aria-controls="v-pills-dashboard"
                   aria-selected="true">
                    Dashboard
                </a>
                <a class="nav-link <?= ($CURRENT_PAGE == "v-pills-dashboard-sharing" ? "active" : "") ?>"
                   id="v-pills-dashboard-sharing-tab"
                   data-toggle="pill"
                   href="#v-pills-dashboard-sharing"
                   role="tab"
                   aria-controls="v-pills-dashboard-sharing"
                   aria-selected="true">
                    Dashboard Sharing
                </a>
                <a class="nav-link <?= ($CURRENT_PAGE == "v-pills-profile" ? "active" : "") ?>"
                   id="v-pills-profile-tab"
                   data-toggle="pill"
                   href="#v-pills-profile"
                   role="tab"
                   aria-controls="v-pills-profile"
                   aria-selected="false">
                    Perfil
                </a>
                <a class="nav-link <?= ($CURRENT_PAGE == "v-pills-reports" ? "active" : "") ?>"
                   id="v-pills-reports-tab"
                   data-toggle="pill"
                   href="#v-pills-reports"
                   role="tab"
                   aria-controls="v-pills-reports"
                   aria-selected="false">
                    Meus relatórios
                </a>
                <a class="nav-link"
                   href="logout.php">
                    Sair
                </a>
            </div>
            <div class="tab-content col-10" id="v-pills-tabContent">
                <div class="tab-pane fade <?= ($CURRENT_PAGE == "v-pills-dashboard" ? "active show" : "") ?>"
                     id="v-pills-dashboard" role="tabpanel" aria-labelledby="v-pills-dashboard-tab">

                    <h1>Dashboard</h1>
                    <h2>Painel de acompanhamento de visualizações</h2>
                    <input class="form-control" id="search" type="text" placeholder="Filtrar relatórios">
                    <small id="search-count"></small>
                    <hr/>

                    <!--
                    <div class="card">
                        <div id="featured" class="card-header"></div>
                        <div class="card-body">
                            <h5 class="card-title">Recentemente Visualizados</h5>
                            <a class="btn btn-primary detail"
                               id="v-pills-detail-tab"
                               data-toggle="pill"
                               href="#v-pills-detail"
                               role="tab"
                               aria-controls="v-pills-detail"
                               aria-selected="false">
                                Detalhes
                            </a>
                        </div>
                    </div>
                    -->

                    <?php
                        $REPORTS["SQL"]   = "SELECT * FROM `report` WHERE `owner` = '$UID'";
                        $REPORTS["QUERY"] = sql_execute($REPORTS["SQL"]);
                        while ($REPORTS["ROW"]   = $REPORTS["QUERY"]->fetch_assoc()) {
                            $REPORT_DATA["SQL"]   = "SELECT * FROM `viewlog` WHERE `owner_id` = '$UID' AND `rep_id` = '{$REPORTS["ROW"]["id"]}';";
                            $REPORT_DATA["SQL"]   = "SELECT DATE_FORMAT(`dt`, '%Y-%m-%d') AS 'date', COUNT(id) AS 'value' FROM `viewlog` WHERE `owner_id` = '$UID' AND `rep_id` = '{$REPORTS["ROW"]["id"]}' GROUP BY DATE_FORMAT(`dt`, '%Y-%m-%d')";
                            $REPORT_DATA["QUERY"] = sql_execute($REPORT_DATA["SQL"]);

                            $GRAPH_CONFIGURATION = "";
                            $GRAPH_FAILMESSAGE   = "";
                            if ($REPORT_DATA["QUERY"]->num_rows === 0) {
                                $GRAPH_FAILMESSAGE = "<span class='empty_graph'>Este relatório ainda não possui visualizações.</span>";
                                $GRAPH_CLASS       = "fail-message";
                            } else {
                                $REPORT_DATA["ARRAY"] = [];
                                $REPORT_DATA["JS"]    = [];

                                while ($REPORT_DATA["ROW"] = $REPORT_DATA["QUERY"]->fetch_object()) {
                                    array_push($REPORT_DATA["JS"], "{date: '{$REPORT_DATA["ROW"]->date}', value: {$REPORT_DATA["ROW"]->value}}");
                                    array_push($REPORT_DATA["ARRAY"], [$REPORT_DATA["ROW"]->date => $REPORT_DATA["ROW"]->value]);
                                }

                                $REPORT_DATA["JS"]   = implode(",", $REPORT_DATA["JS"]);
                                $GRAPH_CONFIGURATION = "<script>"
                                    . "var chart_{$REPORTS["ROW"]["owner"]}_{$REPORTS["ROW"]["id"]} = draw('report-{$REPORTS["ROW"]["owner"]}-{$REPORTS["ROW"]["id"]}', "
                                    . "[{$REPORT_DATA["JS"]}]);"
                                    . "</script>";
                                $GRAPH_CLASS         = "graph";
                            }

                            echo "
                                <div class=\"card\" search='{$REPORTS["ROW"]["id"]}|{$REPORTS["ROW"]["name"]}|{$REPORTS["ROW"]["description"]}'>
                                    $GRAPH_CONFIGURATION
                                    <div id=\"report-{$REPORTS["ROW"]["owner"]}-{$REPORTS["ROW"]["id"]}\" class=\"card-header $GRAPH_CLASS\">$GRAPH_FAILMESSAGE</div>
                                    <div class=\"card-body\">
                                        <h5 class=\"card-title\">{$REPORTS["ROW"]["name"]}</h5>
                                        <p class=\"card-text\">{$REPORTS["ROW"]["description"]}</p>
                                        <a class=\"btn btn-primary detail\"
                                           id=\"v-pills-detail-tab\"
                                           data-toggle=\"pill\"
                                           href=\"#v-pills-detail\"
                                           role=\"tab\"
                                           owner=\"{$REPORTS["ROW"]["owner"]}\"
                                           report=\"{$REPORTS["ROW"]["id"]}\"
                                           reportName=\"{$REPORTS["ROW"]["name"]}\"
                                           aria-controls=\"v-pills-detail\"
                                           aria-selected=\"false\">
                                            Detalhes
                                        </a>
                                    </div>
                                </div>";
                        }
                    ?>

                </div>

                <div class="tab-pane fade <?= ($CURRENT_PAGE == "v-pills-dashboard-sharing" ? "active show" : "") ?>"
                     id="v-pills-dashboard-sharing" role="tabpanel" aria-labelledby="v-pills-dashboard-sharing-tab">

                    <h1>Dashboard Relatórios Compartilhados</h1>
                    <h2>Painel de acompanhamento de visualizações dos relatórios compartilhados</h2>
                    <input class="form-control" id="shared-search" type="text" placeholder="Filtrar relatórios">
                    <small id="shared-search-count"></small>
                    <hr/>

                    <?php
                        $SHARED_REPORTS["SQL"]   = "
                            SELECT s.owner_id AS owner_id, u.name AS owner_name, s.report_id AS report_id, r.name AS report_name, r.description AS report_description
                            FROM `report` r
                                INNER JOIN `share` s ON r.`id` = s.`report_id` AND r.`owner` = s.`owner_id`
                                INNER JOIN `usr` u ON s.`owner_id` = u.`uid`
                            WHERE s.`usr_id` = '$UID' ORDER BY s.`owner_id`, r.`name`";
                        $SHARED_REPORTS["QUERY"] = sql_execute($SHARED_REPORTS["SQL"]);

                        while ($SHARED_REPORTS["ROW"] = $SHARED_REPORTS["QUERY"]->fetch_assoc()) {
                            $REPORT_DATA["SQL"]          = "SELECT * FROM `viewlog` WHERE `owner_id` = '{$SHARED_REPORTS["ROW"]["owner_id"]}' AND `rep_id` = '{$SHARED_REPORTS["ROW"]["report_id"]}';";
                            $SHARED_REPORT_DATA["SQL"]   = "SELECT DATE_FORMAT(`dt`, '%Y-%m-%d') AS 'date', COUNT(id) AS 'value' FROM `viewlog` WHERE `owner_id` = '{$SHARED_REPORTS["ROW"]["owner_id"]}' AND `rep_id` = '{$SHARED_REPORTS["ROW"]["report_id"]}' GROUP BY DATE_FORMAT(`dt`, '%Y-%m-%d')";
                            $SHARED_REPORT_DATA["QUERY"] = sql_execute($SHARED_REPORT_DATA["SQL"]);

                            $GRAPH_CONFIGURATION = "";
                            $GRAPH_FAILMESSAGE   = "";
                            if ($SHARED_REPORT_DATA["QUERY"]->num_rows === 0) {
                                $GRAPH_FAILMESSAGE = "<span class='empty_graph'>Este relatório ainda não possui visualizações.</span>";
                                $GRAPH_CLASS       = "fail-message";
                            } else {
                                $SHARED_REPORT_DATA["ARRAY"] = [];
                                $SHARED_REPORT_DATA["JS"]    = [];

                                while ($SHARED_REPORT_DATA["ROW"] = $SHARED_REPORT_DATA["QUERY"]->fetch_object()) {
                                    array_push($SHARED_REPORT_DATA["JS"], "{date: '{$SHARED_REPORT_DATA["ROW"]->date}', value: {$SHARED_REPORT_DATA["ROW"]->value}}");
                                    array_push($SHARED_REPORT_DATA["ARRAY"], [$SHARED_REPORT_DATA["ROW"]->date => $SHARED_REPORT_DATA["ROW"]->value]);
                                }

                                $SHARED_REPORT_DATA["JS"] = implode(",", $SHARED_REPORT_DATA["JS"]);
                                $GRAPH_CONFIGURATION      = "<script>"
                                    . "var shared_chart_{$SHARED_REPORTS["ROW"]["owner_id"]}_{$SHARED_REPORTS["ROW"]["report_id"]} = draw('shared-report-{$SHARED_REPORTS["ROW"]["owner_id"]}-{$SHARED_REPORTS["ROW"]["report_id"]}', "
                                    . "[{$SHARED_REPORT_DATA["JS"]}]);"
                                    . "</script>";
                                $GRAPH_CLASS              = "graph";
                            }

                            echo "
                                <div class=\"card shared-card\" shared-search='{$SHARED_REPORTS["ROW"]["report_id"]}|{$SHARED_REPORTS["ROW"]["report_name"]}|{$SHARED_REPORTS["ROW"]["report_description"]}|{$SHARED_REPORTS["ROW"]["owner_name"]}'>
                                    $GRAPH_CONFIGURATION
                                    <div id=\"shared-report-{$SHARED_REPORTS["ROW"]["owner_id"]}-{$SHARED_REPORTS["ROW"]["report_id"]}\" class=\"card-header $GRAPH_CLASS\">$GRAPH_FAILMESSAGE</div>
                                    <div class=\"card-body\">
                                        <h5 class=\"card-title\"><span class=\"badge badge-secondary\">{$SHARED_REPORTS["ROW"]["owner_name"]}</span> {$SHARED_REPORTS["ROW"]["report_name"]}</h5>
                                        <p class=\"card-text\">{$SHARED_REPORTS["ROW"]["report_description"]}</p>
                                        <a class=\"btn btn-primary detail shared\"
                                           id=\"v-pills-detail-tab\"
                                           data-toggle=\"pill\"
                                           href=\"#v-pills-detail\"
                                           role=\"tab\"
                                           owner=\"{$SHARED_REPORTS["ROW"]["owner_id"]}\"
                                           report=\"{$SHARED_REPORTS["ROW"]["report_id"]}\"
                                           reportName=\"{$SHARED_REPORTS["ROW"]["report_name"]}\"
                                           aria-controls=\"v-pills-detail\"
                                           aria-selected=\"false\">
                                            Detalhes
                                        </a>
                                    </div>
                                </div>";
                        }
                    ?>

                </div>
                <div class="tab-pane fade <?= ($CURRENT_PAGE == "v-pills-detail" ? "active show" : "") ?>"
                     id="v-pills-detail"
                     role="tabpanel"
                     aria-labelledby="v-pills-detail-tab">

                    <h1 id='title-detail'>Detalhes</h1>
                    <h2>Detalhes de acesso ao relatório <span id='detail-report-name'></span></h2>
                    <div id="detail-loading" class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                        </div>
                    </div>
                    <hr/>
                    <div id="detail-content">
                        <div class='card'>
                            <div class='card-body'>
                                <h5 class='card-title'>Volume de acesso</h5>
                                <p class='card-text'>Acessos mais recentes por dia</p>
                            </div>
                            <hr/>
                            <div id='report-detail' class='card-header graph'></div>
                        </div>
                        <hr/>
                        <div class='card'>
                            <div class='card-body'>
                                <h5 class='card-title'>Relação dos ultimos acessos</h5>
                                <p class='card-text'>Agrupados por IP e Usuário (quando disponível)</p>
                            </div>
                            <div class='card-header'><div id='detail-table'></div></div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade <?= ($CURRENT_PAGE == "v-pills-profile" ? "active show" : "") ?>"
                     id="v-pills-profile"
                     role="tabpanel"
                     aria-labelledby="v-pills-profile-tab">

                    <h1>Perfil</h1>
                    <h2>Atualize suas informações pessoais</h2>
                    <hr/>
                    <?php
                        if (isset($_COOKIE["VIEWLOG_UPDATE_PROFILE_ERROR"])) {
                            echo /** @lang html */
                            "
                            <div class=\"alert alert-danger\" role=\"alert\">
                                {$_COOKIE["VIEWLOG_UPDATE_PROFILE_ERROR"]}
                            </div>
                        ";
                        }
                    ?>
                    <form action="_php/update_profile.php" method="post">
                        <div class="row">
                            <div class="col">
                                <input class="form-control" name="new_name" placeholder="Nome" value="<?= $UNAME ?>"/>
                            </div>
                            <div class="col">
                                <input type="password" name="new_pwd" class="form-control" placeholder="Nova senha">
                            </div>
                            <div class="col">
                                <input type="password" name="new_pwd_confirm" class="form-control"
                                       placeholder="Confirmar senha">
                            </div>
                            <div class="col">
                                <button class="btn btn-primary">Salvar</button>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="tab-pane fade <?= ($CURRENT_PAGE == "v-pills-reports" ? "active show" : "") ?>"
                     id="v-pills-reports"
                     role="tabpanel"
                     aria-labelledby="v-pills-reports-tab">

                    <h1>Meus relatórios</h1>
                    <h2>Adicione, edite, remova ou compartilhe seus relatórios</h2>
                    <hr/>
                    <div id="edit-my-report">
                        <div class="form-group row">
                            <div class="col-1">
                                <input class="form-control" id="my-report-edit-id" placeholder="ID"
                                       value="<?= $NEXT_REPORT_ID ?>"/>
                            </div>
                            <div class="col-11">
                                <input class="form-control" placeholder="Nome do Relatório" id="my-report-edit-name"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="my-report-edit-description">Descrição</label>
                            <textarea class="form-control" id="my-report-edit-description" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <?php
                                    $users["SQL"]   = "SELECT `uid`, `name` FROM `usr` WHERE `uid` != '{$UID}'";
                                    $users["QUERY"] = sql_execute($users["SQL"]);
                                    $i              = 0;
                                    while ($users["ROW"]   = $users["QUERY"]->fetch_assoc()) {
                                        if (( ++$i % 9) === 0) {
                                            echo "</div><br /><div class=\"form-check form-check-inline\">";
                                        }
                                        echo /** @lang HTML */
                                        "
                                            <div class='users'>
                                                <input class=\"form-check-input\" type=\"checkbox\" id=\"usr-{$users["ROW"]["uid"]}\">
                                                <label class=\"form-check-label\" for=\"usr-{$users["ROW"]["uid"]}\">
                                                    {$users["ROW"]["name"]}
                                                </label>
                                            </div>
                                        ";
                                    }
                                ?>

                            </div>
                        </div>
                        <div class="form-group">
                            <button id="my-report-edit-save" type="button" class="btn btn-primary">Salvar</button>
                            <button id="my-report-edit-show-modal" data-toggle="modal" data-target="#modalExcluirRelatorio"
                                    type="button" class="btn btn-danger" disabled>Excluir
                            </button>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="modalExcluirRelatorio" tabindex="-1" role="dialog"
                             aria-labelledby="modalExcluirRelatorioLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalExcluirRelatorioLabel">Excluir relatório do
                                            acompanhamento</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Tem certeza que deseja excluir relatório de todos os acompanhamentos e
                                        compartilhamentos?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <button id="my-report-edit-del" type="button" class="btn btn-danger">Excluir</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="list-group">
                        <?php
                            $my_reports["SQL"]   = "SELECT `id`, `name`, `description` FROM `report` WHERE `owner` = '$UID'";
                            $my_reports["QUERY"] = sql_execute($my_reports["SQL"]);

                            while ($my_reports["ROW"] = $my_reports["QUERY"]->fetch_assoc()) {
                                $sharing["SQL"]   = "SELECT `usr_id`, `usr`.`name` FROM `share` INNER JOIN `usr` ON `usr`.`uid` = `usr_id` WHERE `owner_id` = '$UID' and `report_id` = '{$my_reports["ROW"]["id"]}'";
                                $sharing["QUERY"] = sql_execute($sharing["SQL"]);
                                $sharing_ids      = "";
                                if (($sharing["QUERY"] === false) || ($sharing["QUERY"]->num_rows === 0)) {
                                    $sharing_pills = "<span class=\"badge badge-secondary\">Sem compatilhamentos</span>";
                                } else {
                                    $sharing_ids    = [];
                                    $sharing_pills  = [];
                                    while ($sharing["ROW"] = $sharing["QUERY"]->fetch_assoc()) {
                                        $sharing_pills[] = "<span class=\"badge badge-primary\">{$sharing["ROW"]["name"]}</span>";
                                        $sharing_ids[]   = $sharing["ROW"]["usr_id"];
                                    }
                                    $sharing_pills = implode(" ", $sharing_pills);
                                    $sharing_ids   = implode(",", $sharing_ids);
                                }

                                $last_seen["SQL"]   = "SELECT * FROM `viewlog` WHERE `owner_id` = '{$UID}' AND `rep_id` = '{$my_reports["ROW"]["id"]}' ORDER BY `dt` DESC LIMIT 1";
                                $last_seen["QUERY"] = sql_execute($last_seen["SQL"]);
                                if (($last_seen["QUERY"] === false) || ($last_seen["QUERY"]->num_rows === 0)) {
                                    $last_seen_str = "Nunca";
                                } else {
                                    $last_seen["ROW"] = $last_seen["QUERY"]->fetch_assoc();
                                    $date             = date_create_from_format("Y-m-d H:i:s", $last_seen["ROW"]["dt"]);
                                    $date_str         = $date->format("d/m H:i");

                                    $last_seen_str = implode("", [
                                        $date_str,
                                        ($last_seen["ROW"]["ip"] !== "") ? " IP: " : "",
                                        $last_seen["ROW"]["ip"],
                                        ($last_seen["ROW"]["ip"] !== "") ? " ID: " : "",
                                        $last_seen["ROW"]["gccr_id"],
                                        ($last_seen["ROW"]["ip"] !== "") ? " - " : "",
                                        $last_seen["ROW"]["gccr_name"]
                                    ]);
                                }

                                echo /** @lang HTML */
                                "
                                <a href=\"#\" class=\"list-group-item list-group-item-action flex-column align-items-start\"
                                    db-id='{$my_reports["ROW"]["id"]}'
                                    db-name='{$my_reports["ROW"]["name"]}'
                                    db-description='{$my_reports["ROW"]["description"]}'
                                    db-shares='{$sharing_ids}'
                                    >
                                    <div class=\"d-flex w-100 justify-content-between\">
                                        <h5 class=\"mb-1\">#{$my_reports["ROW"]["id"]}: {$my_reports["ROW"]["name"]}</h5>
                                        <small>Visto por último: $last_seen_str</small>
                                    </div>
                                    <p class=\"mb-1\">{$my_reports["ROW"]["description"]}</p>
                                    <small>$sharing_pills</small>
                                </a>
                            ";
                            }
                        ?>
                    </div>

                    <hr/>

                </div>
            </div>
        </div>
        <script type="text/javascript">
            var detail_owner_id = 0;
            var detail_report_id = 0;
            var intervalFx = null;
            var updateTimeMillis = 1000;
            var uid = $("#owner").attr("db-id");
            $(".nav-link").click(function () {
                document.cookie = "CURRENT_PAGE=" + $(this).attr("aria-controls");
                document.title = "ViewLog - " + $(this).html();

                //clearInterval(intervalFx);
                switch ($(this).attr("aria-controls")) {
                    case 'v-pills-dashboard':
                        $("#v-pills-detail").removeClass("active show");
                        $("#v-pills-detail").hide();

                        var chart = window["chart_" + detail_owner_id + "_" + detail_report_id];
                        chart.write("report-" + detail_owner_id + "-" + detail_report_id);

                        var div = $("#report-" + detail_owner_id + "-" + detail_report_id);
                        div.height(div.height() - 1);
                        setTimeout(function () {
                            div.height(div.height() + 1);
                        }, 100);
                        break;
                    case 'v-pills-dashboard-sharing':
                        $("#v-pills-detail").removeClass("active show");
                        $("#v-pills-detail").hide();

                        var chart = window["shared_chart_" + detail_owner_id + "_" + detail_report_id];
                        chart.write("shared-report-" + detail_owner_id + "-" + detail_report_id);

                        var div = $("#report-" + detail_owner_id + "-" + detail_report_id);
                        div.height(div.height() - 1);
                        setTimeout(function () {
                            div.height(div.height() + 1);
                        }, 100);
                        break;
                    case 'v-pills-detail':

                        break;
                    default:

                        break;
                }
            });
            $(".list-group-item-action").click(function () {
                $(window).animate({
                    scrollTop: 0
                }, 2000);
                $(".list-group-item-action").removeClass("active");
                $(this).addClass("active");
                $("#my-report-edit-id").val($(this).attr("db-id"));
                $("#my-report-edit-name").val($(this).attr("db-name"));
                $("#my-report-edit-description").val($(this).attr("db-description"));
                $(".form-check-input").removeAttr("checked");
                $("#my-report-edit-show-modal").removeAttr("disabled");
                var shares = $(this).attr("db-shares").split(",");
                if (shares.length > 0) {
                    for (var i = 0; i < shares.length; i++) {
                        $("#usr-" + shares[i]).attr("checked", "checked");
                    }
                }
            });

            $(".detail").click(function () {
                detail_owner_id = $(this).attr("owner");
                detail_report_id = $(this).attr("report");
                document.cookie = "CURRENT_PAGE=" + $(this).attr("aria-controls");
                document.title = "ViewLog - " + $(this).html();

                $("#detail-loading").show();

                $("#title-detail").text($(this).attr("reportName"));
                var classname = $(this).attr("class");

                var chart = false;
                if ($(this).hasClass("shared")) {
                    chart = window["shared_chart_" + detail_owner_id + "_" + detail_report_id];

                    $("#v-pills-dashboard-sharing-tab").removeClass("active");
                    $("#v-pills-dashboard-sharing").removeClass("active show");
                } else {
                    chart = window["chart_" + detail_owner_id + "_" + detail_report_id];

                    $("#v-pills-dashboard-tab").removeClass("active");
                    $("#v-pills-dashboard").removeClass("active show");
                }
                chart.write("report-detail");

                $("#report-detail").height($("#report-detail").height() - 1);
                setTimeout(function () {
                    $("#report-detail").height($("#report-detail").height() + 1);
                }, 100);

                $("#v-pills-detail").show();

                $("#v-pills-detail").fadeIn(100, function () {
                    $("#v-pills-detail").addClass("active show");
                    $("#detail-loading").hide();
                    $(document).scrollTop();
                });
            });

            var toastSave = false;
            $("#my-report-edit-save").click(function () {
                toastSave = $.toast({
                    heading: 'Salvar relatório',
                    text: "Processando requisição...",
                    hideAfter: 2000,
                    icon: 'info',
                    loader: true
                });
                var request, rep_id, rep_name, rep_description, shares = [];
                rep_id = $("#my-report-edit-id").val();
                rep_name = $("#my-report-edit-name").val();
                rep_description = $("#my-report-edit-description").val() || "";
                $(".form-check-input:checked").each(function () {
                    shares.push($(this).attr("id").split("-")[1]);
                });
                $.post("_php/update_report.php", {
                    'uid': uid,
                    'rep_id': rep_id,
                    "rep_name": rep_name,
                    "rep_description": rep_description,
                    "shares": shares
                }, function (r) {
                    try {
                        var response = JSON.parse(r);
                        console.log(response);
                        toastSave.update({
                            heading: 'Salvar relatório',
                            text: response.message,
                            hideAfter: 1000,
                            icon: (parseInt(response.status) === 0) ? 'error' : 'success',
                            loader: true,
                            afterHidden: function () {
                                if (parseInt(response.status) === 1)
                                    location.reload();
                            }
                        });
                    } catch (e) {
                        toastSave.update({
                            heading: 'Erro inesperado',
                            text: r,
                            hideAfter: 10000,
                            icon: 'error',
                            loader: true
                        });
                    }
                });
            });

            $("#my-report-edit-del").click(function () {
                var toastDelete = $.toast({heading: 'Excluir relatório',
                    text: "Processando requisição...",
                    hideAfter: 3000,
                    icon: 'info',
                    loader: true
                });
                var request, rep_id;
                rep_id = $("#my-report-edit-id").val();
                $.post("_php/delete_report.php", {
                    'uid': uid, 'rep_id': rep_id
                }, function (response) {
                    try {
                        var r = JSON.parse(response);
                        toastDelete.update({
                            heading: 'Excluir relatório',
                            text: r.message,
                            hideAfter: 2000,
                            icon: (r.status === 0) ? 'error' : 'success',
                            loader: true,
                            afterHidden: function () {
                                location.reload();
                            }
                        });
                    } catch (err) {
                        toastDelete.update({
                            heading: 'Falha ao excluir relatório',
                            text: [
                                "Resposta do servidor:",
                                response,
                                "Erro javascript:",
                                err
                            ],
                            hideAfter: 10 * 1000,
                            icon: 'error'
                        });
                    }
                });
            });

            $("#search").keyup(function () {
                var search = $(this).val();
                var count = 0, hide = 0;
                $(".card").each(function () {
                    var attr = $(this).attr('search');
                    if (typeof attr !== 'undefined' && attr !== false) {
                        if (!attr.match(new RegExp(search, 'i'))) {
                            $(this).hide();
                            hide++;
                        } else {
                            $(this).show();
                            count++;
                        }
                    }
                });
                if (hide > 0) {
                    $("#search-count").text(count + " resultado" + ((count === 1) ? "" : "s"));
                } else {
                    $("#search-count").text("");
                }
            });

            $("#shared-search").keyup(function () {
                var search = $(this).val();
                var count = 0, hide = 0;
                $(".shared-card").each(function () {
                    var attr = $(this).attr('shared-search');
                    if (typeof attr !== 'undefined' && attr !== false) {
                        if (!attr.match(new RegExp(search, 'i'))) {
                            $(this).hide();
                            hide++;
                        } else {
                            $(this).show();
                            count++;
                        }
                    }
                });
                if (hide > 0) {
                    $("#shared-search-count").text(count + " resultado" + ((count === 1) ? "" : "s"));
                } else {
                    $("#shared-search-count").text("");
                }
            });
        </script>
    </body>
</html>
