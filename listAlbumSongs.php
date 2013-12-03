<?php
require_once("_includes/_data.php");
$qs = $_SERVER['QUERY_STRING'];
echo "<script>location.replace(\"$_Master_albumlist_script?$qs\");</script>";
?>