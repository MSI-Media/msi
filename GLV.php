<?php session_start();
{
    error_reporting (E_ERROR);
    require_once("includes/utils.php");
    require_once("includes/data.php");
    require_once("includes/moviePageUtils.php");
    $_GET['lang'] = $_SESSION['lang'];

    $_GET['encode']='utf';
    printHeaders('');
    $cLink = msi_dbconnect();
    mysql_query("SET NAMES utf8");
    $_GDMasterRootofMSI = "http://msidb.info";
    $episode = $_GET['e'];
    $episode_title = $episode;

    if ($episode == '' || $episode== '0'){
	$episode = '0';
	$nextepisode = "1";
	$episode_title = 'Introduction';
    }
    else {
	$nextepisode = $episode + 1;
	$prevepisode = $episode - 1;
    }
    global $_playerLoc;


    $songFile  = "$_GDMasterRootofMSI/GLV/${episode}.mp3";
    $avstring .= "<script language=\"JavaScript\" src=\"$_playerLoc/audio-player.js\"></script>\n";
    $avstring .= "<object type=\"application/x-shockwave-flash\" data=\"$_playerLoc/player.swf\" id=\"audioplayer1\" height=\"24\" width=\"400\">\n";
    $avstring .= "<param name=\"movie\" value=\"$_playerLoc/player.swf\">\n";
    $avstring .= "<param name=\"FlashVars\" value=\"playerID=1&amp;soundFile=$songFile\">\n";
    $avstring .= "<param name=\"quality\" value=\"high\">\n";
    $avstring .= "<param name=\"menu\" value=\"false\">\n";
    $avstring .= "<param name=\"wmode\" value=\"transparent\">\n";
    $avstring .= "</object>\n";

    $prev = 'Prev Episode';
    $next = 'Next Episode';
    $part = 'Episode';

    $tags = runQuery ("SELECT tags from GLV_TAGS where episode=\"$episode\"",'tags');
    $tagstring = '';
    $taglist = explode (',',$tags);
    $tagstring = getTagString ($taglist);

    
    $ep_msg = "The Episode You Requested is Not Yet Uploaded. Please Visit Back Soon";
    $goback_msg = "Return to The First Episode";
    if ($_GET['lang'] != 'E'){
	$prev = get_uc($prev,'');
	$next = get_uc($next,'');
	$part = get_uc($part,'');
	$ep_msg = get_uc($ep_msg,'');
	$goback_msg = get_uc($goback_msg,'');
	if ($episode_title == 0){
	    $episode_title = get_uc($episode_title,'');
	}
    }


    $lim = runQuery("SELECT episode from GLV_TAGS order by episode DESC LIMIT 1",'episode');

    if ($episode <= $lim){
	if ($episode_title == 0){
	    echo "<div class=pheading>$episode_title</div>";
	}
	else {
	    echo "<div class=pheading>$part $episode_title</div>";
	}
	echo "<P><div class=pheading>$avstring</div><P><div class=psubtitle>$tagstring</div><p>";

	echo "<div class=psubtitle> \n";
	if ($episode != ''){
	    echo "<a href=\"GLV.php?e=$prevepisode\"><< $prev </a> | \n";
	}
	echo "<a href=\"GLV.php?e=$nextepisode\"> $next >></a> </div>\n";
    }
    else {
	echo "<div class=psubheading>$ep_msg</div>";
	echo "<div class=psubtitle> <a href=\"GLV.php\"><< $goback_msg </a></div> \n";
    }
    if ($episode_title	== 0){
	addGLVTables();	
    }   
    else {
	echo "<div class=psubtitle> <a href=\"GLV.php\"><< $goback_msg </a></div> \n";
    }
    mysql_close($cLink);
    printFooters();
}
function addGLVTables(){
    $query = "SELECT * FROM GLV_TAGS ORDER BY episode";
    $res_Qry = mysql_query($query);
    $num_Qry = mysql_num_rows($res_Qry);
    echo "<table class=ptables>\n";
    echo "<tr class=tableheader>\n";
    printDetailCellHeads ('Episode');
    printDetailCellHeads ('Key References');
    echo "</tr>";
    while ($i < $num_Qry){
	$ep = mysql_result($res_Qry, $i, "episode");
	printDetailCells ($ep,"GLV.php?e=${ep}",'');
	$tags = mysql_result($res_Qry, $i, "tags");
	$taglist = explode (',',$tags);
	$tagstring = getTagString ($taglist);
	printDetailCells ($tagstring,'','');
	echo "</tr>";
	$i++;
    }
    print "</table>";
}
function findlinks ($tag){

    $url = '';

    $mov = runQuery("SELECT M_ID FROM MOVIES WHERE M_MOVIE=\"$tag\" LIMIT 1",'M_ID');
    if ($mov != ''){
	if ($mov > 0) {
	    $url = "http://malayalasangeetham.info/m.php?$mov";
	}
    }

    if ($url == ''){
	$mus = runQuery("SELECT M_DIRECTOR FROM MOVIES WHERE M_DIRECTOR=\"$tag\" LIMIT 1",'M_DIRECTOR');
	if ($mus != ''){
	    $url = "http://malayalasangeetham.info/displayProfile.php?artist=$mus&category=director";
	}
    }

    if ($url == ''){
	$act  = runQuery("SELECT M_CAST FROM MDETAILS WHERE M_CAST like \"%$tag%\" LIMIT 1",'M_CAST');
	$acts = explode (',',$act);
	foreach ($acts as $ac){
	    $ac = ltrim(rtrim($ac));
	    if ($ac == $tag){
		$url = "http://malayalasangeetham.info/displayProfile.php?artist=$ac&category=actors";
	    }
	}
    }

    if ($url == ''){
	$mus = runQuery("SELECT M_MUSICIAN FROM MOVIES WHERE M_MUSICIAN=\"$tag\" LIMIT 1",'M_MUSICIAN');
	if ($mus != ''){
	    $url = "http://malayalasangeetham.info/displayProfile.php?artist=$mus&category=musician";
	}
    }

    if ($url == ''){
	$lyr = runQuery("SELECT M_WRITERS FROM MOVIES WHERE M_WRITERS=\"$tag\" LIMIT 1",'M_WRITERS');
	if ($lyr != ''){
	    $url = "http://malayalasangeetham.info/displayProfile.php?artist=$lyr&category=lyricist";
	}
    }

    if ($url == ''){
	$lyr = runQuery("SELECT S_SINGERS FROM SONGS WHERE S_SINGERS=\"$tag\" LIMIT 1",'S_SINGERS');
	if ($lyr != ''){
	    $url = "http://malayalasangeetham.info/displayProfile.php?artist=$lyr&category=singers";
	}
    }

    if ($url == ''){
	$lyr = runQuery("SELECT M_PRODUCER FROM MDETAILS WHERE M_PRODUCER=\"$tag\" LIMIT 1",'M_PRODUCER');
	if ($lyr != ''){
	    $url = "http://malayalasangeetham.info/displayProfile.php?artist=$lyr&category=producer";
	}
    }

    return $url;


}
function getTagString($taglist){
    $tagstring = '';
    $tagarray = array();
    foreach ($taglist as $tag){
	$tag = ltrim(rtrim($tag));
	$ttag = $tag;
	if ($_GET['lang'] != 'E'){
	    $ttag = get_uc($tag,'');
	}
	$link = findlinks($tag);
	if ($link != ''){
	    array_push ($tagarray, "<a href=\"$link\" target=\"_new\">$ttag</a>");
	}
	else {
	    array_push ($tagarray,"$ttag");
	}
    }
    $tagstring = implode (',',$tagarray);
    return $tagstring;
}
