<?php

function recovery_by_100_in_chunk($memcache, $num_start, $string, $type_method)
{
    $current_arr = $memcache->get($string . $num_start);

    for ($i = ($num_start + 1); $i <= 100; $i++) {

        $arr = $memcache->get($string . $i);

        if ($type_method == "create") {
            $elem_last = end(array_keys($current_arr));
            $arr[$elem_last] = $current_arr[$elem_last];
            unset($current_arr[$elem_last]);
        } else {
            $elem_first = array_keys($arr)[0];
            $current_arr[$elem_first] = $arr[$elem_first];
            unset($arr[$elem_first]);
        }

        $memcache->set($string . ($i - 1), $current_arr);

        $current_arr = $arr;
    }
}

?>