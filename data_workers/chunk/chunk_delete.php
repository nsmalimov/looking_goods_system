<?php


function remove_from_chunk($memcache, $count, $id_num, $cost, $col_name, $type)
{
    $pages = ceil($count / 100);

    //$median = ceil(ceil($pages / 2) / 100)*100;

    $left = 0;
    $right = $pages;

    $median = ceil(($left + $right) / 2);

    if ($col_name == "id") {
        $comparison_val = intval($id_num);
    }
    else
    {
        $comparison_val = floatval($cost);
    }
    
    $flag_while = False;

    while (True) {
        $arr = $memcache->get("ids_" . $type . "_" . $col_name . "_" . $median);

        if ($col_name == "id" and (!$flag_while)) {
            $first = intval(array_keys($arr)[0]);
        } else {
            $first = floatval(array_values($arr)[0]);

        }

        //if ($col_name != "id") {
        //    echo $median . "\n";
        //}

        //echo ($right - $left) . "\n";

        if (array_key_exists($id_num, $arr)) {
            unset($arr[$id_num]);
            $memcache->replace("ids_" . $type . "_" . $col_name . "_" . $median, $arr);

            echo "delete " . $col_name . " " . $type . "\n";
        }
        
        if (($right - $left) == 1)
        {
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
    
    //echo "id" . " sorted" . "\n";

    remove_from_chunk($memcache, $count, $id_num, $cost, "cost", "sorted");

    //echo "cost" . " sorted" . "\n";

    remove_from_chunk($memcache, $count, $id_num, $cost, "id", "reversed");

    //echo "id" . " reversed" . "\n";

    remove_from_chunk($memcache, $count, $id_num, $cost, "cost", "reversed");

    //echo "cost" . " reversed" . "\n";

    $time_end = microtime(true);

    $execution_time = ($time_end - $time_start);

    echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
}

$memcache = new Memcache;
$memcache->connect("localhost", 11211) or exit("Could not connect to Memcached");


// if not exist?
update_chunk_delete($memcache, "59");

//print_r($memcache->get("ids_sorted_cost_290"));

$memcache->close();


?>