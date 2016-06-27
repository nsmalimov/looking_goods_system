<?php

function find_put_chunk($memcache, $count, $i, $id_num, $cost, $sort_type, $col_name)
{
    $arr = $memcache->get("ids_" . $sort_type . "_" . $col_name . "_" . $i);
    
    if ($col_name == "cost")
    {
        $first_elem = floatval($memcache->get(array_values($arr)[0])['cost']);
        $id_num_elem = floatval($cost);
        $elem_last = floatval($memcache->get(end($arr))['cost']);
    }
    else
    {
        $first_elem = intval(array_values($arr)[0]);
        $id_num_elem = intval($id_num);
        $elem_last = intval(end($arr));
    }

    if ($sort_type == "sorted") {
        if ($id_num_elem >= $first_elem and $id_num_elem <= $elem_last) {

            foreach ($arr as $key => $value) {
                if ($col_name == "cost")
                    $val = floatval($value);
                else
                    $val = intval($value);

                if ($val >= $id_num_elem) {
                    $inserted = array($id_num);
                    array_splice($arr, $key, 0, $inserted);

                    ksort($arr);

                    $memcache->replace("ids_" . $sort_type . "_" . $col_name . "_" . $i, $arr);
                    unset($arr);
                    return False;
                }
            }
        }

        if ($i == 100 and $id_num_elem < $first_elem) {
            array_unshift($arr, $id_num);
            $memcache->replace("ids_" . $sort_type . "_" . $col_name . "_" . $i, $arr);
            unset($arr);
            return False;
        }

        if ($i == $count and $id_num_elem > $elem_last) {
            array_push($arr, $id_num);
            $memcache->replace("ids_" . $sort_type . "_" . $col_name . "_" . $i, $arr);
            unset($arr);
            return False;
        }
    }
    else{
        if ($id_num_elem <= $first_elem and $id_num_elem >= $elem_last) {

            foreach ($arr as $key => $value) {
                if ($col_name == "cost")
                    $val = floatval($value);
                else
                    $val = intval($value);

                if ($val <= $id_num_elem) {
                    $inserted = array($id_num);
                    array_splice($arr, $key, 0, $inserted);

                    ksort($arr);

                    $memcache->replace("ids_" . $sort_type . "_" . $col_name . "_" . $i, $arr);
                    unset($arr);
                    return False;
                }
            }
        }

        if ($i === 100 and $id_num_elem > $first_elem) {
            array_unshift($arr, $id_num);
            $memcache->replace("ids_" . $sort_type . "_" . $col_name . "_" . $i, $arr);
            unset($arr);
            return False;
        }

        if ($i === $count and $id_num_elem < $elem_last) {
            array_push($arr, $id_num);
            $memcache->replace("ids_" . $sort_type . "_" . $col_name . "_" . $i, $arr);
            unset($arr);
            return False;
        }
    }

    unset($arr);
    return True;
}

function update_chunk_create($memcache, $id_num, $cost)
{
    $count = intval($memcache->get("count"));

    $ids_sorted_id_need = True;
    $ids_reversed_id_need = True;
    $ids_sorted_cost_need = True;
    $ids_reversed_cost_need = True;

    for ($i = 100; $i <= $count; $i += 100) {

        if ($ids_sorted_id_need) {
            $ids_sorted_id_need = find_put_chunk($memcache, $count, $i, $id_num, $cost, "sorted", "id");
        }

        if ($ids_reversed_id_need) {
            $ids_reversed_id_need = find_put_chunk($memcache, $count, $i, $id_num, $cost, "reversed", "id");
        }

        if ($ids_sorted_cost_need) {
            $ids_sorted_cost_need = find_put_chunk($memcache, $count, $i, $id_num, $cost, "sorted", "cost");
        }

        if ($ids_reversed_cost_need) {
            $ids_reversed_cost_need = find_put_chunk($memcache, $count, $i, $id_num, $cost, "reversed", "cost");
        }

        if (!$ids_reversed_id_need and !$ids_reversed_cost_need 
            and !$ids_sorted_cost_need and !$ids_sorted_id_need) 
        {
            break;
        }
    }
}

?>