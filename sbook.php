<?php
if (!isset($_SESSION)) {
session_start();
}
    error_reporting (E_ERROR);
    require_once("includes/utils.php");
    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_moviePageUtils.php");



    $cLink = msi_dbconnect();
    printXHeader('Popup');

$movie = $_GET['movie'];
$movn  = $_GET['movn'];
$mid   = $_GET['mid'];

$_SERVER['QUERY_STRING'] = str_replace('&cl=1','',$_SERVER['QUERY_STRING']);
if (!$mid){
 $mid = $_SERVER['QUERY_STRING'];
}
$_GET['lang'] = $_SESSION['lang'];

if (!$movie){
$movie = runQuery("SELECT P_MOVIE from PPUSTHAKAM WHERE P_ID=$mid","P_MOVIE");
}
//$_MasterRootDir     = "/home/msidbo6/public_html";
global $_MasterRootDir;
if (!file_exists("$_MasterRootDir/ppusthakam/$movie")){
	$movie=str_replace(" ","_",$movie);
}

$movie_name = $movie;
$hdrtag = 'Song Book Pages';
$movtag = 'Movie';
$dettag = 'Details';
$msgtag = 'Please click on the images to read the Song Book';
$realname =  runQuery("SELECT M_MOVIE from MOVIES WHERE M_ID=$mid","M_MOVIE");
$movie_name = $realname;
if ($_GET['lang'] != 'E'){
$hdrtag = get_uc("$hdrtag",'');
$movtag = get_uc("$movtag",'');
$dettag = get_uc("$dettag",'');
$msgtag = get_uc("$msgtag",'');
if ($movn == ''){
 $movn = get_uc("$realname",'');
}
if ($movn != '') {
$movie_name = $movn;
}
}
if ($movie_name == '' && $movie != '') { $movie_name = $movie; }

echo "<div class=pheading>$movtag : $movie_name</div>\n";
echo "<div class=psubheading>$hdrtag</div>\n";
echo "<table class=ptables>\n";
/*
echo "<tr ><td align=center valign=top class=pheading colspan=4>  $hdrtag  </td></tr>\n";
echo "<tr ><td align=center valign=top class=pheading colspan=4>  ", $movtag, " : $movie_name</td></tr>\n";
*/
echo "<tr ><td align=center valign=top class=pcellheadscenter colspan=4> <a href=\"$_Master_movie_script?$mid\">", $dettag, "</a></td></tr>\n";
echo "<tr ><td align=center valign=top class=pcells colspan=4> $msgtag </td></tr>\n";

echo "<tr ><td valign=top>\n";
$dir = opendir ("$_MasterRootDir/ppusthakam/$movie");
$images=array();
while ($fh = readdir ($dir)){
    array_push($images,$fh);
}

sort ($images);
$cnt=0;
echo "<tr bgcolor=#ffffff>\n";
foreach ($images as $img){
  $ic1 = strpos ($img, "_");
  if ($ic1 == false && $img != "." && $img != ".." && $img != "Thumbs.db" && $img != "index.html" && $img != "index.html~"){
      $cnt++;
      if (file_exists ("$_MasterRootDir/ppusthakam/$movie/$img")){
	  $img = str_replace(" ","%20",$img);
	  echo "<td align=center><a href=\"$_MasterRootofMSI/ppusthakam/$movie/$img\" toptions=\"group=links,effect=fade\" title=\"$movn\"><img src=\"$_MasterRootofMSI/ppusthakam/$movie/$img\" height=100 border=0></a></td>\n";
	  if ($cnt == 4){
	      $cnt=0;
	      echo "</tr><tr bgcolor=#ffffff>";
	  }
      }
  }
}
echo "</table>";
 printFancyFooters();
mysql_close($cLink);


?>
