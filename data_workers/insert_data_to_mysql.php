<?php

include "settings.php";

$data = file_get_contents("/data_workers/dumps/dump_goods.json");
$json_a = json_decode($data, true);

$url_image = "http://95.213.237.66/media/waffle-iron.jpg";

$mysqli = new mysqli($mysql_dbhost, $mysql_dbuser, $mysql_dbpass, $mysql_dbname);

if ($mysqli->connect_errno) {
    printf("Cannot connect to mysql: %s\n", $mysqli->connect_error);
    exit();
}

$count = 0;

while ($count != 1000000) {
    for ($i = 0; $i < count($json_a); $i++) {

        if ($count % 50000 == 0)
            echo $count . "\n";

        $title = $json_a[$i]['title'][0];
        $cost = $json_a[$i]['cost'];
        $description = $json_a[$i]['description'];

        $sql = "INSERT INTO goods " .
            "(id,cost,title,url_image,description)" .
            "VALUES " .
            "('$count', '$cost','$title','$url_image','$description')";

        $mysqli->query($sql);

        if ($count == 1000000)
            break;

        $count++;
    }

    if ($count == 1000000)
        break;
}
$mysqli->close();

?>