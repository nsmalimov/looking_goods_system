<?php


function remove_from_chunk($memcache, $count, $id_num, $cost, $col_name)
{
    $pages =  ceil($count / 100);

    //$median = ceil(ceil($pages / 2) / 100)*100;

    $left = 0;
    $right = $pages;

    $median = ceil(($left + $right) / 2);

    while ($right - $left >= 1)
    {
        $arr = $memcache->get("ids_sorted_" . $col_name . "_" . $median);

        if ($col_name == "id")
        {
            $first = intval(array_keys($arr)[0]);
        }
        else{
            $first = floatval(array_values($arr)[0]);
        }

        if ($col_name != "id") {
            echo $median . "\n";
        }



        if (array_key_exists($id_num, $arr)) {
            unset($arr[$id_num]);
            $memcache->replace("ids_sorted_" . $col_name . "_" . $median, $arr);

            echo "delete " . $col_name . "\n";

            break;
        }


        
        //if ($median == 100)
        //{
        //    break;
        //}

        if ($col_name == "id") {
            if (intval($id_num) < $first) {
                $right = $median;
            } else {
                $left = $median;

            }
        }
        else
        {
            if (floatval($cost) <= $first) {
                $right = $median;
            } else {
                $left = $median;

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

    remove_from_chunk($memcache, $count, $id_num, $cost, "id");

    remove_from_chunk($memcache, $count, $id_num, $cost, "cost");

    $time_end = microtime(true);

    $execution_time = ($time_end - $time_start);

    echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';
}

//$memcache = new Memcache;
//$memcache->connect("localhost", 11211) or exit("Could not connect to Memcached");


// if not exist?
//update_chunk_delete($memcache, "54");

//print_r($memcache->get("ids_sorted_id_1"));

//$memcache->close();


?>