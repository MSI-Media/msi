<?php
require_once("_includes/_data.php");
$qs = $_SERVER['QUERY_STRING'];
echo "<script>location.replace(\"$_Master_movielist_script?$qs\");</script>";
?>