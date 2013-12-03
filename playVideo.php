<?php
    error_reporting (E_ERROR);
    $_GET['lang'] = $_SESSION['lang'];
    require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("includes/cache.php");

    $cLink = msi_dbconnect();
    echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
    echo "<head>\n";
    echo "    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
    echo "    <link rel=\"stylesheet\" href=\"./msi2013_setup/style.css\" type=\"text/css\" media=\"all\">\n";
    echo "<script type=\"text/javascript\" src=\"fplayer/flowplayer-3.2.8.min.js\"></script>\n";
    echo "</head>\n";
    echo "<aside class=\"body_wrapper\">\n";
    echo "  <div class=\"main\">\n";

$type = $_GET['type'];
$sid  = $_GET['id'];

global $_MasterRootDir;
global $_RootofMSI,$_RootDir;
$_GDMasterRootofMSI = "http://msidb.info";
if ($type == 'm') { $mode = 'Movies'; $smode = 'Movies';}
else { $mode = 'Albums'; $smode = 'Album';}
$songstring = getSongString ($sid, $smode);

$songFile  = findRoot($sid,'Audio',$mode) . '/' . "${sid}.mp3";
$songLoc   = str_replace("$_RootofMSI","$_RootDir",$songFile);
if (filesize($songLoc) < 1) {
    $songFile   = str_replace("$_RootofMSI","$_GDMasterRootofMSI",$songFile);
    $songFile   = str_replace("http://en.msidb.org","$_GDMasterRootofMSI",$songFile);
    $songFile   = str_replace("http://ml.msidb.org","$_GDMasterRootofMSI",$songFile);
    
}
echo "</div>";
echo "<table width=100%>\n";
echo "<tr><td align=center width=100%>$songstring</td></tr>";
echo "<tr><td align=center width=100%>\n";
printVideo($sid,$mode);
echo "</td></tr></table>\n";

    echo "</div>";
    echo "</aside>\n";
    mysql_close($cLink);


function printVideo($sid,$mod)
{
 
    $ut_table = 'UTUBE';
    if ($mod == 'Albums') { $ut_table = 'ALBUM_UTUBE'; }
    global $_MasterRootDir;
    global $_RootofMSI,$_RootDir;
    $_GDMasterRootofMSI = "http://msidb.info";
    $video = false;
    $avstring = "";
    $utube     = runQuery("SELECT UT_URL from $ut_table WHERE UT_ID=$sid AND UT_STAT='Published'",'UT_URL');
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
        // ============================================================
        // --- InMotion Site Optimization Related Changes
        // ============================================================

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
    echo "</div>";

    echo "<div class=pheading>$avstring</div>";
    return $video;
}
?>
