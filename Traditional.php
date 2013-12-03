<?php session_start();

    error_reporting (E_ERROR);
    $_GET['lang'] = $_SESSION['lang'];
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");

    require_once("includes/utils.php");


    $_GET['encode']='utf';
    printXHeader('');
    $cLink = msi_dbconnect();
    mysql_query("SET NAMES utf8");


    $song_str          = $_GET['song_str'];
$song_str_no_spaces = str_replace(" ","",$song_str);




$query       = "SELECT DISTINCT S_WRITERS, COUNT( S_ID ) ccn FROM SONGS  WHERE S_WRITERS like \"%Traditional%\" GROUP BY S_WRITERS ORDER BY ccn DESC;";
$result      = mysql_query($query);
$num_results = mysql_num_rows($result);
$i=0;
$cnt=1;
echo "<P>";
$fndsongs = "Traditional";
$contread = "Continue Reading";
if ($_GET['lang'] != 'E'){
    $fndsongs = get_uc($fndsongs,'');
    $contread = get_uc($contread,'');
}
//echo "<div align=center class=pcells style=\"font-weight=bold\">$num_results $fndsongs<br>";
echo "<table class=ptables>\n";
//printProfileHeaders('Traditional','Movie Songs','Album Songs','');
echo "<tr><td>Traditional</td><td>Movie Songs</td><td>Non Movies</td></tr>";
while ($i < $num_results){
    $genre   = mysql_result($result,$i,"S_WRITERS");
    $ccn     = mysql_result($result,$i,"ccn");

  
    $ccna = runQuery("SELECT DISTINCT COUNT( S_ID ) ccna FROM ASONGS WHERE S_WRITERS=\"$genre\"",'ccna');

    $ugenre  = $genre;

    if ($_GET['lang'] != 'E'){
	$ugenre  = get_uc("$genre","");
 
    }

    if ( $i&1 ) {
	$printstyle = 'odd';
    }
  $ugenre = stripTrads($ugenre);
  $genre = stripTrads($genre);

    if (file_exists("pics/Lyricists/TN/$genre.jpg")){
       $pic = "pics/Lyricists/TN/$genre.jpg";
       $piclink = "<a href=\"$_Master_profile_script?category=lyricist&artist=Traditional ($genre)\" class=\"preview\" rel=\"$pic\">$ugenre</a>";
    }
    else {
       $piclink = "<a href=\"$_Master_profile_script?category=lyricist&artist=Traditional ($genre)\ class=\"preview\">$ugenre</a>";
    }
    


    if ($genre != "" && $genre != "Select One") {
	echo "<tr><td class=\"pcells${printstyle}\">$piclink</td><td class=\"pcells${printstyle}\"><a href=\"$_Master_songlist_script?tag=Search&lyricist=Traditional ($genre)&limit=$ccn\">$ccn</a></td> <td class=\"pcells${printstyle}\"><a href=\"$_Master_albumsonglist_script?tag=Search&lyricist=Traditional ($genre)&limit=$ccna\">$ccna</a></td> </tr>\n";
    }
    $genre='';$ugenre='';
    $printstyle = '';	
    $i++;
}
echo "</table>"; 
    printFancyFooters();
mysql_close($cLink);


function stripTrads($ugenre) 
{
    $ugenre = str_replace('Traditional','',$ugenre);
    $ugenre = str_replace('പരമ്പരാഗതം','',$ugenre);
    $ugenre = str_replace('(','',$ugenre);
    $ugenre = str_replace(')','',$ugenre);
   
    return ltrim(rtrim($ugenre));
}
?>
