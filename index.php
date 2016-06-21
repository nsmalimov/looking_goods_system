<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="http://localhost/dashboard/resources/js/main_page.js"></script>
    <link rel="stylesheet" href="http://localhost/dashboard/resources/css/main_page.css">

    <script type="text/javascript"
            src="http://localhost/dashboard/resources/bower_components/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript"
            src="http://localhost/dashboard/resources/bower_components/moment/min/moment.min.js"></script>
    <script type="text/javascript"
            src="http://localhost/dashboard/resources/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript"
            src="http://localhost/dashboard/resources/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <link rel="stylesheet"
          href="http://localhost/dashboard/resources/bower_components/bootstrap/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet"
          href="http://localhost/dashboard/resources/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css"/>

</head>

<body>
<div class="cover">
    <div class="container">
        <div class="collapse navbar-collapse" id="navbar-ex-collapse">
        </div>
    </div>
    <div class="container" style="margin-top: 50px;">

        <div class="row" id="chanelsList">
            <div class="col-md-12">
                <a type="button" style="margin-bottom: 40px; margin-right: 30px" class="btn btn-primary" id="openCreateChanelFieldButton"
                   onclick="createChanelFieldOpen()">Создать</a>
                <a type="button" style="margin-bottom: 40px; margin-right: 30px" class="btn btn-primary" id="openCreateChanelFieldButton"
                   onclick="createChanelFieldOpen()">Удалить</a>
                <a type="button" style="margin-bottom: 40px; margin-right: 30px" class="btn btn-primary" id="openCreateChanelFieldButton"
                   onclick="createChanelFieldOpen()">Список</a>
                <a type="button" style="margin-bottom: 40px; margin-right: 30px" class="btn btn-primary" id="openCreateChanelFieldButton"
                   onclick="createChanelFieldOpen()">Редактировать</a>
                <?php
                include 'main_loader.php';
                ?>
            </div>
        </div>
    </div>
</div>

</body>

</html>