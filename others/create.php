<?php

define('MYSQL_BOTH', MYSQLI_BOTH);
define('MYSQL_NUM', MYSQLI_NUM);
define('MYSQL_ASSOC', MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    include "../data_workers/settings.php";

    include "../data_workers/chunk/chunk_create.php";

    $title = $_POST['title'];
    $description = $_POST['description'];
    $url_image = $_POST['url_image'];
    $cost = $_POST['cost'];
    $id = $_POST['id_num'];

    $mysqli = new mysqli($mysql_dbhost, $mysql_dbuser, $mysql_dbpass, $mysql_dbname);

    if ($mysqli->connect_errno) {
        printf("Cannot connect to mysql: %s\n", $mysqli->connect_error);
        exit();
    }

    $memcache = new Memcache;
    $memcache->connect($memcache_host, $memcache_port) or exit("Could not connect to Memcached");

    $sql = "INSERT INTO goods " .
        "(id,cost,title,url_image,description)" .
        "VALUES " .
        "('$id','$cost','$title','$url_image','$description')";

    $result = $mysqli->query($sql);

    if ($result) {
        if (mysqli_affected_rows($mysqli) > 0) {

            $to_insert = array("cost" => $cost, "description" => $description, "title" => $title,
                "url_image" => $url_image);

            update_chunk_create($memcache, $id, $cost);
            $memcache->set($id, $to_insert, false);
        } 
    } else {
        echo "<script>alert('id exist');</script>";
    }


    $memcache->close();
    $mysqli->close();
}

?>

<div class="row" id="createChanelModal">
    <div class="col-md-6">

        <h3>Создание</h3>
        <h3></h3>
        <h3></h3>

        <div class="form-group row">
            <div class="col-sm-12">
                <input class="form-control" id="inputIdNum" type="number" placeholder="Id">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
                <input class="form-control" id="inputTitle" type="text" placeholder="Title">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
                <textarea class="form-control" rows="3" placeholder="Description" id="inputDescription"></textarea>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
                <input class="form-control" type="text" placeholder="Image URL" id="inputImageUrl">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
                <input class="form-control" type="text" placeholder="Cost" id="inputCost">
            </div>
        </div>


        <a type="button" class="btn btn-primary" id="createChanelButton" onclick="confirmCreateFunc()">Готово</a>
    </div>
</div>