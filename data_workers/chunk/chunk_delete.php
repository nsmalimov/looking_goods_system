<?php

function update_chunk_delete($memcache, $id_num)
{
    $time_start = microtime(true);

    $count = intval($memcache->get("count"));

    $ids_sorted_id_need = True;
    $ids_reversed_id_need = True;
    $ids_sorted_cost_need = True;
    $ids_reversed_cost_need = True;

    for ($i = 100; $i <= $count; $i += 100) {
        if ($ids_sorted_id_need) {
            $arr = $memcache->get("ids_sorted_id_" . $i);

            if (array_key_exists($id_num, $arr)) {
                unset($arr[$id_num]);
                $memcache->replace("ids_sorted_id_" . $i, $arr);

                $ids_sorted_id_need = False;
            }

            unset($arr);
        }

        if ($ids_reversed_id_need) {
            $arr = $memcache->get("ids_reversed_id_" . $i);

            if (array_key_exists($id_num, $arr)) {
                unset($arr[$id_num]);
                $memcache->replace("ids_reversed_id_" . $i, $arr);

                $ids_reversed_id_need = False;
            }

            unset($arr);
        }

        if ($ids_sorted_cost_need) {
            $arr = $memcache->get("ids_sorted_cost_" . $i);

            if (array_key_exists($id_num, $arr)) {
                unset($arr[$id_num]);
                $memcache->replace("ids_sorted_cost_" . $i, $arr);

                $ids_sorted_cost_need = False;
            }

            unset($arr);
        }

        if ($ids_reversed_cost_need) {
            $arr = $memcache->get("ids_reversed_cost_" . $i);

            if (array_key_exists($id_num, $arr)) {
                unset($arr[$id_num]);
                $memcache->replace("ids_reversed_cost_" . $i, $arr);

                $ids_reversed_cost_need = False;
            }

            unset($arr);
        }

        if (!$ids_reversed_cost_need and !$ids_reversed_id_need
            and !$ids_sorted_id_need and !$ids_sorted_cost_need)
        {
            echo "break";
            break;
        }
    }

    $time_end = microtime(true);

    $execution_time = ($time_end - $time_start);

    echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';
}

?>