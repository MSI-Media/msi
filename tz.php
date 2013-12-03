<?php


date_default_timezone_set('America/Los_Angeles');
//echo date_default_timezone_get() . ' => ' . date('e') . ' => ' . date('T');
    $str = time();
    $today = date("d/m/y", $str);
echo $today, "<BR>";
date_default_timezone_set('Asia/Calcutta');
    $today = date("d/m/y", $str);
echo $today;
?>