<?php session_start();

{
    error_reporting (E_ERROR);
    $_GET['lang'] = $_SESSION['lang'];
    require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("includes/cache.php");
    require_once("_includes/_System.php");

/*
    if (isNonAdminUser()){
	$ch = new cache($_GET['lang'],'Songs');
    }
*/

    $cLink = msi_dbconnect();
    printXHeader('');


    $lang = $_GET['lang'];
    $sid  = $_GET['sid'];
    if (!$sid){
	$sids = explode('&',$_SERVER['QUERY_STRING']);
	$sid = $sids[0];
    }

    $lyricsRoot = findRoot($sid,'Lyrics','Movies');


    $query      = "SELECT * FROM SONGS WHERE S_ID=$sid";
    $result     = mysql_query($query);
    $num_results=mysql_num_rows($result);
    $i=0;

    if ($num_results == 0){
	$missfile = 'Writeups/MissingSong';	
	if ($_GET['lang'] == 'E') { $missfile .= '_English';}
	printContents("${missfile}.html");
    }
    else {
	$movieName = mysql_result($result,$i,"S_MOVIE");
	$songName  = mysql_result($result,$i,"S_SONG");
	$mid       = mysql_result($result,$i,"M_ID");

	$corvt = "Change the Song Details";
	$vidvt = "Add Video";

	$corv = "images/icon-edit.gif";
	$mus  = "icons/Music.png";
	$vid  = "images/youtube.png";
	$lyrs = "icons/Notebook.png";
        $prnt = "icons/print.png";
	$kark = "icons/Karaoke.png";

	$lyr_lock  = '';
	global $_MasterRootDir,$_RootDir,$_RootofMSI;
	if (file_exists("$_MasterRootDir/php/temp/lyrics/${sid}.html") ||  file_exists("$_MasterRootDir/php/temp/mal_lyrics/${sid}.html")){
	    $lyr_lock = 'L';
	}
	if ($_GET['debug'] == 1){
	    echo "$lyr_lock ** <BR>";
	}
	if ($_GET['lang'] == 'E'){
	    echo "<div class=pbheading>$songName <br> </div>\n";
	    echo "<div class=psubheading> <a href=\"$_Master_movie_script?$mid\">$movieName</a> </div>";
	}
	else {
   	    $corvt = get_uc("$corvt",'');		
	    $vidvt = get_uc("$vidvt",'');
	    $mal_movieName = get_uc($movieName,'');
	    $mal_songName  = get_uc($songName,'');
	    echo "<div class=pbheading>$mal_songName <br> </div>\n";
	    echo "<div class=psubheading> <a href=\"$_Master_movie_script?$mid\">$mal_movieName</a> </div>";
	}
	
	$tt_details = "Change Details";
	$tt_video   = "Add Video";
	$tt_audio   = "Add Audio Clip";
	$tt_lyrics  = "Add Lyrics";
        $tt_prnt    = "Print Lyrics and Details";
        $tt_kark    = "Add Karaokes";
	//$submissionMessage = file_get_contents("Writeups/songSubmissionMessage.txt");
	if ($_GET['lang'] != 'E'){
	    $tt_details = get_uc($tt_details,'');
	    $tt_video   = get_uc($tt_video,'');
	    $tt_audio   = get_uc($tt_audio,'');
	    $tt_lyrics  = get_uc($tt_lyrics,'');
            $tt_prnt    = get_uc($tt_prnt,'');
	    $tt_kark    = get_uc($tt_kark,'');
          // $submissionMessage = file_get_contents("Writeups/songSubmissionMessageEnglish.txt");
        }

	$clipavail = mysql_result($result,$i,"S_CLIP");
	$kclipavail = mysql_result($result,$i,"S_KCLIP");
	echo "<table class=ptables><tr><td valign=top width=50%>\n";
	$video = printAV ($sid,$clipavail);
        	    
	if ($video) {
	    if ($lyr_lock == 'L') {
		echo "<BR><div class=psubtitle>  <a href=\"$_Master_Managecomments_script?song_id=$sid\"><img src=\"$corv\" class=\"tooltip\" title=\"$tt_details\" alt=\"$corvt\" height=20 border=0></a>  <a href=\"$_Master_Submitclips_script?sid=$sid\"> ";
		if ($clipavail != 'Y' && $clipavail != 'L'){
		    echo "<img src=\"$mus\" class=\"tooltip\" title=\"$tt_audio\" border=0 height=20></a>";
		}
		if ($kclipavail != 'Y' && $kclipavail != 'L'){
		    echo "<a href=\"$_Master_Submitkars_script?sid=$sid\"><img src=\"$kark\" class=\"tooltip\" title=\"$tt_kark\" border=0 height=20></a>";
		}
		echo "</div>";
	    }
	    else {
		echo "<BR><div class=psubtitle>  <a href=\"$_Master_Managecomments_script?song_id=$sid\"><img src=\"$corv\" class=\"tooltip\" title=\"$tt_details\" alt=\"$corvt\" height=20 border=0></a>  <a href=\"$_Master_Submitclips_script?sid=$sid\">";
		if ($clipavail != 'Y' && $clipavail != 'L'){
		    echo "<img src=\"$mus\" class=\"tooltip\" title=\"$tt_audio\" border=0 height=20></a>  ";
		}
		if ($kclipavail != 'Y' && $kclipavail != 'L'){
		    echo "<a href=\"$_Master_Submitkars_script?sid=$sid\"><img src=\"$kark\" class=\"tooltip\" title=\"$tt_kark\" border=0 height=20></a>";
		}
		echo "<a href=\"$_Master_Managelyrics_script?song_id=$sid\"><img src=\"$lyrs\" height=20 class=\"tooltip\" title=\"$tt_lyrics\" border=0></a> <a href=\"sprint.php?$sid\" target=\"_new\"><img src=\"$prnt\" title=\"$tt_prnt\" border=0 height=20></a> </div>";
	    }
	}
	else {
	    if ($lyr_lock == 'L'){
		echo "<P><div class=psubtitle> <a href=\"$_Master_Managecomments_script?song_id=$sid\"><img src=\"$corv\" class=\"tooltip\" title=\"$tt_details\" alt=\"$corvt\" height=20 border=0></a> ";
	    }
	    else {
		echo "<P><div class=psubtitle>  <a href=\"$_Master_Managecomments_script?song_id=$sid\"><img src=\"$corv\" class=\"tooltip\" title=\"$tt_details\" alt=\"$corvt\" height=20 border=0></a>   <a href=\"$_Master_Managelyrics_script?song_id=$sid\"><img src=\"$lyrs\" class=\"tooltip\" title=\"$tt_lyrics\" height=20 border=0></a> <a href=\"sprint.php?$sid\" target=\"_new\"><img src=\"$prnt\" title=\"$tt_prnt\" border=0 height=20></a> ";
	    }

	    $vidlock = runQuery("SELECT UT_ID from UTUBE WHERE UT_ID=$sid and UT_STAT=\"Submitted\"",'UT_ID');
	    $audlock = runQuery("SELECT S_ID from SONGS WHERE S_ID=$sid and S_CLIP=\"L\"",'S_ID');
	    if ($vidlock != $sid) {
		echo "<a href=\"$_Master_Submitvids_script?sid=$sid\"><img src=\"$vid\" alt=\"$vidvt\" class=\"tooltip\" title=\"$tt_video\"></a>";
	    }
	    if ($audlock != $sid) {
		if ($clipavail != 'Y' && $clipavail != 'L'){
		    echo "<a href=\"$_Master_Submitclips_script?sid=$sid\"> <img src=\"$mus\" border=0 height=20 class=\"tooltip\" title=\"$tt_audio\"></a>  \n";
		}
	    }
	    if ($kclipavail != 'Y' && $kclipavail != 'L'){
		echo "<a href=\"$_Master_Submitkars_script?sid=$sid\"> <img src=\"$kark\" border=0 height=20 class=\"tooltip\" title=\"$tt_kark\"></a>  \n";
	    }

	    echo "</div>";
	}
	$mus = mysql_result($result,$i,"S_MUSICIAN");
	$lyr = mysql_result($result,$i,"S_WRITERS");
	$sin = mysql_result($result,$i,"S_SINGERS");

	echo "</td><td valign=top>";

	echo "<table width=100%><tr>";

        printDetailHeadingRows ('Details','2');
	$rag = mysql_result($result,$i,"S_RAGA");
	$yr  = mysql_result($result,$i,"S_YEAR");
	$gen =  mysql_result($result,$i,"S_GENRE");
	$icnt=0;
	printDetailRows ('Year',$yr,'',$icnt++);
	printDetailRows ('Musician',$mus,'Musicians',$icnt++);
	printDetailRows ('Lyricist',$lyr,'Lyricists',$icnt++);
	printDetailRows ('Singers',$sin,'Singers',$icnt++);
	if ($rag && $rag != 'Uncategorized'){
	    printDetailRows ('Raga',$rag,'Ragas',$icnt++);
	}
	if ($gen && $gen != '*' && $gen != 'Select One'){
	    printDetailRows ('Genre',$gen,'',$icnt++);
	}

	$actor_list = runQuery("SELECT UT_ACTORS FROM UTUBE where UT_ID=$sid",'UT_ACTORS','UT_ACTORS');
	if ($actor_list != '' && $actor_list != 'NULL' && $actor_list != 'Uncategorized'){
	    printDetailRows ('Actors',$actor_list,'Actors',$icnt++);
	}
        echo "</td></tr>";
        echo "<tr><td>&nbsp;</td><td valign=top>";
	echo "</td></tr></table>";
	printPicturesExtended ($mid,$mus,$lyr,$sin, 'Movies');
        echo "</td></tr></table>\n";

	print "<div class=ptables>\n";
        printSFB($sid,'');        
	echo "</div>";


	$hdr = "Lyrics";
	if ($_GET['lang'] != 'E'){
	    $hdr = get_uc($hdr,'');
	}

	echo( "<table class=ptables>\n");
        printDetailHeadingRows ("$hdr",'2');
	echo "<tr><td valign=top>\n";
	if ($lyr_lock != 'L') {
	    $hdr3 = "Change Lyrics";
	    if ($_GET['lang'] != 'E'){
		$hdr3 = get_uc("$hdr3",'');
	    }
	    if (file_exists ("$lyricsRoot/$sid.html")){
	        echo "<div class=nonselectable>\n";
		printLyricsContents("$lyricsRoot/$sid.html",$_GET['lang']);
		echo "</div>";
		echo "<a href=\"$_Master_Managelyrics_script?song_id=$sid\">$hdr3</a>";
	    }
	    else {
		$hdr1 = "Lyrics Not Available";
		$hdr2 = "Click here to add";
		if ($_GET['lang'] != 'E'){
		    $hdr1 = get_uc("$hdr1",'');
		    $hdr2 = get_uc("$hdr2",'');
		}
		echo "$hdr1<p><a href=\"$_Master_Managelyrics_script?song_id=$sid\">$hdr2</a><P>";
	    }
	}
	else {
	    $hdr1 = "Lyrics Are Locked Pending Admin Approval";
	    $hdr2 = "Please visit back later";
	    if ($_GET['lang'] != 'E'){
		$hdr1 = get_uc("$hdr1",'');
		$hdr2 = get_uc("$hdr2",'');
	    }
	    echo "$hdr1<p>$hdr2</a><P>";
	}
	echo ("</td></tr></table>");


	echo( "<table class=ptables>\n");
	echo "<tr><td width=50% valign=top>";
	echo "<table width=100%>";
	$ic=0;
        printDetailHeadingRows ('Contributors','2');
//      printDetailOwnerRows('Lyrics Owner', mysql_result($result,$i,"S_LYROWNER"),'UserPics');
	$uname = findUserFromComments('S_COMMENTS','SONGS','S_ID',$sid);
	if ($uname){
	    printDetailOwnerRows('Details', $uname,'UserPics',$ic++);
	}
        printDetailOwnerRows('Lyrics Owner', findAllSongOwners ($sid,'S_LYROWNER','Movies') ,'UserPics',$ic++);
	printDetailOwnerRows('Audio Clip', mysql_result($result,$i,"S_CLIPOWN"),'UserPics',$ic++);
	printDetailOwnerRows ('Video Owner',runQuery("SELECT DISTINCT UT_OWN from UTUBE where UT_ID=$sid and UT_STAT=\"Published\" order by UT_OWN",UT_OWN), 'UserPics',$ic++);
	printDetailOwnerRows('Karaoke Owner', mysql_result($result,$i,"S_KCLIPOWN"),'UserPics',$ic++);

	echo ("</table>");
	echo( "</td><td valign=top><table width=100%>\n");
        printDetailHeadingRows ('Related Songs','2');
	$icnt=0;
	printLinkedRows('From This Movie', "$_Master_songlist_script?mid=$mid&tag=Search",'',$icnt++);
	printLinkedRows("From This Team","$_Master_songlist_script?tag=Search&musician=$mus&lyricist=$lyr&singers=$sin",'',$icnt++);
	printLinkedRows ("For This Year","$_Master_profile_script?category=year&artist=$yr",'',$inct++);
        $kclip  = mysql_result($result,$i,"S_KCLIP");
	$_GDMasterRootofMSI = "http://msidb.info";
        if ($kclip == 'Y' && url_exists("$_GDMasterRootofMSI/published_karaoke/${sid}.mp3")) { 
            printLinkedRows ("Karaoke Track For This Song","$_GDMasterRootofMSI/published_karaoke/${sid}.mp3",'_new',$icnt++);
        }
	else if ($kclip == 'Y' && url_exists("$_RootofMSI/published_karaoke/${sid}.mp3")) { 
            printLinkedRows ("Karaoke Track For This Song","$_RootofMSI/published_karaoke/${sid}.mp3",'_new',$icnt++);
	}
	if ($rag != ""){
	    printLinkedRows ("In This Raga","$_Master_profile_script?category=raga&artist=$rag",'',$icnt++);
	}
	echo ("</table>");
	echo ("</td></tr></table>");

    }

    $regenMsg = "Regenerate This Page";
    if ($_GET['lang'] != 'E') { $regenMsg = get_uc($regenMsg,''); }
    $today = date("F j, Y, g:i a T");
    echo( "<table class=ptables><tr><td align=center class=fixedtiny>This page was generated on $today | <a href=\"SecureCache.php?type=_s.php&typename=Songs&id=$sid\">$regenMsg</a></td></tr>");
    echo ("<tr><td align=center><a href=\"edits/createSongIndex.php?sid=$sid&admin=1&showform=yes\"><img src=\"images/small_lock.jpg\" border=0></a> | <a href=\"admin/EditMasterLyricsPageDirect.php?encode=utf&sid=$sid&lang=eng\"><img src=\"images/lock-icon.gif\" border=0></a></td></tr></table>");

    printFancyFooters();
    mysql_close($cLink);

/*
    if (isNonAdminUser()){
	$ch->close();
    }
*/
}


function printAV($sid,$clip)
{
 
    global $_MasterRootDir;
    global $_RootofMSI,$_RootDir;
    $_GDMasterRootofMSI = "http://msidb.info";
    $video = false;
    $avstring = "";
    $utube     = runQuery("SELECT UT_URL from UTUBE WHERE UT_ID=$sid AND UT_STAT='Published'",'UT_URL');
    $utubevalsx = explode ('&',$utube);
    $utubevals  = explode ('=',$utubevalsx[0]);
    if ($utubevals[1] != "") {
	$utlink  = "http://gdata.youtube.com/feeds/api/videos/$utubevals[1]";
	if (checkYoutubeId("$utlink")){
	    $avstring = "<P><iframe width=\"400\" height=\"225\" src=\"http://www.youtube.com/embed/$utubevals[1]?wmode=transparent\" frameborder=\"0\" allowfullscreen></iframe>";
	    $video=true;
	}
       if ($avstring == ""){
          $alternateVideo = 0;
    	  $video_url      = "";
	  $url = $utubevals[1];
	  if (file_exists("$_MasterRootDir/Videos/${url}.flv")) { $alternateVideo = 1; $video_url = "$_GDMasterRootofMSI/Videos/${url}.flv"; }
	  else if (file_exists("$_MasterRootDir/Videos/${sid}.flv")) { $alternateVideo = 1; $video_url = "$_GDMasterRootofMSI/Videos/${sid}.flv"; }
	  else if (file_exists("$_MasterRootDir/Videos/${sid}.FLV")) { $alternateVideo = 1; $video_url = "$_GDMasterRootofMSI/Videos/${sid}.FLV"; }
	  if ($alternateVideo == 1){
	      $video = true;
	      echo "<P><div class=pvideoheading>\n";
	      echo "<a href=\"$video_url\" id=\"player\"></a>\n";
	      echo "<script>flowplayer(\"player\", {src:\"fplayer/flowplayer-3.2.8.swf\", wmode: \"transparent\"}, {      clip:  {          autoPlay: false,          autoBuffering: true      }  });</script>\n";
	      echo "</div><P>\n";
	  }
       }
    }
    if ($clip == 'Y'){
	global $_playerLoc;
		
	$songFile  = findRoot($sid,'Audio','Movies') . '/' . "${sid}.mp3";
        $songLoc   = str_replace("$_RootofMSI","$_RootDir",$songFile);
        if (filesize($songLoc) < 1) {
            $songFile   = str_replace("$_RootofMSI","$_GDMasterRootofMSI",$songFile);
            $songFile   = str_replace("http://en.msidb.org","$_GDMasterRootofMSI",$songFile);
            $songFile   = str_replace("http://ml.msidb.org","$_GDMasterRootofMSI",$songFile);
        }
        echo "</div>";


	$browser = new Browser();
	if ($browser->getBrowser() == 'Firefox' && $browser->getVersion() < 19){
	   $oldbrowser = 1;
	}
	else if ($browser->getBrowser() == 'MSIE' && $browser->getVersion() < 9){
	   $oldbrowser = 1;
	}
	else if ($browser->getPlatform() == 'WinXP' || $browser->getPlatform() == 'Windows'){
	   $oldbrowser = 1;
	}
	if ($_GET['debug2013'] ==1) { echo "Running on ", $browser->getPlatform(), ":", $browser->getBrowser(), ":", $browser->getVersion, "<BR>"; }

        if (!$oldbrowser){
	    echo "<div class=psubtitle><audio controls height=\"100\" width=\"100\">\n";
	    echo "<source src=\"$songFile\" type=\"audio/mpeg\">\n";
	    echo "<embed height=\"50\" width=\"100\" src=\"$songFile\">\n";
	    echo "</audio></div>\n";
        }
        else {
	    $avstring .= "<script language=\"JavaScript\" src=\"$_playerLoc/audio-player.js\"></script>\n";
	    $avstring .= "<object type=\"application/x-shockwave-flash\" data=\"$_playerLoc/player.swf\" id=\"audioplayer1\" height=\"24\" width=\"400\">\n";
	    $avstring .= "<param name=\"movie\" value=\"$_playerLoc/player.swf\">\n";
	    $avstring .= "<param name=\"FlashVars\" value=\"playerID=1&amp;soundFile=$songFile\">\n";
	    $avstring .= "<param name=\"quality\" value=\"high\">\n";
	    $avstring .= "<param name=\"menu\" value=\"false\">\n";
	    $avstring .= "<param name=\"wmode\" value=\"transparent\">\n";
	    $avstring .= "</object>\n";
        }
    }
    echo "<div class=pheading>$avstring</div>";
    if ($clip == 'Y'){
//	$xmsg = 'Song clips of limited duration provided here are for identification purposes only. Please do not contact us for full songs as MSI is not a song downloading site';
	$xmsg = 'Audio clip is for identification Only. Full song not available';
	if ($_GET['lang'] != 'E') { $xmsg = get_uc($xmsg,''); }
	echo "<div class=psmallsubtitle>$xmsg</div>";
    }

    return $video;
}



function link_exists($strURL) {
    $resURL = curl_init();
    curl_setopt($resURL, CURLOPT_URL, $strURL);
    curl_setopt($resURL, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($resURL, CURLOPT_HEADERFUNCTION, 'curlHeaderCallback');
    curl_setopt($resURL, CURLOPT_FAILONERROR, 1);

    curl_exec ($resURL);

    $intReturnCode = curl_getinfo($resURL, CURLINFO_HTTP_CODE);
    curl_close ($resURL);

    if ($intReturnCode != 200 && $intReturnCode != 302 && $intReturnCode != 304) {
       return false;
    }Else{
        return true ;
    }
} 

?>
