<?php

//$memcache = new Memcache;
//$memcache->connect("localhost", 11211) or exit("Could not connect to Memcached");

// ids_sorted_id
// ids_reversed_id

// ids_sorted_cost
// ids_reversed_cost

function update_chunk_delete($memcache, $id_num)
{
    $count = intval($memcache->get("count"));
    
    $ids_sorted_id_need = True;
    $ids_reversed_id_need = True;
    $ids_sorted_cost_need = True;
    $ids_reversed_cost_need = True;

    for ($i = 100; $i <= $count; $i += 100) {
        if ($ids_sorted_id_need) {
            $arr = $memcache->get("ids_sorted_id_" . $i);
            $find_num = array_search($id_num, $arr);
            
            //echo gettype($find_num) . "\n";

            if (!($find_num === False)) {

                //echo $i . " id1 \n";
                unset($arr[$find_num]);
                $memcache->replace("ids_sorted_id_" . $i, $arr);
                $ids_sorted_id_need = False;
            }
            
            unset($arr);
        }

        if ($ids_reversed_id_need) {
            $arr = $memcache->get("ids_reversed_id_" . $i);
            $find_num = array_search($id_num, $arr);

            if (!($find_num === False)) {
                
                //echo $i . " id2 \n";
                unset($arr[$find_num]);
                $memcache->replace("ids_reversed_id_" . $i, $arr);
                $ids_reversed_id_need = False;
            }
            
            unset($arr);
        }

        if ($ids_sorted_cost_need) {
            $arr = $memcache->get("ids_sorted_cost_" . $i);
            $find_num = array_search($id_num, $arr);

            if (!($find_num === False)) {

                //echo $i . " cost1 \n";
                unset($arr[$find_num]);
                $memcache->replace("ids_sorted_cost_" . $i, $arr);
                $ids_sorted_cost_need = False;
            }
            
            unset($arr);
        }

        if ($ids_reversed_cost_need) {
            $arr = $memcache->get("ids_reversed_cost_" . $i);
            $find_num = array_search($id_num, $arr);

            if (!($find_num === False)) {

                ///echo $i . " cost2 \n";
                unset($arr[$find_num]);
                //echo $find_num . "\n";
                print_r($arr);
                $memcache->replace("ids_reversed_cost_" . $i, $arr);
                $ids_reversed_cost_need = False;
            }
            
            unset($arr);
        }
        
        if (!$ids_reversed_cost_need and !$ids_reversed_id_need 
            and !$ids_sorted_id_need and !$ids_sorted_cost_need)
        {
            //echo "break" . "\n";
            break;
        }
    }
}

//$memcache->close();

?>