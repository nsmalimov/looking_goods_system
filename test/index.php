<?php

ini_set('memory_limit', '750M');

$a = range(1, 1000000);

$time_start = microtime(true);

$obj = new SplMinHeap();

//$r = array_search($k, $a);
//for ($i = 0; $i < count($a); $i++)
//{
//    $obj->insert($i);
//}


$time_end = microtime(true);

$execution_time = ($time_end - $time_start);

echo $execution_time . "\n";

?>