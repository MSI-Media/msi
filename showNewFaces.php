<?php session_start();
{
    error_reporting (E_ERROR);
    require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");

    $_GET['lang'] = $_SESSION['lang'];
    $profileScript = $_Master_profile_script;
    $songScript    = $_Master_songlist_script;
    $movieScript   = $_Master_movie_script;
    $albumScript    = $_Master_album_script;
    $albumsongScript   = $_Master_albumsonglist_script;

    $_GET['encode']='utf';

    $cLink = msi_dbconnect();
    printXHeader('');

    $modes = array('M_WRITERS');
    mysql_query("SET NAMES utf8");
    $year = $_GET['year'];
    $mode = $_GET['mode'];

    if ($mode == 'M_MUSICIAN'){
	$url = "$_Master_profile_script?category=musician&artist=";
	$title = "Debutant Musicians for the Year";
	$table = "MOVIES";
	$tag = "M_YEAR";
	$mmode  = "Composers";
	$tagid = "M_ID";
	$picpath = "pics/Musicians";
    }
    else if ($mode == "M_WRITERS"){
	$url = "$_Master_profile_script?category=lyricist&artist=";
	$title = "Debutant Lyricists for the Year";
	$table = "MOVIES";
	$tag = "M_YEAR";
	$mmode  = "Lyricists";
	$tagid = "M_ID";
	$picpath = "pics/Lyricists";
    }
    else if ($mode == "S_SINGERS"){
	$url = "$_Master_profile_script?category=singers&artist=";
	$title = "Debutant Singers for the Year";
	$mmode  = "Singers";
	$table = "SONGS";
	$tag = "S_YEAR";
	$tagid = "S_ID";
	$picpath = "pics/Singers";
    }
    if ($_GET['lang'] != 'E') { $msg = get_uc($msg,''); $title = get_uc($title,''); }
    if ($_GET['lang'] != 'E') { $mmode = get_uc($mmode,''); }
    echo "<div class=psubheading> $mmode ($year)</div><P>";    
    echo "<div class=pcellslong>  \n";
//  $q = "SELECT DISTINCT ($mode) FROM $table where $tag like \"$year\" AND $mode NOT IN (SELECT DISTINCT $mode FROM $table WHERE $tag < \"$year\") ORDER BY $mode";
    $q = "SELECT DISTINCT ($mode) FROM $table where $tag like \"$year\" and $mode not like \"NA\" and $mode not like \"Uncategorized\" ORDER BY $mode";
    $allcomposers = array();
    $res_funcQry = mysql_query($q);
    $num_funcQry = mysql_num_rows($res_funcQry);
    $i = 0;
    while ($i < $num_funcQry){
	$val = mysql_result($res_funcQry, $i, "$mode");
	if (strpos($val,",") !== false){
	    $elems = explode(',',$val);
	    foreach ($elems as $elem){
		$elem = rtrim(ltrim($elem));
		if (!in_array("$elem",$allcomposers)){
		    array_push ($allcomposers,"$elem");
		}
	    }
	}
	else {
	    $val = rtrim(ltrim($val));
	    if (!in_array("$val",$allcomposers)){
		array_push ($allcomposers,"$val");
	    }
	}
	$i++;
    }
    sort($allcomposers);
    $cnt=0;
    echo "<table width=100% align=center><tr><td width=60% valign=top>\n";
    echo "<table align=center width=40% class=ptables>\n";
    foreach ($allcomposers as $ac){
	$vcount = runQuery("SELECT COUNT($tagid) as ccn FROM $table WHERE $tag < \"$year\" and ($mode like \"$ac\" or $mode like \"$ac\,%\" or $mode like \"%,$ac\" or $mode like \"%,$ac,%\") ",'ccn');

	if ($vcount < 1){
	    $cnt++;
	    if ( $i&1 ) {
		$printstyle = 'odd';
	    }
//	    $pic = "pics/NoPhoto.jpg";
	    if (file_exists("$picpath/${ac}.jpg")){
		$pic = "$picpath/${ac}.jpg";
	    }
	    if ($_GET['lang'] != 'E') { $acval = get_uc($ac,''); } else { $acval = $ac; }
	    if ($pic != ""){
		echo "<tr><td align=center class=\"pcellslong\"><a href=\"${url}${ac}\"> <img src=\"$pic\" height=100 width=100> <br>$acval</a></td></tr>";
		$pic = '';
	    }
	    else {
		echo "<tr><td align=center class=\"pcellslong\"><a href=\"${url}${ac}\"> $acval</a></td></tr>";
	    }
	    $printstyle = '';	
	}
    }
    echo "</table>";
echo "</td><td valign=top>\n";
echo "<div class=\"fb-like-box\" data-href=\"http://facebook.com/malayalasangeetham.info\" data-width=\"400\" data-height=\"600\" data-show-faces=\"true\" data-stream=\"true\" data-header=\"true\"></div>\n";
echo "</td></tr></table>\n";
    echo "</div><P>";
    printFancyFooters();  
    mysql_close($cLink);

}
?>
