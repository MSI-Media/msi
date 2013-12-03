<?php session_start();

{
    error_reporting (E_ERROR);
    $_GET['lang'] = $_SESSION['lang'];
    require_once("includes/utils.php");
    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("includes/cache.php");




//   $ch = new cache($_GET['lang'],'Movies');
   

    $_GET['encode']='utf';

    $cLink = msi_dbconnect();
    printXHeader('Popup');

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
        if ($_GET['lang'] == 'E'){
	    printContents("Writeups/MissingMovieEnglish.html");
	}
	else {
	    printContents("Writeups/MissingMovie.html");
	}
    }
    else {
	$i=0;


	while ($i < $num_results){
	    
	    $movieName = mysql_result($result,$i,"MOVIES.M_MOVIE");
	    
	    $full_details = "Click here for the Complete Details About The Movie";
	    $myear = mysql_result($result,$i,"MOVIES.M_YEAR");
	    if ($_GET['lang'] != 'E'){
                $movieName = get_uc($movieName,'');
		$full_details = get_uc($full_details,'');
	    }
             echo "<div class=pheading><a href=\"findmp.php?mid=$mid&mode=prev\"><img src=\"images/br_prev.png\"></a>&nbsp; $movieName ($myear) &nbsp;<a href=\"findmp.php?mid=$mid&mode=next\"><img src=\"images/br_next.png\"></a></div>";
	    echo "<div class=psubtitle><a href=\"m.php?$mid\">$full_details</a></div>";
            echo( "<table class=ptables>\n");
            printDetailHeadingRows ('Still Photographs and Posters','4');
            echo "<tr><td valign=top>\n";
	    printAllPictures ($mid,$movieName);
            echo "</td></tr><tr><td valign=top>\n";
	    //echo( "<table class=ptables>\n");
	    printDetailHeadingRows ('Videos','4');
            printVideos($mid);
	    //echo ("</table>");
            echo ("</td>");
            echo ("</tr></table>");
	    $i++;
	}
    }

    
    $regenMsg = "Regenerate This Page";
    if ($_GET['lang'] != 'E') { $regenMsg = get_uc($regenMsg,''); }
    $today = date("F j, Y, g:i a T");
    echo( "<div align=center class=fixedtiny>This page was generated on $today | <a href=\"SecureCache.php?type=mp.php&typename=Movies&id=$mid\">$regenMsg</a></div>");
    printFancyFooters();
    mysql_close($cLink);
//    $ch->close();
 
}
function printAllPictures($mid,$movn){
   $pictures = array();
   if (file_exists("moviepics/${mid}.jpg")){
       array_push ($pictures,"moviepics/${mid}.jpg");
   }
   $picPath2 = "_PhotosfrmBlog";
   $picPath2URL = "_PhotosfrmBlog";

    if (file_exists("$picPath2/${mid}.jpg")){
	array_push($pictures,"$picPath2URL/${mid}.jpg");
    }
    foreach (range(0,9) as $number) {
  
       if (file_exists("$picPath2/${mid}_$number.jpg")){
         array_push($pictures,"$picPath2URL/${mid}_$number.jpg");
      } 
    }

   echo "<tr>";
   $cnt=0;
   foreach ($pictures as $img){
     
      if (file_exists ("$img")){
          $cnt++;
	  echo "<td align=center><a href=\"$img\" toptions=\"group=links,effect=fade\" title=\"$movn\"><img class=preview src=\"$img\" height=100 border=0></a></td>\n";
	  if ($cnt > 2){
	      $cnt=0;
	      echo "</tr><tr bgcolor=#ffffff>";
	  }
      }
   }
   if ($img == ''){
      $nomsg = 'No Stills and Posters are available for this movie. Please click here to submit';
      if ($_GET['lang'] != 'E') { $nomsg = get_uc($nomsg,''); }
      echo "<td class=pcells><a href=\"submitPictures.php?mid=$mid\">$nomsg</a></td>";
   }
   echo "</tr>";


}
function printVideos($mid){
   $songs = buildArrayFromQuery("SELECT S_ID FROM SONGS WHERE M_ID=$mid",'S_ID');
   echo "<tr>";
   $cnt=0;
   $masterav='';
   foreach ($songs as $sid) {
       $avstring = getAV ($sid);
       if ($avstring != ''){
           $infostring = getInfoString($sid);
           echo "<td align=center>$avstring<br>$infostring</td>";
           $cnt++;
           if ($cnt > 2) { $cnt = 0; echo "</tr><tr>"; }
           $masterav .= $avstring;
       }

       $avstring='';$infostring='';
  
   }
      if ($masterav == ''){
      $nomsg = 'No Videos are available for this movie. Please click here to submit';
      if ($_GET['lang'] != 'E') { $nomsg = get_uc($nomsg,''); }
      echo "<td class=pcells><a href=\"m.php?$mid\">$nomsg</a></td>";
   }
   


   echo "</tr>";
 //  echo "</tr></table>";
}
function getInfoString($sid)
{
    $query = "SELECT S_SONG,S_MUSICIAN,S_WRITERS,S_SINGERS FROM SONGS WHERE S_ID=$sid";
    $result     = mysql_query($query);
    $num_results=mysql_num_rows($result);
    $i=0;
    if ($num_results> 0){

          $song = mysql_result($result,$i,"S_SONG");
          $music = mysql_result($result,$i,"S_MUSICIAN");
          $lyrics = mysql_result($result,$i,"S_WRITERS");
          $singers = mysql_result($result,$i, "S_SINGERS");
          $i++;
    }
    if ($_GET['lang'] != 'E'){
           $song = get_uc($song,'');
           $music = get_uc($music,'');
           $lyrics = get_uc($lyrics,'');
           $singers = get_uc($singers,'');
    }
    return "<div align=center><a href=\"s.php?$sid\">$song</a><br>$music | $lyrics | $singers</div>";
}
function getAV($sid)
{
    global $_MasterRootDir;
    $_GDMasterRootofMSI = "http://msidb.info";
    $video = false;
    $avstring = "";
    $utube     = runQuery("SELECT UT_URL from UTUBE WHERE UT_ID=$sid AND UT_STAT='Published'",'UT_URL');
    $utubevalsx = explode ('&',$utube);
    $utubevals  = explode ('=',$utubevalsx[0]);
    if ($utubevals[1] != "") {
	$utlink  = "http://gdata.youtube.com/feeds/api/videos/$utubevals[1]";
	if (checkYoutubeId("$utlink")){
	    $avstring = "<iframe width=\"280\" height=\"170\" src=\"http://www.youtube.com/embed/$utubevals[1]?wmode=transparent\" frameborder=\"0\" allowfullscreen></iframe>\n";
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
	      $avstring .= "<a href=\"$video_url\" style=\"display:block;width:280px;height:170px;\"  id=\"player${sid}\"></a>\n";
	      $avstring .= "<script>flowplayer(\"player${sid}\", {src:\"fplayer/flowplayer-3.2.8.swf\", wmode: \"transparent\"}, {      clip:  {          autoPlay: false,          autoBuffering: true      }  });</script>\n";
	   }
      }
}


    return $avstring;
}


?>
