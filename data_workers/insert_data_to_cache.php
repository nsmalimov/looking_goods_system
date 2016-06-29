<?php

include "settings.php";

//define('MYSQL_BOTH', MYSQLI_BOTH);
//define('MYSQL_NUM', MYSQLI_NUM);
//define('MYSQL_ASSOC', MYSQLI_ASSOC);

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
        $sql_ids = 'SELECT id,cost FROM goods FORCE INDEX (id,cost) ORDER BY ' . $col_name;
    } else {
        $sql_ids = 'SELECT id,cost FROM goods FORCE INDEX (id,cost) ORDER BY ' . $col_name . ' DESC';
    }

    echo $sql_ids . "\n";

    $result_ids = $mysqli->query($sql_ids);
    $arr_id = array();

    $count = 0;
    while ($row = $result_ids->fetch_array(MYSQLI_NUM)) {
        if ($count % 100 == 0 and $count != 0)
        {
            $memcache->set("ids_" . $type . "_" . $col_name . "_" . $count, $arr_id, false);
            unset($arr_id);
            $arr_id = array();
        }
        $arr_id[$row[0]] = $row[1];
        $count ++;
    }

    if (count($arr_id) != 0)
    {
        $memcache->set("ids_" . $type . "_" . $col_name . "_" . ceil($count/100)*100, $arr_id, false);
    }
}


function put_sorted_array_all($mysqli, $memcache)
{
    $sql_ids = 'SELECT id,cost FROM goods FORCE INDEX (id,cost) ORDER BY cost';

    $result_ids = $mysqli->query($sql_ids);
    $arr_all = array();

    while ($row = $result_ids->fetch_array(MYSQLI_NUM)) {
        $arr_all[$row[0]] = $row[1];
    }
    
    echo count($arr_all) . "\n";

    $memcache->set("all_ids", $arr_all, false);
}

function setArraysFromSql($mysqli, $memcache)
{
    $count = getCountInBase($mysqli);

    //$count = $memcache->get("count");

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

    $memcache->set("count", ceil($count / 100) * 100, false);

}

setAllDataFromSQL($mysqli, $memcache);

setArraysFromSql($mysqli, $memcache);

setOtherVars($mysqli, $memcache);

$memcache->close();

$mysqli->close();

?>