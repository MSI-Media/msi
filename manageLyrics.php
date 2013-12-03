<?php session_start();

error_reporting (E_ERROR);
require_once("_includes/_data.php");
require_once("includes/utils.php");
require_once("_includes/_xtemplate_header.php");
require_once("updates/movieSearch.inc");
$_GET['lang'] = $_SESSION['lang'];

$table = 'SONGS';
$mode = $_GET['mode'];

if (!$mode){
   $mode = 'movie';
}
if ($_GET['mode'] == 'album'){
   $table = 'ASONGS';
}
$sid = $_GET['song_id'];

$conLink = msi_dbconnect();
printXHeader('');

if ($sid > 0) {

  $query = "SELECT * from $table WHERE S_ID=$sid";
  $res_qry = mysql_query($query);
  $num_qry = mysql_num_rows($res_qry);
  $i = 0;
  echo "<table class=ptables>\n";
  while ($i < $num_qry){
    $song = mysql_result($res_qry, $i, "S_SONG");
    $mov  = mysql_result($res_qry, $i, "S_MOVIE");
    $mmov = get_uc("$mov",'movie');

    $mustag = 'Musician';
    $lyrtag = 'Lyrics';
    $yrtag  = "Year";
    $sing   = 'Singers';
	$lyraddtag = "Add or Modify Lyrics of this Song";
$dettag = 'Song Details';
    if ($_GET['lang'] != 'E'){
	$msong = get_uc("$song",'song');
	$mustag = get_uc('Musician','');
	$lyrtag = get_uc('Lyrics','');
	$yrtag  = get_uc("Year",'');
	$sing   = get_uc('Singers','');
	$lyraddtag = get_uc($lyraddtag,'');
        $dettag = get_uc($dettag,'');
    }

    if ($_GET['lang'] == 'E'){
	echo "<tr><td colspan=2 class=pheading align=center>$song ($mov)</td></tr>";
    }
    else {
	echo "<tr><td colspan=2 class=pheading align=center>$msong ($mmov)</td></tr>\n";
    }
	echo "<tr><td colspan=2 class=psubheading align=center><font color=#AA3322>$lyraddtag</font></td></tr>\n";
        $ulink = "s.php?$sid";
        if ($mode == 'album') {
           $ulink = "as.php?$sid";
        }
     echo "<tr><td colspan=2 class=psubtitle align=center><a href=\"${ulink}\">$dettag</a></font></td></tr>\n";

    $mus  = mysql_result($res_qry, $i, "S_MUSICIAN");
    $lyr  = mysql_result($res_qry, $i, "S_WRITERS");

    $yr  = mysql_result($res_qry, $i, "S_YEAR");
    $singers  = mysql_result($res_qry, $i, "S_SINGERS");
    $ra  = mysql_result($res_qry, $i, "S_RAGA");


    if ($_GET['lang'] != 'E'){
	$mus = get_uc("$mus",'composer');
	$lyr = get_uc("$lyr",'lyricist');
	$singers = get_uc("$singers",'singers');
	$ra = get_uc("$ra",'raga');
	$yr = get_uc("$yr",'year');
    }


    echo "<tr bgcolor=#eeeeee><td class=ptextsmall>$mustag</td><td class=ptextsmall>$mus</td></tr>";
    echo "<tr><td class=ptextsmall>$lyrtag</td><td class=ptextsmall>$lyr</font></td></tr>";
    echo "<tr bgcolor=#eeeeee><td class=ptextsmall>$yrtag</td><td class=ptextsmall>$yr</td></tr>";
    echo "<tr><td class=ptextsmall>$sing</td><td class=ptextsmall>$singers </font></td></tr>";
    if ($ra){
	echo "<tr bgcolor=#eeeeee><td class=ptextsmall>Raga(s) Used</td><td class=ptextsmall>$ra</td></tr>";
    }

    mysql_query("SET NAMES latin1");
    $i++;
  }
  $edit_tag = "Edit or Add Lyrics";
  $sub_tag  = "Add";
  $contact_tag = 'Your Contact';
  if ($_GET['lang'] != 'E'){
      $edit_tag = get_uc($edit_tag,'');
      $sub_tag = get_uc($sub_tag,'');
      $contact_tag = get_uc($contact_tag,'');
  }
  $lyricsRoot = findRoot($sid,'Lyrics','Movies');
  if ($_GET['mode'] == 'album'){
     $lyricsRoot = findRoot($sid,'Lyrics','Albums');
  }
  $existing_lyrics='';
  if (file_exists("$lyricsRoot/${sid}.html")){
      $existing_lyrics = file_get_contents("$lyricsRoot/${sid}.html");
  }
 // echo "<tr><td colspan=2 class=ptextsmall align=center><font color=#BB5566>$edit_tag</font></td></tr></table>\n";
   echo "<tr><td colspan=2 class=ptextsmall ><font color=#BB5566>$edit_tag</font></td></tr>\n";
  echo "<tr><td colspan=2 valign=top class=ptextsmall align=left>\n";
  echo ( "<form action=addlyrics.php method=post>");
  echo ("<input type=hidden name=mode value=$mode>\n");
  echo ( "<textarea cols=90 rows=30 name=lyrics>&nbsp;</textarea>");
//  echo "</td><td valign=top class=ptextsmall>$existing_lyrics</td></tr>";
  echo "</td></tr>";
  echo "<tr bgcolor=#eeeeee><td class=ptextsmall align=left>\n";
  echo ( "$contact_tag</td><td class=ptextsmall><input type=text size=50 name=uname></td></tr>\n");
  echo ( "<input type=hidden name=sid value=\"$sid\"><br>\n");
  echo ( "<tr ><td colspan=2 class=ptextsmall align=center><font color=#BB5566><input type=submit name=submit value=\"$sub_tag\">\n");
  echo ( "</form></td></tr></table>");


  echo "</td></tr>";
  echo "</table>\n";
  mysql_close($conLink);
  printFancyFooters();

}

?>
