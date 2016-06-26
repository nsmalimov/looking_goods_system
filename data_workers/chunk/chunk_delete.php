<?php

//$memcache = new Memcache;
//$memcache->connect("localhost", 8000) or exit("Could not connect to Memcached");

// ids_sorted_id
// ids_reversed_id

// ids_sorted_cost
// ids_reversed_cost

function update_chunk($memcache, $id_num)
{
    $count = $memcache->get("count");

    $first_need = True;
    $second_need = True;

    for ($i = 100; $i <= $count; $i += 100) {

        if ($first_need) {
            $arr1 = $memcache->get("ids_sorted_id_" . $i);
            $find_num1 = array_search($id_num, $arr1);

            if ($find_num1 != null) {
                unset($arr1[$find_num1]);
                $first_need = False;
                $memcache->replace("ids_sorted_id_" . $i, $arr1);
                
                $rev_i = $count - $i + 100;

                //echo $i . " " . $rev_i . " " . $count . "\n";
                
                $arr_temp = $memcache->get("ids_reversed_id_" . $rev_i);
                $find_num_new = array_search($id_num, $arr_temp);
                unset($arr_temp[$find_num_new]);
                
                $memcache->replace("ids_reversed_id_" . $rev_i, $arr_temp);

                unset($arr_temp);
            }

            unset($arr1);
        }

        if ($second_need) {
            $arr2 = $memcache->get("ids_sorted_cost_" . $i);
            $find_num2 = array_search($id_num, $arr2);

            if ($find_num2 != null) {
                unset($arr2[$find_num2]);
                $second_need = False;
                $memcache->replace("ids_sorted_cost_" . $i, $arr2);

                $rev_i = $count - $i + 100;

                //echo $i . " " . $rev_i . " " . $count . "\n";

                $arr_temp = $memcache->get("ids_reversed_id_" . $rev_i);
                $find_num_new = array_search($id_num, $arr_temp);
                unset($arr_temp[$find_num_new]);
                
                $memcache->replace("ids_reversed_cost_" . $rev_i, $arr_temp);

                unset($arr_temp);
            }

            unset($arr2);
        }

        if ($first_need == False and $second_need) {
            break;
        }
    }
}

//find_chunk_need_by_id($memcache, "999997");

//print_r($memcache->get("ids_sorted_id_100"));

//print_r(0 == False);
//$memcache->close();

// (1==2) == Null;

?>