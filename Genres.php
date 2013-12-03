<?php session_start();

    error_reporting (E_ERROR);
    $_GET['lang'] = $_SESSION['lang'];
    require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");


    $_GET['encode']='utf';

    $cLink = msi_dbconnect();
    printXHeader('');
    mysql_query("SET NAMES utf8");


$song_str          = $_GET['song_str'];
$song_str_no_spaces = str_replace(" ","",$song_str);




$query       = "SELECT DISTINCT S_GENRE, COUNT( S_ID ) ccn FROM SONGS GROUP BY S_GENRE ORDER BY ccn DESC;";
$result      = mysql_query($query);
$num_results = mysql_num_rows($result);
$i=0;
$cnt=1;
echo "<P>";
$fndsongs = "Genres";
$contread = "Continue Reading";
if ($_GET['lang'] != 'E'){
    $fndsongs = get_uc($fndsongs,'');
    $contread = get_uc($contread,'');
}
echo "<div align=center class=pcells style=\"font-weight=bold\">$num_results $fndsongs</div>";
echo "<table class=ptables>\n";
printProfileHeaders('Genre','Movie Songs','Album Songs','');
while ($i < $num_results){
    $genre   = mysql_result($result,$i,"S_GENRE");
    $ccn     = mysql_result($result,$i,"ccn");
    $movie = mysql_result($result,$i,"S_MOVIE");
  
    $ccna = runQuery("SELECT DISTINCT COUNT( S_ID ) ccna FROM ASONGS WHERE S_GENRE=\"$genre\"",'ccna');

    $ugenre  = $genre;
    if ($_GET['lang'] != 'E'){
	$ugenre  = get_uc("$genre","");
    }

    if ( $i&1 ) {
	$printstyle = 'odd';
    }

    if ($genre != "" && $genre != "Select One") {
	echo "<tr><td class=\"pcells${printstyle}\">$ugenre</td><td class=\"pcells${printstyle}\"><a href=\"$_Master_songlist_script?tag=Search&genre=$genre&limit=$ccn\">$ccn</a></td> <td class=\"pcells${printstyle}\"><a href=\"$_Master_albumsonglist_script?tag=Search&genre=$genre&limit=$ccna\">$ccna</a></td> </tr>\n";
    }
    $printstyle = '';	
    $i++;
}
echo "</table>"; 

echo "<table class=ptables>\n";
printProfileHeaders('Genres','Albums','','');
$genrefile = "php/data/album_classification.txt";
$fh = fopen("$genrefile", "r");
$i=0;
if ($fh){  
    while (!feof($fh)){
	$lx = fgets($fh,1048576);
	$genre = ltrim(rtrim($lx));
	if ($genre != ''){
	    $ccn = runQuery("SELECT COUNT(M_ID) as ccn from ALBUMS WHERE M_COMMENTS=\"$genre\"",'ccn');
	    if ( $i&1 ) {
		$printstyle = 'odd';
	    }
	    if ($_GET['lang'] != 'E'){
		$ugenre  = get_uc("$genre","");
	    }else { $ugenre = $genre; }
	    echo "<tr><td class=\"pcells${printstyle}\">$ugenre</td><td class=\"pcells${printstyle}\"><a href=\"$_Master_albumlist_script?genre=$lx&limit=$ccn\">$ccn</a></td> </tr>";
	    $printstyle = '';	
	    $i++;
	}
    }
    fclose($fh);
}
echo "</table>"; 
printFancyFooters();
mysql_close($cLink);


?>
