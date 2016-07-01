<?php

include_once "recovery.php";
include_once "binary_search.php";

function update_chunk_create($memcache, $id_num, $cost)
{
    $time_start = microtime(true);

    $count = intval($memcache->get("count"));

    find_put_chunk($memcache, $count, $id_num, $cost, "id", "sorted", "create");

    find_put_chunk($memcache, $count, $id_num, $cost, "id", "reversed", "create");

    find_put_chunk($memcache, $count, $id_num, $cost, "cost", "sorted", "create");

    find_put_chunk($memcache, $count, $id_num, $cost, "cost", "reversed", "create");

    $time_end = microtime(true);

    $execution_time = ($time_end - $time_start);

    echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
}

?>
