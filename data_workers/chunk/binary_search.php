<?php

function create_clauses($memcache, $arr, $comparison_val, $first, $last, $type, $median,
                        $pages, $cost, $id_num, $col_name, $right, $left)
{
    if ((($comparison_val >= $first and $comparison_val <= $last) and $type == "sorted")
        or (($comparison_val <= $first and $comparison_val >= $last) and $type == "reversed")

        or (($comparison_val <= $first and $median == 1) and $type == "sorted")
        or (($comparison_val >= $last and $median == $pages) and $type == "sorted")

        or (($comparison_val >= $first and $median == 1) and $type == "reversed")
        or (($comparison_val <= $last and $median == $pages) and $type == "reversed")

        or (($right - $left) == 1)
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

        return True;
    }

    return False;
}

function remove_clauses($memcache, $arr, $id_num, $type, $col_name, $median)
{
    if (array_key_exists($id_num, $arr)) {
        unset($arr[$id_num]);
        $memcache->replace("ids_" . $type . "_" . $col_name . "_" . $median, $arr);

        recovery_by_100_in_chunk($memcache, $median, "ids_" . $type . "_" . $col_name . "_", "delete");

        return True;
    }

    return False;
}

function find_put_chunk($memcache, $count, $id_num, $cost, $col_name, $type, $type_work)
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

        if ($type_work == "create") {
            $result = create_clauses($memcache, $arr, $comparison_val, $first, $last, $type, $median,
                $pages, $cost, $id_num, $col_name, $right, $left);

            if ($result) {
                break;
            }
        } else {
            $result = remove_clauses($memcache, $arr, $id_num, $type, $col_name, $median);

            if ($result) {
                break;
            }
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

?>