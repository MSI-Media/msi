<?php session_start();

{
error_reporting (E_ERROR);
require_once("_includes/_data.php");
require_once("_includes/_xtemplate_header.php");
require_once("includes/utils.php");
require_once("updates/movieSearch.inc");


$_GET['lang'] = $_SESSION['lang'];
$sid = $_GET['song_id'];
$table = 'SONGS';
$mode = $_GET['mode'];
if (!$mode){
   $mode = 'movie';
}
if ($_GET['mode'] == 'album'){
   $table = 'ASONGS';
}
$conLink = msi_dbconnect();
printXHeader('');

if ($sid > 0) {

  $query = "SELECT * from $table WHERE S_ID=$sid";
  $res_qry = mysql_query($query);
  $num_qry = mysql_num_rows($res_qry);
  $i = 0;
  echo "<table class=ptables>\n";

    $song = mysql_result($res_qry, $i, "S_SONG");
    $mov  = mysql_result($res_qry, $i, "S_MOVIE");
    $mmov = get_uc("$mov",'movie');

    $mustag = 'Musician';
    $lyrtag = 'Lyrics';
    $yrtag  = "Year";
    $sing   = 'Singers';
    $dettag = 'Song Details';

    if ($_GET['lang'] != 'E'){
	$msong = get_uc("$song",'song');
	$mustag = get_uc('Musician','');
	$lyrtag = get_uc('Lyrics','');
	$yrtag  = get_uc("Year",'');
	$sing   = get_uc('Singers','');
        $dettag = get_uc($dettag,'');
    }

    if ($_GET['lang'] == 'E'){
	echo "<tr><td colspan=2 class=pheading align=center><font color=#AA3322>$song ($mov)</td></tr>";
    }
    else {
	echo "<tr><td colspan=2 class=pheading align=center><font color=#AA3322>$msong ($mmov)</font></td></tr>\n";
    }
        $ulink = "$_Master_song_script?$sid";
        if ($mode == 'album') {
           $ulink = "$_Master_albumsong_script?$sid";
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

  echo "<tr><td colspan=2 class=ptextsmall>";
  echo "<br><img src=	  \"../images/redarrow.gif\" border=0 >&nbsp;<font color=#666666>Add your comments below:</font><br>";
  echo "<form action=$_Master_addcomments_script method=post>";
  echo "<input type=hidden name=mode value=$mode>\n";
  echo "<br><textarea cols=40 rows=3 name=comms>&nbsp;</textarea>";
  echo "<br>Your Name/Email:<br><input type=text size=40 name=uname>\n";
  echo "<input type=hidden name=sid value=\"$sid\"><br>\n";
  //--Captcha Stuff
  echo "<div class=ptextsmall>To reduce spam, you are required to type in the characters exactly like you see below</div>";
  echo "<img id=\"captcha\" src=\"/securimage/securimage_show.php\" alt=\"CAPTCHA Image\" />\n";
  echo "<input type=\"text\" name=\"captcha_code\" size=\"10\" maxlength=\"6\" />\n";
  echo "<div class=fixedtiny><a href=\"#\" onclick=\"document.getElementById('captcha').src = '/securimage/securimage_show.php?' + Math.random(); return false\">Reload Image</a></div>\n";
  echo "<input type=submit name=submit value=\"Add Comments\">\n";
  echo "</form>";

  echo "</td></tr>";
  echo "</table>\n";
  printFancyFooters();
  mysql_close($conLink);

}
function get_unicode ($comp,$tag){
    trim($comp);
    $query = "SELECT unicode from UNICODE_MAPPING WHERE name=\"$comp\";";  	
    $result      = mysql_query($query);
    $num_results = mysql_num_rows($result);
    $i=0;
    while ($i < $num_results){
	$ucomposer   = mysql_result($result,$i,"unicode");
	$i++;
    }
    if (!$ucomposer){
	$ucomposer = "No Equivalent Unicode Available";
    }

    $ucomposer = str_replace("<br>","",$ucomposer);
    return $ucomposer;
}
?>
