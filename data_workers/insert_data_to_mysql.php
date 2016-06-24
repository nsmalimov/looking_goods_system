<?php

include "settings.php";

$data = file_get_contents("/Applications/XAMPP/xamppfiles/htdocs/looking_goods_system/data_workers/dumps/dump_goods.json");
$json_a = json_decode($data, true);

$url_image = "http://95.213.237.66/media/waffle-iron.jpg";

$conn = mysql_connect($mysql_dbhost, $mysql_dbuser);
if (!$conn) {
    die('Could not connect to mysql: ' . mysql_error());
}

mysql_select_db($mysql_dbname);

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

        mysql_query($sql, $conn);

        if ($count == 1000000)
            break;
        
        $count ++;
    }
    if ($count == 1000000)
        break;
}

mysql_close($conn);

?>