<?php

ini_set('memory_limit', '750M');

$a = range(1, 1000000);

$time_start = microtime(true);

$obj = new SplMinHeap();

$time_end = microtime(true);

$execution_time = ($time_end - $time_start);

echo $execution_time . "\n";

?>