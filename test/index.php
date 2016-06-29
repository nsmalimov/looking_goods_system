<?php

ini_set('memory_limit', '750M');

$a = range(1, 1000000);

$time_start = microtime(true);

$k = 500000;

//echo array_keys($a)[100] . "\n";

$r = array_search($k, $a);
//for ($i = 0; $i < count($a); $i++)
//{
//    if ($i == $k)
//    {
//        echo "111 \n";
//    }
//}

$time_end = microtime(true);

$execution_time = ($time_end - $time_start);

echo $execution_time . "\n";

?>