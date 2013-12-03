<?php session_start();
error_reporting (E_ERROR);
$_GET['lang'] = $_SESSION['lang'];
require_once("includes/utils.php");
require_once("_includes/_xtemplate_header.php");
require_once("_includes/_data.php");
require_once("_includes/_moviePageUtils.php");
require_once("_includes/_nadakaSearch.php");

$conLink = msi_dbconnect();
printXHeader('');



echo "<table class=ptables>";
echo "<tr>";
echo "<td >";
printBrowseTables("$year","$distinction","$browseorder");
echo "</td>";
echo "</tr>";
echo "</table>";
printFancyFooters();
mysql_close($conLink);


?>









