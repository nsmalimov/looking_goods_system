<?php

include "settings.php";

define('MYSQL_BOTH', MYSQLI_BOTH);
define('MYSQL_NUM', MYSQLI_NUM);
define('MYSQL_ASSOC', MYSQLI_ASSOC);

ini_set('memory_limit', '750M');

$mysqli = new mysqli($mysql_dbhost, $mysql_dbuser, $mysql_dbpass, $mysql_dbname);

$memcache = new Memcache;
$memcache->connect($memcache_host, $memcache_port) or exit("Could not connect to Memcached");

if ($mysqli->connect_errno) {
    printf("Cannot connect to mysql: %s\n", $mysqli->connect_error);
    exit();
}

function setAllDataFromSQL($mysqli, $memcache)
{
    $sql_all = 'SELECT * FROM goods';

    $result_all = $mysqli->query($sql_all);


    $count = 0;

    while ($row = $result_all->fetch_array(MYSQL_ASSOC)) {
        if ($count % 50000 == 0) {
            echo $count . "\n";
        }

        $to_insert = array("cost" => $row["cost"], "description" => $row["description"], "title" => $row["title"],
            "url_image" => $row["url_image"]);

        $memcache->set($row['id'], $to_insert, false);

        $count++;
    }

    $result_all->free();
}

function put_sorted_array($mysqli, $memcache, $col_name, $type, $count)
{
    if ($type == "sorted") {
        $sql_ids = 'SELECT id FROM goods FORCE INDEX (' . $col_name . ') ORDER BY ' . $col_name;
    } else {
        $sql_ids = 'SELECT id FROM goods FORCE INDEX (' . $col_name . ') ORDER BY ' . $col_name . ' DESC';
    }

    echo $sql_ids . "\n";

    $result_ids = $mysqli->query($sql_ids);
    $arr_id = array();
    while ($row = $result_ids->fetch_array(MYSQLI_NUM)) {
        array_push($arr_id, $row[0]);
    }

    $result_ids->free();

    $num = 0;

    $first = 0;
    for ($i = 100; $i <= ceil($count / 100) * 100; $i += 100) {
        if ($num % 1000 == 0 and $num != 0)
            echo $num . "\n";

        $sliced_arr = array();

        for ($j = $first; $j < $first + 100; $j++) {
            if (array_key_exists($j, $arr_id))
                if ($arr_id[$j] != null)

                    array_push($sliced_arr, $arr_id[$j]);
        }

        $memcache->set("ids_" . $type . "_" . $col_name . "_" . $i, $sliced_arr);
        $first = $i;
        $num++;
        unset($sliced_arr);
    }

    unset($arr_id);
}

function setArraysFromSql($mysqli, $memcache)
{
    $count = getCountInBase($mysqli);

    put_sorted_array($mysqli, $memcache, "id", "sorted", $count);
    echo "done" . "\n";

    put_sorted_array($mysqli, $memcache, "id", "reversed", $count);
    echo "done" . "\n";

    put_sorted_array($mysqli, $memcache, "cost", "sorted", $count);
    echo "done" . "\n";

    put_sorted_array($mysqli, $memcache, "cost", "reversed", $count);
    echo "done" . "\n";
}

function getCountInBase($mysqli)
{
    $sql_count = 'SELECT COUNT(*) FROM goods';

    $result_count = $mysqli->query($sql_count);

    $row = $result_count->fetch_array(MYSQLI_NUM);

    $result_count->free();

    return intval($row[0]);
}

function setOtherVars($mysqli, $memcache)
{
    $count = getCountInBase($mysqli);

    $memcache->set("count", ceil($count / 100) * 100);

}

setAllDataFromSQL($mysqli, $memcache);

setArraysFromSql($mysqli, $memcache);

setOtherVars($mysqli, $memcache);

$memcache->close();

$mysqli->close();

?>