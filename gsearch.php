<?php session_start();
{
    error_reporting (E_ERROR);

    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("includes/utils.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("_includes/_grep.php");
    $_GET['encode']='utf';

    $cLink = msi_dbconnect();
    printXheader('auto');
    mysql_query("SET NAMES utf8");
    set_time_limit(0);				
    $db = $_GET['db'];
    if (!$db) { $db = 'movies'; }


    define("SLASH", stristr($_SERVER['SERVER_SOFTWARE'], "win") ? "\\" : "/"); // slash for win or unix
	$arr  = array();
    $path	= ($_POST['path']) ? $_POST['path'] : dirname(__FILE__) ;
    $search		= $_POST['search'];
    $total        = 0;  // stat count (statistics)
    $occurance    = 0;  // stat count
    $filesearched = 0;  // stat count
    $ret_array    = array();
    $dirsearched  = 0;  // stat count	
//    echo php_grep("Manmadha","/home/msidbo6/public_html/Lyrics");
    $arr = php_grep("Manmadha","AlbumLyrics");
    print "$total , $occurance, $filesearched <BR>";
    foreach ($arr as $arelem){
	$l = explode ('|',$arelem);
	print "$l[0] -> $l[1]<BR>";
    }
    printFancyFooters();
    mysql_close($cLink);
}
?>
