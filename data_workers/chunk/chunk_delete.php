<?php

include_once "recovery.php";
include_once "binary_search.php";

function update_chunk_delete($memcache, $id_num)
{
    $time_start = microtime(true);

    $count = intval($memcache->get("count"));

    $elem = $memcache->get($id_num);

    $cost = floatval($elem["cost"]);

    find_put_chunk($memcache, $count, $id_num, $cost, "id", "sorted", "delete");

    find_put_chunk($memcache, $count, $id_num, $cost, "cost", "sorted", "delete");

    find_put_chunk($memcache, $count, $id_num, $cost, "id", "reversed", "delete");

    find_put_chunk($memcache, $count, $id_num, $cost, "cost", "reversed", "delete");

    $time_end = microtime(true);

    $execution_time = ($time_end - $time_start);

    echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
}

?>