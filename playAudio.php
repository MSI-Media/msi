<?php
    error_reporting (E_ERROR);
    $_GET['lang'] = $_SESSION['lang'];
    require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("_includes/_System.php");
    require_once("includes/cache.php");

    $cLink = msi_dbconnect();
    echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
    echo "<head>\n";
    echo "    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
    echo "    <link rel=\"stylesheet\" href=\"./msi2013_setup/style.css\" type=\"text/css\" media=\"all\">\n";
    echo "</head>\n";
    echo "<aside class=\"body_wrapper\">\n";
    echo "  <div class=\"main\">\n";

$type = $_GET['type'];
$sid  = $_GET['id'];

global $_MasterRootDir;
global $_RootofMSI,$_RootDir;
global $_playerLoc; //          = "$_RootofMSI/published_clips";
$_GDMasterRootofMSI = "http://msidb.info";

 $mode = 'Movies'; $smode = 'Movies';
if ($type == 'a') { $mode = 'Albums'; $smode = 'Album';}
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
    echo "<audio controls height=\"100\" width=\"100\">\n";
    echo "<source src=\"$songFile\" type=\"audio/mpeg\">\n";
    echo "<embed height=\"50\" width=\"100\" src=\"$songFile\">\n";
    echo "</audio>\n";
}
else {
    echo "<script language=\"JavaScript\" src=\"$_playerLoc/audio-player.js\"></script>\n";
    echo "<object type=\"application/x-shockwave-flash\" data=\"$_playerLoc/player.swf\" id=\"audioplayer1\" height=\"24\" width=\"400\">\n";
    echo "<param name=\"movie\" value=\"$_playerLoc/player.swf\">\n";
    echo "<param name=\"FlashVars\" value=\"playerID=1&amp;soundFile=$songFile\">\n";
    echo "<param name=\"quality\" value=\"high\">\n";
    echo "<param name=\"menu\" value=\"false\">\n";
    echo "<param name=\"wmode\" value=\"transparent\">\n";
    echo "</object>\n";
}
echo "</td></tr></table>\n";

    echo "</div>";
    echo "</aside>\n";
    mysql_close($cLink);

?>
