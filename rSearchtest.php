<?php session_start();
{
    error_reporting (E_ERROR);
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("includes/utils.php");

    $cLink = msi_dbconnect();
    mysql_query("SET NAMES utf8");
    $search = $_GET['search'];
    $n = array();
    $n = buildArrayFromQuery("SELECT name from UNICODE_MAPPING WHERE unicode like \"$search%\"",'name');
    $new_match_string = '';
    $min_size = 1000;
    foreach ($n as $nelem){
	if (strlen("$nelem") < $min_size){
	    $new_match_string = $nelem;
	    $min_size = strlen($nelem);
	}
    }
    if ($new_match_string != '') { $search = $new_match_string; }

   mysql_close($cLink);

   $db = $_GET['db'];
    if ($db == 'movies'){
       location_replace("$_Master_search_process?db=$db&moviename=$search&search_type=1");
   }
   else if ($db == 'moviesongs'){
       location_replace("$_Master_search_process?db=$db&songname=$search&search_type=1");
   }
   else if ($db == 'albums'){
       location_replace("$_Master_search_process?db=$db&albumname=$search&search_type=1");
   }
   else if ($db == 'albumsongs'){
       location_replace("$_Master_search_process?db=$db&songname=$search&search_type=1");
   }
   else if ($db == 'raga'){
       location_replace("$_Master_profile_script?artist=$search&category=raga");
   }
   else {
       location_replace("$_Master_quicksearch?q=$search");
   }
}
function location_replace($loc){
    echo "<script>location.replace(\"$loc\");</script>";
}
?>
