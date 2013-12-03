<?php session_start();
{
    require_once("includes/utils.php");
    require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("_includes/_searchUtils.php");


    $_GET['lang'] = $_SESSION['lang'];
    $_GET['encode']='utf';
    $cLink = msi_dbconnect();
    printXHeader('');

    $movieScript = $_Master_movie_script;
    $songScript  = $_Master_song_script;
    $albumScript = $_Master_album_script;
    $albumsongScript = $_Master_albumsong_script;
    $searchScript = $_Master_search;


    $str = $_GET['str'];


    $len  = strlen($str);
    if ($len > 8){
	$val8 = substr($str,0,8);
    }
    else {
	$val8 = $str;
    }
    
    $val8 = str_replace("a","%a%",$val8);
    $val8 = str_replace("e","%e%",$val8);
    $val8 = str_replace(" ","%",$val8);
    $val8 = str_replace(",","%",$val8);
    $val8 = str_replace("t","t%",$val8);


    $songlist = array();
    $valregexp  = expandStr($str);
    $valregexp = str_replace('**','*',$valregexp);
    $qry = "SELECT songid from PSONGS WHERE songstr regexp \"$valregexp\" or songstr like \"%$val8%\" or songstr like \"%$str%\"";
    echo $qry;
    $songlist = buildArrayFromQuery($qry, 'songid');
    if ($songlist[0] > 0){
	echo "<table class=ptables>\n";
	echo "<tr class=tableheader>\n";
	printDetailCellHeadsSorts ('Song',1,"$_Master_songlist_script?${_qs}");
	printDetailCellHeadsSorts ('Movie',2,"$_Master_songlist_script?${_qs}");
	printDetailCellHeadsSorts ('Year',3,"$_Master_songlist_script?${_qs}");
	printDetailCellHeadsSorts ('Musician',4,"$_Master_songlist_script?${_qs}");
	printDetailCellHeadsSorts ('Lyricist',4,"$_Master_songlist_script?${_qs}");
	printDetailCellHeadsSorts ('Singers',5,"$_Master_songlist_script?${_qs}");
	echo "</tr>\n";
    }
    $opct=50;
    foreach ($songlist as $sid){
	$query = "SELECT S_SONG,S_MOVIE,S_YEAR,S_SINGERS,S_MUSICIAN,S_WRITERS,S_RAGA from SONGS where S_ID=$sid";
	$result        = mysql_query($query);
	$num_results   = mysql_num_rows($result);
	$i=0;
	if ($num_results > 0) {
	    while ($i < $num_results){
		$song_name_found = mysql_result($result, $i, "S_SONG");
	    	similar_text(substr($song_name_found,0,8),substr($str,0,8),$pct);
		echo  substr($song_name_found,0,8),'*',substr($str,0,8),'*',$pct, "<BR>";
		if ($pct > $opct){
		echo "<tr class=ptableslist>\n";
		$mid = mysql_result($result, $i, "M_ID");
		printDetailCells (mysql_result($result, $i, "S_SONG"),"$songScript?$sid",$i);
		printDetailCells (mysql_result($result, $i, "S_MOVIE"),"$movieScript?$mid",$i);
		printDetailCells (mysql_result($result, $i, "S_YEAR"),'',$i);
		printDetailCells (mysql_result($result, $i, "S_MUSICIAN"),'',$i);
		printDetailCells (mysql_result($result, $i, "S_WRITERS"),'',$i);
		printDetailCells (mysql_result($result, $i, "S_SINGERS"),'',$i);
		echo "</tr>";
}
		$i++;
	    }
	}
    }
    if ($songlist[0] > 0) { echo "</table>"; }
    printFancyFooters();
    mysql_close($cLink);

}

?>
