<?php session_start();
{
//  error_reporting (E_ERROR);
    require_once("includes/utils.php");
    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_moviePageUtils.php");



    $_GET['lang'] = $_SESSION['lang'];

    $_GET['encode']='utf';
    $cLink = msi_dbconnect();
    printXHeader('Popup');
    mysql_query("SET NAMES utf8");
    $episode = $_GET['e'];
    $cn = $_GET['cn'];
    if (!$episode) { $episode = '1'; }
    if (!$cn) { $cn = 'BV'; }

    $episode_title = $episode;


    if ($cn == 'BV' || !$cn) { $cname = 'A Viewfinder to the Trodden Tracks'; }

    if ($episode == '' || $episode== '0'){
	$episode       = '0';
	$nextepisode   = "1";
	$episode_title = 'Introduction';
    }
    else {
	$nextepisode = $episode + 1;
	$prevepisode = $episode - 1;
    }


    $prev = 'Prev Article';
    $next = 'Next Article';
    $part = 'Article';

    $tags = runQuery ("SELECT tags from ACOLUMNS where ordr=\"$episode\"",'tags');
    $tagstring = '';
    $taglist = explode (',',$tags);
    $tagstring = getTagString ($taglist);

    $articleSource = runQuery("SELECT path from ACOLUMNS where ordr=$episode and coltitle=\"$cn\"",'path');

    if (!file_exists($articleSource)){
      if ($_GET['lang'] != 'E'){
        $articleSource = $articleSource . "_malayalam.txt";
      }
      else {
        $articleSource = $articleSource . ".txt";
      }
    }
    if (!file_exists($articleSource)){
	$articleSource = runQuery("SELECT path from ACOLUMNS where ordr=$episode and coltitle=\"$cn\"",'path');	
    }
    
    $ep_msg = "The Article You Requested is Not Yet Uploaded. Please Visit Back Soon";
    $goback_msg = "Return to The First Article";
    if ($_GET['lang'] != 'E'){
	$prev = get_uc($prev,'');
	$next = get_uc($next,'');
	$part = get_uc($part,'');
	$ep_msg = get_uc($ep_msg,'');
	$cname = get_uc($cname, '');	
	$goback_msg = get_uc($goback_msg,'');
	if ($episode_title == 0){
	    $episode_title = get_uc($episode_title,'');
	}
    }
//  echo "<div class=pheading>${cname}</div><BR>";

    if ($cn == 'BV'){
	$title_img = "images/Abhrapalikalude.png";
	$title_sub = "A Weekly Column on Movies, Music & Opinions from MSI";
	$width=500;
    }
    else if ($cn == 'GNN'){
	$title_img = "images/Kadhaparayunnu.png";
	$title_sub = "A Walk Down The Memory Lane";
	$width=200;
    }
    else {
	$title_img = "images/Laghuvishayangal.png";
	$title_sub = "A Regular Column on Trivia from Malayalam Movies and Music";
	$width=300;
    }

    echo "<div align=center><img src=\"$title_img\" width=$width></div>\n";
    echo "<div class=psubtitle>$title_sub</div><P>";
    $auth = runQuery("SELECT author from ACOLUMNS where ordr=$episode and coltitle=\"$cn\"",'author');
/*
    $pic = "pics/Critics/${auth}.jpg";
    if (file_exists("$pic")){
	echo "<div align=center><a href=\"#\" class=\"shadow\"><img src=\"$pic\" border=0 height=100 onclick=\"javascript:return false;\" onmousedown=\"if(event.button==2){return false;}\"></a></div><br>\n"; 
    }
    printContents("columns/${cn}/Introduction.txt");
*/
    $lim = runQuery("SELECT ordr from ACOLUMNS order by ordr DESC LIMIT 1",'ordr');

    if ($episode <= $lim ){
        printContents($articleSource);	
	echo "<P><div class=pheading>$avstring</div><P><div class=psubtitle>$tagstring</div><p>";

	echo "<div class=psubtitle> \n";
	if ($episode != '' && $episode != '1'){
	    echo "<a href=\"$_Master_Columns_script?cn=$cn&e=$prevepisode\"><< $prev </a> | \n";
	}
	echo "<a href=\"$_Master_Columns_script?&cn=$cn&e=$nextepisode\"> $next >></a> </div>\n";
    }
    else {
	echo "<div class=psubheading>$ep_msg</div>";
	echo "<div class=psubtitle> <a href=\"$_Master_Columns_script?e=1&cn=$cn\"><< $goback_msg </a></div> \n";
    }

    printFancyFooters();
    mysql_close($cLink);
}
function addColumnsTables(){
    $query = "SELECT * FROM ACOLUMNS ORDER BY ordr";
    $res_Qry = mysql_query($query);
    $num_Qry = mysql_num_rows($res_Qry);
    echo "<table class=ptables>\n";
    echo "<tr class=tableheader>\n";
    printDetailCellHeads ('Article');
    printDetailCellHeads ('Key References');
    echo "</tr>";
    while ($i < $num_Qry){
	$ep = mysql_result($res_Qry, $i, "ordr");
	printDetailCells ($ep,"$_Master_Columns_script?e=${ep}",'');
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
	    $url = "http://malayalasangeetham.info/$_Master_movie_script?$mov";
	}
    }

    if ($url == ''){
	$mus = runQuery("SELECT M_DIRECTOR FROM MOVIES WHERE M_DIRECTOR=\"$tag\" LIMIT 1",'M_DIRECTOR');
	if ($mus != ''){
	    $url = "http://malayalasangeetham.info/$_Master_profile_script?artist=$mus&category=director";
	}
    }

    if ($url == ''){
	$act  = runQuery("SELECT M_CAST FROM MDETAILS WHERE M_CAST like \"%$tag%\" LIMIT 1",'M_CAST');
	$acts = explode (',',$act);
	foreach ($acts as $ac){
	    $ac = ltrim(rtrim($ac));
	    if ($ac == $tag){
		$url = "http://malayalasangeetham.info/$_Master_profile_script?artist=$ac&category=actors";
	    }
	}
    }

    if ($url == ''){
	$mus = runQuery("SELECT M_MUSICIAN FROM MOVIES WHERE M_MUSICIAN=\"$tag\" LIMIT 1",'M_MUSICIAN');
	if ($mus != ''){
	    $url = "http://malayalasangeetham.info/$_Master_profile_script?artist=$mus&category=musician";
	}
    }

    if ($url == ''){
	$lyr = runQuery("SELECT M_WRITERS FROM MOVIES WHERE M_WRITERS=\"$tag\" LIMIT 1",'M_WRITERS');
	if ($lyr != ''){
	    $url = "http://malayalasangeetham.info/$_Master_profile_script?artist=$lyr&category=lyricist";
	}
    }

    if ($url == ''){
	$lyr = runQuery("SELECT S_SINGERS FROM SONGS WHERE S_SINGERS=\"$tag\" LIMIT 1",'S_SINGERS');
	if ($lyr != ''){
	    $url = "http://malayalasangeetham.info/$_Master_profile_script?artist=$lyr&category=singers";
	}
    }

    if ($url == ''){
	$lyr = runQuery("SELECT M_PRODUCER FROM MDETAILS WHERE M_PRODUCER=\"$tag\" LIMIT 1",'M_PRODUCER');
	if ($lyr != ''){
	    $url = "http://malayalasangeetham.info/$_Master_profile_script?artist=$lyr&category=producer";
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
