<?php

include "recovery.php";

function remove_from_chunk($memcache, $count, $id_num, $cost, $col_name, $type)
{
    $pages = ceil($count / 1000);

    $left = 0;
    $right = $pages;

    $median = ceil(($left + $right) / 2);

    if ($col_name == "id") {
        $comparison_val = intval($id_num);
    } else {
        $comparison_val = floatval($cost);
    }

    while (True) {
        $arr = $memcache->get("ids_" . $type . "_" . $col_name . "_" . $median);

        if ($col_name == "id") {
            $first = intval(array_keys($arr)[0]);
        } else {
            $first = floatval(array_values($arr)[0]);

        }

        if (array_key_exists($id_num, $arr)) {
            unset($arr[$id_num]);
            $memcache->replace("ids_" . $type . "_" . $col_name . "_" . $median, $arr);
            
            recovery_by_100_in_chunk($memcache, $median, "ids_" . $type . "_" . $col_name . "_", "delete");

            echo count($memcache->get("ids_" . $type . "_" . $col_name . "_" . $median));

            echo "delete " . $col_name . " " . $type . "\n";

            break;
        }

        if (($right - $left) == 1) {
            break;
        }


        if ($type == "sorted") {
            if ($comparison_val <= $first) {
                $right = $median;
            } else {
                $left = $median;

            }
        } else {
            if ($comparison_val <= $first) {
                $left = $median;
            } else {
                $right = $median;

            }
        }

        $median = ceil(($left + $right) / 2);
    }
}

function update_chunk_delete($memcache, $id_num)
{
    $time_start = microtime(true);

    $count = intval($memcache->get("count"));

    $elem = $memcache->get($id_num);

    $cost = floatval($elem["cost"]);

    remove_from_chunk($memcache, $count, $id_num, $cost, "id", "sorted");

    remove_from_chunk($memcache, $count, $id_num, $cost, "cost", "sorted");

    remove_from_chunk($memcache, $count, $id_num, $cost, "id", "reversed");

    remove_from_chunk($memcache, $count, $id_num, $cost, "cost", "reversed");

    $time_end = microtime(true);

    $execution_time = ($time_end - $time_start);

    echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
}

?>