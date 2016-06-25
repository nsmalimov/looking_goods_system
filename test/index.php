<?php
include "settings.php";

ini_set('memory_limit','400M');

$mysqli = new mysqli($mysql_dbhost, $mysql_dbuser, "", $mysql_dbname);

if ($mysqli->connect_errno) {
    printf("Cannot connect to mysql: %s\n", $mysqli->connect_error);
    exit();
}

$sql_all = 'SELECT * FROM goods LIMIT 300000';

$result_all = $mysqli->query($sql_all);

//$count = 0;
//
//$arr_id = array();
//
//while($row = $result_all->fetch_array(MYSQL_ASSOC))
//{
//
//    array_push($arr_id, $row);
//}

?>