<?php

    $UID = $UNAME = "";
    if(!isset($_COOKIE["VIEWLOG_LOGIN"])){
        header("location: login.php");
    } else {
        $COOKIE = json_decode($_COOKIE["VIEWLOG_LOGIN"], true);
        $UID   = $COOKIE["uid"];
        $UNAME = $COOKIE["name"];
    }

    if(isset($_COOKIE["CURRENT_PAGE"])){
        $CURRENT_PAGE = $_COOKIE["CURRENT_PAGE"];
    } else {
        $CURRENT_PAGE = "v-pills-dashboard";
    }
    
    $inc_o = ["default"];
    require "_php/include.php";

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
        <script type="text/javascript" src="_js/Chart.bundle.min.js"></script>
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

        <title>ViewLog</title>
        
        <!-- Bootstrap core CSS -->
        <link href="_css/jquery.toast.min.css" rel="stylesheet" />
        <link href="_css/bootstrap.min.css" rel="stylesheet" />
        <link href="_css/bootstrap-select.min.css" rel="stylesheet" />
        <link href="_css/app.css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <script src="_js/respond.min.js"></script>
        <script src="_js/jquery.min.js"></script>
        <script src="_js/popper.min.js"></script>
        <script src="_js/jquery.toast.min.js"></script>
        <!--<script src="_js/jquery.cookie.js"></script>--> <!-- Não funciona, JS puro é melhor -->
        <script src="_js/bootstrap.min.js"></script>
        <script src="_js/bootstrap-select.min.js"></script>
    </head>
    
    <body>
        <div class="row">
            <div class="nav flex-column nav-pills col-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <div id="owner" db-id="<?=$UID?>">
                    <?=$UNAME?>
                </div>
                <a class="nav-link <?=($CURRENT_PAGE == "v-pills-dashboard"? "active" : "")?>"
                   id="v-pills-dashboard-tab"
                   data-toggle="pill"
                   href="#v-pills-dashboard"
                   role="tab"
                   aria-controls="v-pills-dashboard"
                   aria-selected="true">
                    Dashboard
                </a>
                <a class="nav-link <?=($CURRENT_PAGE == "v-pills-profile"? "active" : "")?>"
                   id="v-pills-profile-tab"
                   data-toggle="pill"
                   href="#v-pills-profile"
                   role="tab"
                   aria-controls="v-pills-profile"
                   aria-selected="false">
                    Perfil
                </a>
                <a class="nav-link <?=($CURRENT_PAGE == "v-pills-reports"? "active" : "")?>"
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
                <div class="tab-pane fade <?=($CURRENT_PAGE == "v-pills-dashboard"? "active show" : "")?>" id="v-pills-dashboard" role="tabpanel" aria-labelledby="v-pills-dashboard-tab">
                
                    <h1>Dashboard</h1>
                    <h2>Painel de acompanhamento de visualizações <i>realtime</i></h2>
                    <hr />

                    <div class="card">
                        <div class="card-header">
                            Featured
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Recentemente Visualizados</h5>
                            <a href="#" class="btn btn-primary">Detalhes</a>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            Featured
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Relatório de Volumetria</h5>
                            <p class="card-text">Descrição do Relatório</p>
                            <a href="#" class="btn btn-primary">Detalhes</a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            Featured
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Relatório de Volumetria</h5>
                            <p class="card-text">Descrição do Relatório</p>
                            <a href="#" class="btn btn-primary">Detalhes</a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            Featured
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Relatório de Volumetria</h5>
                            <p class="card-text">Descrição do Relatório</p>
                            <a href="#" class="btn btn-primary">Detalhes</a>
                        </div>
                    </div>
                    
                </div>
                <div class="tab-pane fade <?=($CURRENT_PAGE == "v-pills-profile"? "active show" : "")?>"
                     id="v-pills-profile"
                     role="tabpanel"
                     aria-labelledby="v-pills-profile-tab">

                    <h1>Perfil</h1>
                    <h2>Atualize suas informações pessoais</h2>
                    <hr />
                    <?php
    
                    if(isset($_COOKIE["VIEWLOG_UPDATE_PROFILE_ERROR"])){
                        echo /** @lang html*/ "
                            <div class=\"alert alert-danger\" role=\"alert\">
                                {$_COOKIE["VIEWLOG_UPDATE_PROFILE_ERROR"]}
                            </div>
                        ";
                    }
    
                    ?>
                    <form action="_php/update_profile.php" method="post">
                        <div class="row">
                            <div class="col">
                                <input class="form-control" name="new_name" placeholder="Nome" value="<?=$UNAME?>"/>
                            </div>
                            <div class="col">
                                <input type="password" name="new_pwd" class="form-control" placeholder="Nova senha">
                            </div>
                            <div class="col">
                                <input type="password" name="new_pwd_confirm" class="form-control" placeholder="Confirmar senha">
                            </div>
                            <div class="col">
                                <button class="btn btn-primary">Salvar</button>
                            </div>
                        </div>
                    </form>
                    
                </div>
                <div class="tab-pane fade <?=($CURRENT_PAGE == "v-pills-reports"? "active show" : "")?>"
                     id="v-pills-reports"
                     role="tabpanel"
                     aria-labelledby="v-pills-reports-tab">

                    <h1>Meus relatórios</h1>
                    <h2>Adicione, edite, remova ou compartilhe seus relatórios</h2>
                    <hr />
                    <div id="edit-my-report">
                        <input type="hidden" id="my-report-edit-id">
                        <div class="form-group">
                            <input class="form-control" placeholder="Nome do Relatório" id="my-report-edit-name">
                        </div>
                        <div class="form-group">
                            <label for="my-report-edit-description">Descrição</label>
                            <textarea class="form-control" id="my-report-edit-description" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <?php
                                
                                    $users["SQL"] = "SELECT `uid`, `name` FROM `usr`";
                                    $users["QUERY"] = sql_execute($users["SQL"]);
                                    while($users["ROW"] = $users["QUERY"]->fetch_assoc()){
                                        echo /** @lang HTML */ "
                                            <div class='users'>
                                                <input class=\"form-check-input\" type=\"checkbox\" value=\"\" id=\"usr-{$users["ROW"]["uid"]}\">
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
                        </div>
                    </div>
                    <hr />
                    <div class="list-group">
                    <?php
                        $my_reports["SQL"] = "SELECT `id`, `name`, `description` FROM `report` WHERE `owner` = '$UID'";
                        $my_reports["QUERY"] = sql_execute($my_reports["SQL"]);

                        while($my_reports["ROW"] = $my_reports["QUERY"]->fetch_assoc()){
                            $sharing["SQL"] = "SELECT `usr_id`, `usr`.`name` FROM `share` INNER JOIN `usr` ON `usr`.`uid` = `usr_id` WHERE `owner_id` = '$UID' and `report_id` = '{$my_reports["ROW"]["id"]}'";
                            $sharing["QUERY"] = sql_execute($sharing["SQL"]);
                            $sharing_ids = "";
                            if(($sharing["QUERY"] === false) || ($sharing["QUERY"]->num_rows === 0)){
                                $sharing_pills = "<span class=\"badge badge-secondary\">Sem compatilhamentos</span>";
                            } else {
                                $sharing_pills = $sharing_ids = [];
                                while ($sharing["ROW"] = $sharing["QUERY"]->fetch_assoc()) {
                                    $sharing_pills[] = "<span class=\"badge badge-primary\">{$sharing["ROW"]["name"]}</span>";
                                    $sharing_ids[] = $my_reports["ROW"]["id"];
                                }
                                $sharing_pills = implode("", $sharing_pills);
                                $sharing_ids = implode(",", $sharing_ids);
                            }
                            
                            $last_seen["SQL"] = "SELECT * FROM `viewlog` WHERE `owner_id` = '{$UID}' AND `rep_id` = '{$my_reports["ROW"]["id"]}' ORDER BY `dt` DESC LIMIT 1";
                            $last_seen["QUERY"] = sql_execute($last_seen["SQL"]);
                            if(($last_seen["QUERY"] === false) || ($last_seen["QUERY"]->num_rows === 0)){
                                $last_seen_str = "Nunca";
                            } else {
                                $last_seen["ROW"] = $last_seen["QUERY"]->fetch_assoc();
                                $date = date_create_from_format("Y-m-d H:i:s", $last_seen["ROW"]["dt"]);
                                $date_str = $date->format("d/m H:i");
                                
                                $last_seen_str = implode("", [
                                    $date_str,
                                    ($last_seen["ROW"]["ip"] !== "")? " IP: " : "",
                                    $last_seen["ROW"]["ip"],
                                    ($last_seen["ROW"]["ip"] !== "")? " ID: " : "",
                                    $last_seen["ROW"]["gccr_id"],
                                    ($last_seen["ROW"]["ip"] !== "")? " - " : "",
                                    $last_seen["ROW"]["gccr_name"]
                                ]);
                            }
                            
                            echo /** @lang HTML */ "
                                <a href=\"#\" class=\"list-group-item list-group-item-action flex-column align-items-start\"
                                    db-id='{$my_reports["ROW"]["id"]}'
                                    db-name='{$my_reports["ROW"]["name"]}'
                                    db-description='{$my_reports["ROW"]["description"]}'
                                    db-shares='{$sharing_ids}'
                                    >
                                    <div class=\"d-flex w-100 justify-content-between\">
                                        <h5 class=\"mb-1\">{$my_reports["ROW"]["name"]}</h5>
                                        <small>Visto por último: $last_seen_str</small>
                                    </div>
                                    <p class=\"mb-1\">{$my_reports["ROW"]["description"]}</p>
                                    <small>$sharing_pills</small>
                                </a>
                            ";
                        }
                    
                    ?>
                    </div>
                    
                    <hr />
                
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(".nav-link").click(function(){
                document.cookie = "CURRENT_PAGE=" + $(this).attr("aria-controls");
            });

            $(".list-group-item-action").click(function(){
                $(".list-group-item-action").removeClass("active");
                $(this).addClass("active");
                
                $("#my-report-edit-id").val($(this).attr("db-id"));
                $("#my-report-edit-name").val($(this).attr("db-name"));
                $("#my-report-edit-description").val($(this).attr("db-description"));
                
                $(".form-check-input").removeAttr("checked");
                
                var shares = $(this).attr("db-shares").split(",");
                if(shares.length > 0){
                    for (var i = 0; i < shares.length; i++){
                        $("usr" + shares[i]).attr("checked", "checked");
                    }
                }
                
            });
            
            $("#my-report-edit-save").click(function(){
                var request, rep_id, uid, rep_name, rep_description, shares = [];
                uid = $("#owner").attr("db-id");
                rep_id = $("#my-report-edit-id").val();
                rep_name = $("#my-report-edit-name").val();
                rep_description = $("#my-report-edit-description").val() || "";
                
                $(".form-check-input:checked").each(function(){
                    shares.push($(this).attr("id").split("-")[1]);
                });

                $.post("_php/update_report.php", {
                    'uid': uid,
                    'rep_id': rep_id,
                    "rep_name": rep_name,
                    "rep_description": rep_description,
                    "shares": shares
                }, function(response){
                    console.log(response);
                    $.toast({
                        heading: 'Salvar Relatório',
                        text: response.message,
                        hideAfter: 1000,
                        icon: (response.status === 0)? 'error' : 'success',
                        loader: true
                    })
                });
                
            });
        </script>
    </body>
</html>
