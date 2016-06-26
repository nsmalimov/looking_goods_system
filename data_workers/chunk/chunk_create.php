<?php

//$memcache = new Memcache;
//$memcache->connect("localhost", 8000) or exit("Could not connect to Memcached");

// ids_sorted_id
// ids_reversed_id

// ids_sorted_cost
// ids_reversed_cost

function update_chunk_create($memcache, $id_num, $cost)
{
    $count = $memcache->get("count");

    $first_need = True;
    $second_need = True;

    for ($i = 100; $i <= $count; $i += 100) {
        if ($first_need) {
            $arr1 = $memcache->get("ids_sorted_id_" . $i);

            if ($i == $count and $id_num > end($arr1)) {

                array_push($arr1, $id_num);
                $memcache->replace("ids_sorted_id_" . $i, $arr1);


                $arr_temp = $memcache->get("ids_reversed_id_" . 100);
                array_unshift($arr_temp, $id_num);

                $memcache->replace("ids_reversed_id_" . 100, $arr_temp);

                $first_need = False;

                unset($arr_temp);
            } elseif ($id_num <= array_values($arr1)[0]) {
                array_unshift($arr1, $id_num);
                $memcache->replace("ids_sorted_id_" . $i, $arr1);


                $rev_i = $count - $i + 100;

                $arr_temp = $memcache->get("ids_reversed_id_" . $rev_i);
                array_push($arr_temp, $id_num);

                $memcache->replace("ids_reversed_id_" . $rev_i, $arr_temp);

                $first_need = False;

                unset($arr_temp);
            } elseif ($id_num > end($arr1)) {
                continue;
            } elseif ($id_num > array_values($arr1)[0] and $id_num <= end($arr1)) {
                foreach ($arr1 as $key => $value) {
                    if ($value >= $id_num) {
                        $inserted = array($id_num);
                        array_splice($arr1, $key, 0, $inserted);
                        $memcache->replace("ids_sorted_id_" . $i, $arr1);


                        $rev_i = $count - $i + 100;

                        $arr_temp = $memcache->get("ids_reversed_id_" . $rev_i);
                        array_splice($arr_temp, count($arr_temp) - $key + 1, 0, $inserted);

                        $memcache->replace("ids_reversed_id_" . $rev_i, $arr_temp);

                        $first_need = False;

                        break;
                    }
                }
            }

            unset($arr1);
        }

        if ($second_need) {
            $arr2 = $memcache->get("ids_sorted_cost_" . $i);

            if ($i == $count and $cost > $memcache->get(end($arr2))['cost']) {
                array_push($arr2, $id_num);
                $memcache->replace("ids_sorted_cost_" . $i, $arr2);

                $arr_temp = $memcache->get("ids_reversed_cost_" . 100);
                array_unshift($arr_temp, $id_num);

                $memcache->replace("ids_reversed_cost_" . 100, $arr_temp);

                $second_need = False;

                unset($arr_temp);

            } elseif ($cost <= $memcache->get(array_values($arr2)[0])['cost']) {
                array_unshift($arr2, $id_num);
                $memcache->replace("ids_sorted_cost_" . $i, $arr2);

                $rev_i = $count - $i + 100;

                $arr_temp = $memcache->get("ids_reversed_cost_" . $rev_i);
                array_push($arr_temp, $id_num);

                $memcache->replace("ids_reversed_cost_" . $rev_i, $arr_temp);

                $second_need = False;

                unset($arr_temp);
            } elseif ($cost > $memcache->get(end($arr2))['cost']) {
                continue;
            } elseif ($cost > $memcache->get(array_values($arr2)[0])['cost']
                and $cost <= $memcache->get(end($arr2))['cost']
            ) {
                foreach ($arr2 as $key => $value) {
                    if ($memcache->get($value)['cost'] >= $cost) {
                        $inserted = array($id_num);
                        array_splice($arr2, $key, 0, $inserted);
                        $memcache->replace("ids_sorted_cost_" . $i, $arr2);


                        $rev_i = $count - $i + 100;

                        $arr_temp = $memcache->get("ids_reversed_cost_" . $rev_i);

                        array_splice($arr_temp, count($arr_temp) - $key + 1, 0, $inserted);

                        $memcache->replace("ids_reversed_cost_" . $rev_i, $arr_temp);

                        $second_need = False;

                        break;
                    }
                }
            }

            unset($arr2);
        }

        if ($first_need == False and $second_need == False) {
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