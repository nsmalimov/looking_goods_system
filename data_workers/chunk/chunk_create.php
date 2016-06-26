<?php

//$memcache = new Memcache;
//$memcache->connect("localhost", 8000) or exit("Could not connect to Memcached");

// ids_sorted_id
// ids_reversed_id

// ids_sorted_cost
// ids_reversed_cost

function update_chunk_create($memcache, $id_num, $cost)
{
    $count = intval($memcache->get("count"));

    //echo $id_num . "\t" . gettype($id_num) . "\n";

    $ids_sorted_id_need = True;
    $ids_reversed_id_need = True;
    $ids_sorted_cost_need = True;
    $ids_reversed_cost_need = True;

    for ($i = 100; $i <= $count; $i += 100) {
        if ($ids_sorted_id_need) {
            $arr = $memcache->get("ids_sorted_id_" . $i);

            if ($i == $count and intval($id_num) > intval(end($arr))) {
                array_push($arr, $id_num);
                $memcache->replace("ids_sorted_id_" . $i, $arr);
                $ids_sorted_id_need = False;

            } elseif (intval($id_num) <= intval(array_values($arr)[0])) {
                array_unshift($arr, $id_num);
                $memcache->replace("ids_sorted_id_" . $i, $arr);
                $ids_sorted_id_need = False;

            } elseif (intval($id_num) > intval(end($arr))) {
                continue;

            } elseif (intval($id_num) > intval(array_values($arr)[0]) and intval($id_num) <= intval(end($arr))) {
                foreach ($arr as $key => $value) {
                    if (intval($value) >= intval($id_num)) {
                        $inserted = array($id_num);
                        array_splice($arr, $key, 0, $inserted);
                        $memcache->replace("ids_sorted_id_" . $i, $arr);
                        break;
                    }
                }
            }

            unset($arr);
        }

        if ($ids_reversed_id_need) {
            $arr = $memcache->get("ids_reversed_id_" . $i);

            if ($i == $count and intval($id_num) > intval(end($arr))) {
                array_push($arr, $id_num);
                $memcache->replace("ids_reversed_id_" . $i, $arr);
                $ids_reversed_id_need = False;

            } elseif (intval($id_num) <= intval(array_values($arr)[0])) {
                array_unshift($arr, $id_num);
                $memcache->replace("ids_reversed_id_" . $i, $arr);
                $ids_reversed_id_need = False;

            } elseif (intval($id_num) > intval(end($arr))) {
                continue;

            } elseif (intval($id_num) > intval(array_values($arr)[0]) and intval($id_num) <= intval(end($arr))) {
                foreach ($arr as $key => $value) {
                    if (intval($value) >= intval($id_num)) {
                        $inserted = array($id_num);
                        array_splice($arr, $key, 0, $inserted);
                        $memcache->replace("ids_reversed_id_" . $i, $arr);
                        break;
                    }
                }
            }

            unset($arr);
        }

        if ($ids_sorted_cost_need) {
            $arr = $memcache->get("ids_sorted_cost_" . $i);

            if ($i == $count and intval($id_num) > intval(end($arr))) {
                array_push($arr, $id_num);
                $memcache->replace("ids_sorted_cost_" . $i, $arr);
                $ids_sorted_cost_need = False;

            } elseif (intval($id_num) <= intval(array_values($arr)[0])) {
                array_unshift($arr, $id_num);
                $memcache->replace("ids_sorted_cost_" . $i, $arr);
                $ids_sorted_cost_need = False;

            } elseif (intval($id_num) > intval(end($arr))) {
                continue;

            } elseif (intval($id_num) > intval(array_values($arr)[0]) and intval($id_num) <= intval(end($arr))) {
                foreach ($arr as $key => $value) {
                    if (intval($value) >= intval($id_num)) {
                        $inserted = array($id_num);
                        array_splice($arr, $key, 0, $inserted);
                        $memcache->replace("ids_sorted_cost_" . $i, $arr);
                        break;
                    }
                }
            }

            unset($arr);
        }

        if ($ids_reversed_cost_need) {
            $arr = $memcache->get("ids_reversed_cost_" . $i);

            if ($i == $count and intval($id_num) > intval(end($arr))) {
                array_push($arr, $id_num);
                $memcache->replace("ids_reversed_cost_" . $i, $arr);
                $ids_reversed_cost_need = False;

            } elseif (intval($id_num) <= intval(array_values($arr)[0])) {
                array_unshift($arr, $id_num);
                $memcache->replace("ids_reversed_cost_" . $i, $arr);
                $ids_reversed_cost_need = False;

            } elseif (intval($id_num) > intval(end($arr))) {
                continue;

            } elseif (intval($id_num) > intval(array_values($arr)[0]) and intval($id_num) <= intval(end($arr))) {
                foreach ($arr as $key => $value) {
                    if (intval($value) >= intval($id_num)) {
                        $inserted = array($id_num);
                        array_splice($arr, $key, 0, $inserted);
                        $memcache->replace("ids_reversed_cost_" . $i, $arr);
                        break;
                    }
                }
            }

            unset($arr);
        }


        if (!$ids_reversed_cost_need and !$ids_reversed_id_need
            and !$ids_sorted_id_need and !$ids_sorted_cost_need)
        {
            echo "break" . "\n";
            break;
        }
    }
}

//update_chunk_create($memcache, "3", "1000");
//print_r($memcache->get("ids_sorted_id_100"));

//$s = array(12,13,15,20);


//$d = array(100);

//$arr_temp = $memcache->get("ids_reversed_id_" . $rev_i);
//array_splice($s, 2, 0, $d);

//foreach ($s as $key => $value)
//{
///    echo $key . "\t" . $value . "\n";
//}

//$memcache->close();

?>