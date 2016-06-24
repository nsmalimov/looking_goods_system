<?php

include "settings.php";

$conn = mysql_connect($mysql_dbhost, $mysql_dbuser);

if(! $conn )
{
    die('Could not connect to mysql: ' . mysql_error());
}

$sql = 'SELECT * FROM goods';

mysql_select_db($mysql_dbname);

$retval = mysql_query( $sql, $conn );

if(! $retval )
{
    die('Could not get data: ' . mysql_error());
}

$memcache = new Memcache;
$memcache->connect($memcache_host, $memcache_port) or exit("Could not connect to Memcached");

$count = 0;

while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
{
    if ($count % 50000 == 0)
    {
        echo $count;
    }

    $memcache->set($row['id'], $row, false);

    $count ++;
}
//
//$last = 0;
//
//for ($i=1;$i<=100;$i++) {
//
//    //echo $i;
//    //echo " ";
//
//    $new = $i * 10000;
//
//    echo $last . " " . $new . "\n";
//

//
//    $ids_ids = array();
//    $ids_cost = array();
//
//    $retval_ids = mysql_query($sql_ids, $conn);
//
//    while ($row = mysql_fetch_array($retval_ids, MYSQL_ASSOC)) {
//        $ids_ids[] = $row;
//    }
//
//    $retval_costs = mysql_query($sql_costs, $conn);
//
//    while ($row = mysql_fetch_array($retval_costs, MYSQL_ASSOC)) {
//        $ids_cost[] = $row;
//    }
//
//    mysql_free_result($retval_ids);
//    mysql_free_result($retval_costs);
//
//    $ids_ids = null;
//    $ids_cost = null;
//
//    $retval_ids = null;
//    $retval_costs = null;
//
//    $memcache->set('sorted_ids_id_' . $i, $ids_ids, false, 10);
//    $memcache->set('sorted_ids_cost_' . $i, $ids_cost, false, 10);
//
//    $last = $i * 10000;
//}

$memcache->close();
mysql_close($conn);

echo "end";


?>