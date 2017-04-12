<?php

// open the file in a binary mode
$graph = $_GET['graphs'];
$fp = fopen("$graph", 'rb');

// send the right headers
header("Content-Type: image/png");
header("Content-Length: " . filesize($graph));

// dump the picture and stop the script
fpassthru($fp);
unlink ("$graph");

?>

