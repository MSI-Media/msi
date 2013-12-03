<?php session_start();

{
    error_reporting (E_ERROR);
    $_GET['lang'] = $_SESSION['lang'];
    require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("_includes/_System.php");
    require_once("includes/cache.php");

/*
    if (isNonAdminUser()){
	$ch = new cache($_GET['lang'],'AlbumSongs');
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
     $lyricsRoot = findRoot($sid,'Lyrics','Albums');
     if ($_GET['debug'] == 1){
     	echo $lyricsRoot , "<BR>";
     }


    $query      = "SELECT * FROM ASONGS WHERE S_ID=$sid";
    $result     = mysql_query($query);
    $num_results=mysql_num_rows($result);
    $i=0;
    if ($num_results == 0){
	printContents("writeups/MissingAlbumSong.html");
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
	global $_MasterRootDir;
	if (file_exists("$_MasterRootDir/php/temp/album_lyrics/${sid}.html") ||  file_exists("$_MasterRootDir/php/temp/album_mal_lyrics/${sid}.html")){
	    $lyr_lock = 'L';
	}


	if ($_GET['lang'] == 'E'){
	    echo "<div class=pbheading>$songName <br> </div>\n";
	    echo "<div class=psubheading> <a href=\"$_Master_album_script?$mid\">$movieName</a> </div>";
	}
	else {
   	    $corvt = get_uc("$corvt",'');		
	    $vidvt = get_uc("$vidvt",'');
	    $mal_movieName = get_uc($movieName,'');
	    $mal_songName  = get_uc($songName,'');
	    echo "<div class=pbheading>$mal_songName <br> </div>\n";
	    echo "<div class=psubheading> <a href=\"$_Master_album_script?$mid\">$mal_movieName</a> </div>";
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
//	$kclipavail = mysql_result($result,$i,"S_KCLIP");
	if (file_exists("album_karaoke_clips/${sid}.mp3")){
		$kclipavail = 'L';
	}
	else if (file_exists("published_karaoke/${sid}.mp3")){
	    $kclipavail = 'Y';
	}
	else {
	    $kclipavail = 'N';
	}
	echo "<table class=ptables><tr><td valign=top width=50%>\n";
	$video = printAV ($sid,$clipavail);

	if ($video) {
	    if ($lyr_lock == 'L') {
		echo "<BR><div class=psubtitle>  <a href=\"$_Master_Managecomments_script?song_id=$sid&mode=album\"><img src=\"$corv\" class=\"tooltip\" title=\"$tt_details\" alt=\"$corvt\" height=20 border=0></a>  <a href=\"$_Master_Submitclips_script?sid=$sid&mode=ALBUMS\"> ";
		if ($clipavail != 'Y' && $clipavail != 'L'){
		    echo "<img src=\"$mus\" class=\"tooltip\" title=\"$tt_audio\" border=0 height=20></a>";
		}
		if ($kclipavail != 'Y' && $kclipavail != 'L'){
		    echo "<a href=\"$_Master_Submitkars_script?mode=ALBUMS&sid=$sid\"><img src=\"$kark\" class=\"tooltip\" title=\"$tt_kark\" border=0 height=20></a>";
		}
		echo "</div>";
	    }
	    else {
		echo "<BR><div class=psubtitle>  <a href=\"$_Master_Managecomments_script?mode=album&song_id=$sid\"><img src=\"$corv\" alt=\"$corvt\" class=\"tooltip\" title=\"$tt_details\" height=20 border=0></a>  ";
		if ($clipavail != 'Y' && $clipavail != 'L'){
		    echo "<a href=\"$_Master_Submitclips_script?sid=$sid&mode=ALBUMS\"> <img src=\"$mus\" class=\"tooltip\" title=\"$tt_audio\" border=0 height=20></a>";
		}
		if ($kclipavail != 'Y' && $kclipavail != 'L'){
		    echo "<a href=\"$_Master_Submitkars_script?mode=ALBUMS&sid=$sid\"><img src=\"$kark\" class=\"tooltip\" title=\"$tt_kark\" border=0 height=20></a>";
		}
               //<img src=\"$mus\" class=\"tooltip\" title=\"$tt_audio\"  border=0 height=20></a>  
               echo "<a href=\"$_Master_Managelyrics_script?mode=album&song_id=$sid&mode=album\"><img src=\"$lyrs\" class=\"tooltip\" title=\"$tt_lyrics\" height=20 border=0></a> <a href=\"asprint.php?$sid\" target=\"_new\"><img src=\"$prnt\" title=\"$tt_prnt\" border=0 height=20></a></div>";

	    }
	}
	else {
	    if ($lyr_lock == 'L'){
		echo "<P><div class=psubtitle> <a href=\"$_Master_Managecomments_script?song_id=$sid&mode=album\"><img src=\"$corv\" class=\"tooltip\" title=\"$tt_details\" alt=\"$corvt\" height=20 border=0></a> ";
	    }
	    else {
		echo "<P><div class=psubtitle>  <a href=\"$_Master_Managecomments_script?song_id=$sid&mode=album\"><img src=\"$corv\" alt=\"$corvt\" class=\"tooltip\" title=\"$tt_details\" height=20 border=0></a>   <a href=\"$_Master_Managelyrics_script?song_id=$sid&mode=album\"><img src=\"$lyrs\" class=\"tooltip\" title=\"$tt_lyrics\" height=20 border=0></a> <a href=\"asprint.php?$sid\" target=\"_new\"><img src=\"$prnt\" title=\"$tt_prnt\" border=0 height=20></a> ";
	    }

	    $vidlock = runQuery("SELECT UT_ID from ALBUM_UTUBE WHERE UT_ID=$sid and UT_STAT=\"Submitted\"",'UT_ID');
	    $audlock = runQuery("SELECT S_ID from ASONGS WHERE S_ID=$sid and S_CLIP=\"L\"",'S_ID');
	    if ($vidlock != $sid) {
		echo "<a href=\"$_Master_Submitvids_script?mode=ALBUMS&sid=$sid\"><img src=\"$vid\" alt=\"$vidvt\" class=\"tooltip\" title=\"$tt_video\" ></a>";
	    }

	    if ($audlock != $sid) {
		echo "<a href=\"$_Master_Submitclips_script?sid=$sid&mode=ALBUMS\"> ";
		if ($clipavail != 'Y' && $clipavail != 'L'){
		    echo "<img src=\"$mus\" class=\"tooltip\" title=\"$tt_audio\" border=0 height=20></a>";
		}
	    }

	    if ($kclipavail != 'Y' && $kclipavail != 'L'){
		echo "<a href=\"$_Master_Submitkars_script?mode=ALBUMS&sid=$sid\"> <img src=\"$kark\" border=0 height=20 class=\"tooltip\" title=\"$tt_kark\"></a>  \n";
	    }

	    echo "</div>";
	}

	$mus = mysql_result($result,$i,"S_MUSICIAN");
	$lyr = mysql_result($result,$i,"S_WRITERS");
	$sin = mysql_result($result,$i,"S_SINGERS");
	$rag = mysql_result($result,$i,"S_RAGA");
	$yr  = mysql_result($result,$i,"S_YEAR");
	$gen =  mysql_result($result,$i,"S_GENRE");
	echo "</td><td valign=top>";
	echo( "<table width=100%><tr>\n");
        printDetailHeadingRows ('Details','2');
	$icnt=0;
	if ($yr != 'Uncategorized' && $yr != ''){
	printDetailRows ('Year',$yr,'',$icnt++);
	 }
	printDetailRows ('Musician',$mus,'Musicians',$icnt++);
	printDetailRows ('Lyricist',$lyr,'Lyricists',$icnt++);
	printDetailRows ('Singers',$sin,'Singers',$icnt++);
	if ($rag != '' && $rag != 'Uncategorized'){
		printDetailRows ('Raga',$rag,'Ragas',$icnt++);
	}
	if ($gen && $gen != '*' && $gen != 'Select One'){
	    printDetailRows ('Genre',$gen,'',$icnt++);
	}
//	printDetailRows ('Actors',runQuery("SELECT UT_ACTORS FROM UTUBE where UT_ID=$sid",'UT_ACTORS'),'Actors');
        echo "</td></tr>";

        echo "<tr><td>&nbsp;</td><td valign=top>";
	echo "</td></tr></table>";
	printPicturesExtended ($mid,$mus,$lyr,$sin, 'Albums');
        echo "</td></tr></table>\n";
	print "<div class=ptables>\n";
        printSFB($sid,'Albums');
	echo "</div>";



	$hdr = "Lyrics";
	if ($_GET['lang'] != 'E'){
	    $hdr = get_uc($hdr,'');
	}

	echo( "<table class=ptables>\n");
        printDetailHeadingRows ("$hdr",'2');
	echo "</tr><tr><td valign=top class=cells>\n";

	echo "</tr><tr><td valign=top class=cells>\n";
	if ($lyr_lock != 'L') {
	    $hdr3 = "Change Lyrics";
	    if ($_GET['lang'] != 'E'){
		$hdr3 = get_uc("$hdr3",'');
	    }
	    if (file_exists ("$lyricsRoot/$sid.html")){
	        echo "<div class=nonselectable>\n";
		printLyricsContents("$lyricsRoot/$sid.html",$_GET['lang']);
		echo "</div>";
		echo "<P><a href=\"$_Master_Managelyrics_script?song_id=$sid&mode=album\">$hdr3</a><P>";
	    }
	    else {
		$hdr1 = "Lyrics Not Available";
		$hdr2 = "Click here to add";
		if ($_GET['lang'] != 'E'){
		    $hdr1 = get_uc("$hdr1",'');
		    $hdr2 = get_uc("$hdr2",'');
		}
		echo "$hdr1<p><a href=\"$_Master_Managelyrics_script?song_id=$sid&mode=album\">$hdr2</a><P>";
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



	echo( "<table class=ptables>\n");
	echo "<tr><td width=50% valign=top>";
	echo "<table width=100%>";
	$icnt=0;
        printDetailHeadingRows ('Contributors','2');
//	printDetailOwnerRows('Lyrics Owner', mysql_result($result,$i,"S_LYROWNER"),'UserPics');
	$uname = findUserFromComments('S_COMMENTS','ASONGS','S_ID',$sid);
	if ($uname){
	    printDetailOwnerRows('Details', $uname,'UserPics',$ic++);
	}
        printDetailOwnerRows('Lyrics Owner', findAllSongOwners ($sid,'S_LYROWNER','Albums') ,'UserPics',$icnt++);
	printDetailOwnerRows('Audio Clip', mysql_result($result,$i,"S_CLIPOWN"),'UserPics',$icnt++);
	printDetailOwnerRows ('Video Owner',runQuery("SELECT UT_OWN from ALBUM_UTUBE where UT_ID=$sid and UT_STAT=\"Published\"",UT_OWN), 'UserPics',$icnt++);
	$kown = runQuery("SELECT S_KCLIPOWN FROM ALBUM_KCLIPS WHERE S_ID=$sid",'S_KCLIPOWN');
	printDetailOwnerRows('Karaoke Owner', $kown,'UserPics',$ic++);
	echo ("</table>");
	echo( "</td><td valign=top><table width=100%>\n");
        printDetailHeadingRows ('Related Songs','2');
	printLinkedRows('From This Album', "$_Master_albumsonglist_script?mid=$mid&tag=Search",'',$icnt++);
	printLinkedRows("From This Team","$_Master_albumsonglist_script?tag=Search&musician=$mus&lyricist=$lyr&singers=$sin",'',$icnt++);
	printLinkedRows ("For This Year","$_Master_profile_script?category=year&artist=$yr",''.$icnt++);
	global $_RootofMSI;
	global $_GDMasterRootofMSI ;
	if (url_exists("$_RootofMSI/published_album_karaoke/${sid}.mp3")) { 
            printLinkedRows ("Karaoke Track For This Song","$_GDMasterRootofMSI/published_album_karaoke/${sid}.mp3",'_new',$icnt++);
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
    echo( "<table class=ptables><tr><td align=center class=fixedtiny>This page was generated on $today | <a href=\"SecureCache.php?type=_as.php&typename=AlbumSongs&id=$sid\">$regenMsg</a></td></tr>");
    echo ("<tr><td align=center><a href=\"edits/createAlbumSongIndex.php?sid=$sid&admin=1&showform=yes\"><img src=\"images/small_lock.jpg\" border=0></a> | <a href=\"admin/EditMasterAlbumLyricsPageDirect.php?encode=utf&sid=$sid&lang=eng\"><img src=\"images/lock-icon.gif\" border=0></a></td></tr></table>");


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

    global $_RootofMSI,$_RootDir;
    $_GDMasterRootofMSI = "http://msidb.info";
    $video = false;
    $avstring = "";
    $utube     = runQuery("SELECT UT_URL from ALBUM_UTUBE WHERE UT_ID=$sid AND UT_STAT='Published'",'UT_URL');
    $utubevals = explode ('=',$utube);
    if ($utubevals[1] != "") {
	$utlink  = "http://gdata.youtube.com/feeds/api/videos/$utubevals[1]";
	if (checkYoutubeId("$utlink")){
	    $video = true;
	    $avstring = "<iframe width=\"400\" height=\"225\" src=\"http://www.youtube.com/embed/${utubevals[1]}\" frameborder=\"0\" allowfullscreen></iframe>";
	}
    }
    if ($clip == 'Y'){
	global $_playerLoc;
        $songFile  = findRoot($sid,'Audio','Albums') . '/' . "${sid}.mp3";
        $songLoc   = str_replace("$_RootofMSI","$_RootDir",$songFile);
        if (filesize($songLoc) < 1) {
            $songFile   = str_replace("$_RootofMSI","$_GDMasterRootofMSI",$songFile);
        }

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
	    $avstring .= "<object type=\"application/x-shockwave-flash\" data=\"$_playerLoc/player.swf\" id=\"audioplayer1\" autostart=\"true\" height=\"24\" width=\"290\">\n";
	    $avstring .= "<param name=\"movie\" value=\"$_playerLoc/player.swf\">\n";
	    $avstring .= "<param name=\"FlashVars\" value=\"playerID=1&amp;autostart=1&amp;soundFile=$songFile\">\n";
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



?>
