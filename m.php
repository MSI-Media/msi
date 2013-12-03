<?php session_start();

{
    error_reporting (E_ERROR);
    $_GET['lang'] = $_SESSION['lang'];
    require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("includes/cache.php");


/*
    if (isNonAdminUser()){    
	$ch = new cache($_GET['lang'],'Movies');
    }
*/
    $_GET['encode']='utf';
    $cLink = msi_dbconnect();
    printXheader('Popup');


    $query = '';
    $movie = $_GET['movie'];
    $year  = $_GET['year'];
    if ($movie == "" || $year == ""){
        $mid  = $_GET['mid'];
    	$lang = $_GET['lang'];
	if (!$mid){
	    $mids = explode('&',$_SERVER['QUERY_STRING']);
	    $mid = $mids[0];
	}
    }
    else {
	$mid = runQuery("SELECT M_ID from MOVIES WHERE M_MOVIE=\"$movie\" and M_YEAR=\"$year\"",'M_ID');
    }

    $query      = "SELECT * FROM MOVIES,MDETAILS WHERE MOVIES.M_ID=$mid and MDETAILS.M_ID=$mid";


    $result     = mysql_query($query);
    $num_results=mysql_num_rows($result);
    $i=0;
    if ($num_results == 0){
	echo "<div align=center>\n";
        if ($_GET['lang'] == 'E'){
	    printContents("Writeups/MissingMovieEnglish.html");
	}
	else {
	    printContents("Writeups/MissingMovie.html");
	}
	echo "</div>";
    }
    else {
	$i=0;
	$corv = "images/icon-edit.gif";
	$picv = "icons/Movie.png";
	$revv = "icons/Notebook.png";
	$promov = "icons/Picture.png";

	$corvt = "Change the Details on this Page";
	$picvt = "Add a Picture of This Movie";

	while ($i < $num_results){
	    
	    $movieName = mysql_result($result,$i,"MOVIES.M_MOVIE");
	    $mal_movieName = get_uc($movieName,'');
	    $myear = mysql_result($result,$i,"MOVIES.M_YEAR");
            //$submissionMessage = file_get_contents("Writeups/movieSubmissionMessage.txt");
            $stillvideos = 'Stills Posters Videos from this Movie';
	    if ($_GET['lang'] == 'E'){
		if ($myear > 0){ 
		    echo "<div class=pheading>$movieName ($myear) </div>";
		}
		else {
		    echo "<div class=pheading>$movieName</div>";
		}
                //$submissionMessage = file_get_contents("Writeups/movieSubmissionMessageEnglish.txt");
	    }
	    else {
		$corvt = get_uc("$corvt",'');
		$picvt = get_uc("$picvt",'');
		if ($myear > 0) { 
		    echo "<div class=pheading>$mal_movieName ($myear) </div>";
		}
		else {
		    echo "<div class=pheading>$mal_movieName</div>";
		}
	    }
            

	$tt_details = "Change Details";
	$tt_reviews = "Add a Review for this movie";
	$tt_picture = "Add a Poster or Picture from this movie";
	$tt_promo = "Add Promotional Materials for This Movie";

	//$submissionMessage = file_get_contents("Writeups/songSubmissionMessage.txt");
	if ($_GET['lang'] != 'E'){
	    $tt_details = get_uc($tt_details,'');
	    $tt_reviews = get_uc($tt_reviews,'');
	    $tt_picture = get_uc($tt_picture,'');
	    $tt_promo = get_uc($tt_promo,'');
            $stillvideos = get_uc($stillvideos,'');
        }
	displayRatings($mid);
        $sbook = "Song Book";	
	 if ($_GET['lang'] != 'E'){
	$sbook = get_uc($sbook,'');
	  }
	    $pmov = runQuery("SELECT P_MOVIE FROM PPUSTHAKAM where P_ID=$mid",'P_MOVIE');
	    $_pmov = str_replace(" ","_",$pmov);
	    if ($pmov != "" && file_exists("ppusthakam/$pmov")){
		$pp_str = "<a href=\"$_Master_Songbook_script?movie=$pmov&movn=$mmov&mid=$mid\">$sbook</a>";
	    }
	    else if (file_exists("ppusthakam/$_pmov") && $_pmov != ""){
		$pp_str = "<a href=\"$_Master_Songbook_script?movie=$_pmov&movn=$mmov&mid=$mid\">$sbook</a>";
	    }


	if ($pp_str) {
	    echo "<div class=psubtitle>$pp_str</div>";
	}
/*
	else {
	    echo "<div class=psubtitle><a href=\"_mp.php?$mid\">$stillvideos</a></div>";
	}
*/

            if (file_exists("moviepics/${mid}.jpg") || file_exists("uploaded_pictures/${mid}.jpg")){
        	echo "<div class=psubtitle> $submissionMessage <a href=\"$_Master_movie_edit?display=Change&id=$mid\"><img src=\"$corv\" class=\"tooltip\" title=\"$tt_details\" height=20  border=0></a> <a href=\"$_Master_SubmitTrailers_script?$mid\"><img src=\"$promov\" class=\"tooltip\" title=\"$tt_promo\" height=20  border=0></a>
  <a href=\"mailto:msiadmins@googlegroups.com?Subject=MSI 2012 [msidb.org] Additional Pictures for $movieName ($mid)\"><img src=\"$picv\" class=\"tooltip\" title=\"$tt_picture\" border=0 height=20></a> <a href=\"$_Master_SubmitReviews_script?mid=$mid\"><img src=\"$revv\" border=0 height=20 class=\"tooltip\" title=\"$tt_reviews\"></a> </div><P>";
            }
            else {
        	echo "<div class=psubtitle> $submissionMessage <a href=\"$_Master_movie_edit?display=Change&id=$mid\"><img src=\"$corv\" class=\"tooltip\" title=\"$tt_details\" height=20 border=0></a> <a href=\"$_Master_SubmitTrailers_script?$mid\"><img src=\"$promov\" class=\"tooltip\" title=\"$tt_promo\" height=20  border=0></a>
  <a href=\"$_Master_SubmitPictures_script?mid=$mid\"><img src=\"$picv\" class=\"tooltip\" title=\"$tt_picture\" border=0 height=20></a> <a href=\"$_Master_SubmitReviews_script?mid=$mid\"><img src=\"$revv\" border=0 height=20 class=\"tooltip\" title=\"$tt_reviews\"></a> </div><P>";
            }

//	    $vid_pid = runQuery("SELECT P_ID FROM PROMOS WHERE P_ID=$mid and P_STAT=\"Published\" limit 1",'P_ID');		

            echo( "<table class=ptables>\n");

	    if (checkPromosVideos ($mid)){	    
		echo "<tr><td align=center>\n";
		printConsolidatedPictures ($mid,'Movies',$movieName,$mal_movieName);
		printPromos ($mid);	    
		echo "</td><td>\n";
		printPromosVideos ($mid);	    
		echo "</td></tr>\n";
	    }
	    else {
		echo "<tr><td align=center colspan=2>\n";
		printConsolidatedPictures ($mid,'Movies',$movieName,$mal_movieName);
		printPromos ($mid);	    
		echo "</td></tr>\n";
	    }
	    echo "<tr>";
            echo "<td valign=top colspan=2>\n";

	    echo( "<table class=ptables>\n");
            $mstat = mysql_result($result,$i,"MOVIES.M_COMMENTS");
            if ($mstat == 'Dubbed' || $mstat == 'Dubbing') { $mstat = 'Dubbed';} else if ($mstat == '*') { $mstat = 'Unreleased'; }  else if ($mstat == 'Pre') { $mstat = 'In Production'; } else { $mstat = ''; }
	    printDetailMovieHeadingRows ($mstat,'Details','2');
	    $icnt=$i;
	    printDetailRows ('Producer',mysql_result($result,$i,"MDETAILS.M_PRODUCER"),'Producers',$icnt++);
	    printDetailRows ('Director',mysql_result($result,$i,"MOVIES.M_DIRECTOR"),'Directors',$icnt++);
	    printDetailRows ('Actors',mysql_result($result,$i,"MDETAILS.M_CAST"),'Actors',$icnt++);
	    printDetailRows ('Musician',mysql_result($result,$i,"MOVIES.M_MUSICIAN"),'Musicians',$icnt++);
	    printDetailRows ('Lyricist',mysql_result($result,$i,"MOVIES.M_WRITERS"),'Lyricists',$icnt++);
	    printDetailRows ('Singers',getDistinctSingers ($mid), 'Singers',$icnt++);
	    printDetailRows ('Background Music',mysql_result($result,$i,"MDETAILS.M_BGM"),'Musicians',$icnt++);
	    printDetailRows ('Banner',mysql_result($result,$i,"MDETAILS.M_BANNER"),'Banners',$icnt++);
	    printDetailRows ('Distribution',mysql_result($result,$i,"MDETAILS.M_DISTRIBUTION"),'Distribution',$icnt++);
	    printDetailRows ('Story',mysql_result($result,$i,"MDETAILS.M_STORY"),'Screenplay',$icnt++);
	    printDetailRows ('Screenplay',mysql_result($result,$i,"MDETAILS.M_SCREENPLAY"),'Screenplay',$icnt++);
	    printDetailRows ('Dialog',mysql_result($result,$i,"MDETAILS.M_DIALOG"),'Screenplay',$icnt++);
	    printDetailRows ('Editor',mysql_result($result,$i,"MDETAILS.M_EDITOR"),'Editors',$icnt++);
	    printDetailRows ('Art Director',mysql_result($result,$i,"MDETAILS.M_ART"),'Art',$icnt++);
	    printDetailRows ('Camera',mysql_result($result,$i,"MDETAILS.M_CAMERA"),'Camera',$icnt++);
	    printDetailRows ('Design',mysql_result($result,$i,"MDETAILS.M_DESIGN"),'Design',$icnt++);
	    $dateofrelease = mysql_result($result,$i,"MDETAILS.M_PRODEXEC");
	    printDetailRows ('Date of Release',$dateofrelease,'',$icnt++);
	    $numSongs = runQuery("SELECT COUNT(S_ID) ccn from SONGS WHERE M_ID=$mid",'ccn');
	    printDetailRows ('Number of Songs',$numSongs,'',$icnt++);
	    echo ("</table>");
/*
            echo ("</td><tr><td>&nbsp;</td><td>");
            printFB($mid,'');
*/
            echo ("</td></tr>");
            echo ("</table>");
            printFB($mid,'');
	    printSongs($mid,'Movies');
	    printNewReviews($mid);
	    printContributors($mid,'Movies');
	    $i++;
	}
    }

    
    $regenMsg = "Regenerate This Page";
    if ($_GET['lang'] != 'E') { $regenMsg = get_uc($regenMsg,''); }
    $today = date("F j, Y, g:i a T");
    echo( "<table class=ptables><tr><td align=center class=fixedtiny>This page was generated on $today | <a href=\"SecureCache.php?type=$_Master_movie_script&typename=Movies&id=$mid\">$regenMsg</a> | <a href=\"http://msidb.org/admin/UpdateMovie.php?id=$mid\"><img src=\"images/small_lock.jpg\" border=0></a></td></tr></table>");

//    echo( "<div class=fixedtiny>This page was generated on $today | <a href=\"SecureCache.php?type=$_Master_movie_script&typename=Movies&id=$mid\">$regenMsg</a> | <a href=\"http://msidb.org/admin/UpdateMovie.php?id=$mid\"><img src=\"images/small_lock.jpg\" border=0></a></div>");
    printFancyFooters();
    mysql_close($cLink);
/*
    if (isNonAdminUser()){
	$ch->close();
    }
*/
}

function checkPromosVideos ($mid){
    $qryP         = "SELECT * FROM PROMOS WHERE P_ID=$mid and P_STAT=\"Published\" limit 1";
    $resultP      = mysql_query($qryP);
    $num_resultsP = mysql_num_rows($resultP);    
    $i=0;
    $avstring = "";
    if ($num_resultsP > 0) { 
	while ($i < $num_resultsP){
	    $vid  = mysql_result($resultP,$i,"P_VIDEO");
	    if ($vid != ''){
		$utubevalsx = explode ('&',$vid);
		$utubevals  = explode ('=',$utubevalsx[0]);
		if ($utubevals[1] != "") {
		   $utlink  = "http://gdata.youtube.com/feeds/api/videos/$utubevals[1]";
	           if (checkYoutubeId("$utlink")){
 		      return 1;
                   }
		}
	    }
	    $i++;
	}
    }
    return 0;
}

function printPromosVideos ($mid){
    $qryP         = "SELECT * FROM PROMOS WHERE P_ID=$mid and P_STAT=\"Published\" limit 1";
    $resultP      = mysql_query($qryP);
    $num_resultsP = mysql_num_rows($resultP);    
    $i=0;
    $avstring = "";
    if ($num_resultsP > 0) { 
	while ($i < $num_resultsP){
	    $vid  = mysql_result($resultP,$i,"P_VIDEO");
	    if ($vid != ''){
		$utubevalsx = explode ('&',$vid);
		$utubevals  = explode ('=',$utubevalsx[0]);
		if ($utubevals[1] != "") {
		   $utlink  = "http://gdata.youtube.com/feeds/api/videos/$utubevals[1]";
	           if (checkYoutubeId("$utlink")){
 		      $avstring .= "<iframe width=\"400\" height=\"225\" src=\"http://www.youtube.com/embed/$utubevals[1]?wmode=transparent\" frameborder=\"0\" allowfullscreen></iframe>";
                   }
		}
	    }
	    $avstring .= '<BR>';
	    $i++;
	}
    }
    $avstring .= "";
    echo $avstring, "<BR>";
}

function printConsolidatedPictures ($mid,$tag,$mov,$mmov){

    global $_MasterRootDir;
    $_GDMasterRootofMSI = "http://msidb.info";
    $picPath1  = "moviepics";
    $picPath2 = "$_MasterRootDir/_PhotosfrmBlog";
    $picPath2URL = "$_MasterRootofMSI/_PhotosfrmBlog";

    if ($tag == 'Albums'){
        $picPath1  = "albumpics";	
    }
    $pic_array = array();

    if (file_exists("$picPath1/${mid}.jpg")){
	array_push($pic_array,"$picPath1/${mid}.jpg");
    }

    if (file_exists("$picPath2/${mid}.jpg")){
	array_push($pic_array,"$picPath2URL/${mid}.jpg");
    }
    foreach (range(0,20) as $number) {
       if (file_exists("$picPath2/${mid}_$number.jpg")){
         array_push($pic_array,"$picPath2URL/${mid}_$number.jpg");
      } 
    }
//    echo "<div class=pheading>\n";
    if ($pic_array[0] != ""){
	foreach ($pic_array as $pics){
	    echo "<a href=\"$pics\" class=\"preview\" style=\"border:1px solid #fff;\" src=\"$vals[3]\"  ><img src=\"$pics\" border=0 height=100 width=100 onclick=\"javascript:return false;\" onmousedown=\"if(event.button==2){return false;}\">\n";
	}
    }
//    echo "</div>\n";
}

function printPromos ($mid){
    $qryP         = "SELECT * FROM PROMOS WHERE P_ID=$mid and P_STAT=\"Published\" limit 1";
    $resultP      = mysql_query($qryP);
    $num_resultsP = mysql_num_rows($resultP);    
    $i=0;
    $avstring = "";//<div align=center>";
    if ($num_resultsP > 0) { 
	while ($i < $num_resultsP){
	    $wiki = mysql_result($resultP,$i,"P_WIKI");
	    $web  = mysql_result($resultP,$i,"P_WEB");
	    $fb   = mysql_result($resultP,$i,"P_FACE");

	    $avstring .= '<BR>';
	    $wiki_png = "icons/wikipedia-icon.png";
	    $web_png = "icons/firefox-icon.png";
	    $fb_png = "icons/facebook-icon.png";
	    $wikimsg = "Wikipedia Entry";
	    $webmsg = "Official Website";
	    $fbmsg = "Facebook Page";
	    if ($_GET['lang'] != 'E'){
		$webmsg = get_uc($webmsg,'');
		$wikimsg = get_uc($wikimsg,'');
		$fbmsg = get_uc($fbmsg,'');
	    }
	    if ($wiki != ''){
		$avstring .= "<a href=\"$wiki\" target=\"_new\"><img src=\"$wiki_png\" class=\"tooltip\" title=\"$wikimsg\" border=0 height=40></a>";
	    }
	    if ($web != ''){
		$avstring .= "<a href=\"$web\" target=\"_new\"><img src=\"$web_png\" class=\"tooltip\" title=\"$webmsg\" border=0 height=40></a>";
	    }
	    if ($fb != ''){
		$avstring .= "<a href=\"$fb\" target=\"_new\"><img src=\"$fb_png\" class=\"tooltip\" title=\"$fbmsg\" border=0 height=40></a>";
	    }
	    $i++;
	}
    }
//    $avstring .= "</div>";
    $avstring .= "";
   echo $avstring, "<BR>";
}

function getDistinctSingers($mid){
    $singers = array();

    foreach (buildArrayFromQuery("SELECT DISTINCT S_SINGERS FROM SONGS WHERE M_ID=\"$mid\"",'S_SINGERS') as $sns){
	$sns_array = explode(',',$sns);
	foreach ($sns_array as $sn){
	    $sn = trim($sn);
	    if ($sn != "Chorus" && $sn != "-" && $sn != "Insrtumental") {
		if (!in_array("$sn",$singers)){
		    array_push ($singers,"$sn");
		}
	    }
	}
    }
    sort($singers);
    return implode (',',$singers);
}
function displayRatings($mid){

    $rating = runQuery("SELECT rating from RATINGS WHERE mid=$mid",'rating');
    if ($rating != ''){
	echo "<div align=center>\n";
	echo "<span class=\"rating-static rating-${rating}\"></span></div>";
    }
}

function printNewReviews ($mid){

    $revPath  = "reviews";
    $srevPath = "sreviews";

    $rev_array = array();

    if (file_exists("$revPath/${mid}.html")){
	array_push($rev_array,"$revPath/${mid}.html");
    }
    if (file_exists("$revPath/${mid}.txt")){
	array_push($rev_array,"$revPath/${mid}.txt");
    }

    $hdr = "Storyline";
	if ($_GET['lang'] != 'E'){
	    $hdr = get_uc($hdr,'');
	}

    $oig_link = runQuery("SELECT M_URL FROM MD_LINKS WHERE M_URL like '%oldmalayalam%' and M_ID=$mid limit 1",'M_URL');
    
    if ($rev_array[0] != '' || $oig_link != ''){
	echo "<table class=ptables width=95%><tr class=tableheader><td style=\"font-size:12pt;font-family:Lucida Sans;font-weight:bold;text-align:left;\" >$hdr</td></tr>";
	echo "<tr><td>\n";
    }

    foreach ($rev_array as $revs){
	echo "<div style=\"padding-left:50px;font-size:11pt;font-family:Lucida Sans;font-family:\"HelveticaNeue-Light\", \"Helvetica Neue Light\", \"Helvetica Neue\", Helvetica, Arial, \"Lucida Grande\", sans-serif;text-align:left;\"";
	printXContents("$revs");
	echo "</div>";
    }


    if ($oig_link != ''){
	$old_is_gold_title = "Old is Gold by B Vijayakumar";
	if ($_GET['lang'] != 'E'){
	    $old_is_gold_title = get_uc($old_is_gold_title,'');
	}
	echo "<a href=\"$oig_link\" target=\"_new\"> $old_is_gold_title </a>";
    }

/*
    echo "<P>";
    $revm = "Add or Update Review and Synopsis";
    if ($_GET['lang'] != "E"){
	$revm = get_uc("$revm",'');
    }
    echo "<div class=subtitle>\n";
    if ($rev_array[0] == ""){
	if ($_GET['lang'] != 'E'){
	    $hdr1 = get_uc("Review Not Available",'');
	}
	echo "$hdr1<P>";
    }
    echo "<a href=\"submitReviews.php?mid=$mid\">$revm</a></div>\n";
*/

    $exts = array('txt','html');
    if (file_exists("$srevPath/${mid}.txt") || file_exists("$srevPath/${mid}.html")){
        $shdr = "Songs Review";	
	if ($_GET['lang'] != 'E'){
	    $shdr = get_uc($shdr,'');
	}
	echo "<table class=ptables width=95%><tr class=tableheader><td style=\"font-size:13pt;font-family:Lucida Sans;font-weight:bold;text-align:left;\" >$shdr</td></tr>";
	echo "<tr><td class=ptextleft>\n";
	foreach ($exts as $ext) {
	    echo "<div style=\"padding-left:50px;font-size:11pt;font-family:Lucida Sans;font-style:italic;text-align:left;\" >\n";
	    printContents("$srevPath/${mid}.${ext}");
	    echo "</div>\n";
	}
    }




    echo "</td></tr></table>";
}
?>
