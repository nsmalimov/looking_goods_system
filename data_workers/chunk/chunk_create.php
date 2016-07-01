<?php

include "recovery.php";

function find_put_chunk($memcache, $count, $id_num, $cost, $col_name, $type)
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
            $last = intval(end(array_keys($arr)));
        } else {
            $first = floatval(array_values($arr)[0]);
            $last = floatval(end($arr));
        }

        if ((($comparison_val >= $first and $comparison_val <= $last) and $type == "sorted")
            or (($comparison_val <= $first and $comparison_val >= $last) and $type == "reversed")

            or (($comparison_val <= $first and $median == 1) and $type == "sorted")
            or (($comparison_val >= $first and $median == $pages) and $type == "sorted")

            or (($comparison_val >= $last and $median == 1) and $type == "reversed")
            or (($comparison_val <= $last and $median == $pages) and $type == "reversed")
        ) {

            $arr[$id_num] = $cost;

            if ($col_name == "id" and $type == "sorted") {
                ksort($arr);
            } elseif ($col_name == "id" and $type == "reversed") {
                krsort($arr);
            } elseif ($col_name == "cost" and $type == "sorted") {
                asort($arr);
            } elseif ($col_name == "cost" and $type == "reversed") {
                arsort($arr);
            }

            $memcache->replace("ids_" . $type . "_" . $col_name . "_" . $median, $arr);

            recovery_by_100_in_chunk($memcache, $median, "ids_" . $type . "_" . $col_name . "_", "create");

            echo "insert " . $col_name . " " . $type . "\n";

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

function update_chunk_create($memcache, $id_num, $cost)
{
    $time_start = microtime(true);

    $count = intval($memcache->get("count"));

    find_put_chunk($memcache, $count, $id_num, $cost, "id", "sorted");

    find_put_chunk($memcache, $count, $id_num, $cost, "id", "reversed");

    find_put_chunk($memcache, $count, $id_num, $cost, "cost", "sorted");

    find_put_chunk($memcache, $count, $id_num, $cost, "cost", "reversed");

    $time_end = microtime(true);

    $execution_time = ($time_end - $time_start);

    echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
}

//$memcache = new Memcache;
//$memcache->connect("localhost", 11211) or exit("Could not connect to Memcached");
//
//
//$time_start = microtime(true);
//
//
//
//
//$time_end = microtime(true);
//
//$execution_time = ($time_end - $time_start);
//
//echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';

// if not exist?
//update_chunk_create($memcache, "3", "1200");

//print_r($memcache->get("ids_sorted_id_1"));

//$arr = array("12"=>"3", "1456"=>"5", "433"=>"8", "454"=>"16", "87"=>"22", "99"=>"56");

//$arr = binary_search_insert_small($arr, "456", "id");

//array_splice($arr, 5, 0, "99000");

//print_r($array_1);

//$memcache->close();

?>
