<?php session_start();

error_reporting (E_ERROR);
$_GET['lang'] = $_SESSION['lang'];

require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");


set_time_limit(0);


$cLink = msi_dbconnect();
    printXHeader('');

$sid = $_GET['sid'];
$mode = $_GET['mode'];

getSongCDetails($sid,$mode);
    printFancyFooters();
mysql_close($cLink);



function getSongCDetails($sid,$mode){
    $table = 'SONGS';
    if ($mode == 'ALBUMS') { $table = 'ASONGS'; }

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
    $audtag = 'Submit Audios';
    $dettag = 'Song Details';
    if ($_GET['lang'] != 'E'){
	$msong = get_uc("$song",'song');
	$mustag = get_uc('Musician','');
	$lyrtag = get_uc('Lyrics','');
	$yrtag  = get_uc("Year",'');
	$sing   = get_uc('Singers','');
        $audtag = get_uc($audtag,'');
        $dettag = get_uc($dettag,'');
    }

    if ($_GET['lang'] == 'E'){
	echo "<tr><td colspan=2 class=pheading align=center>$song ($mov)</td></tr>";
    }
    else {
	echo "<tr><td colspan=2 class=pheading align=center>$msong ($mmov)</td></tr>\n";
    }
       echo "<tr><td colspan=2 class=psubheading align=center>$audtag</td></tr>\n";
        $ulink = "s.php?$sid";
        if ($mode == 'ALBUMS') {
           $ulink = "as.php?$sid";
        }
        echo "<tr><td colspan=2 class=psubtitle align=center><a href=\"${ulink}\">$dettag</a></td></tr>\n";


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

    if ($mode == 'ALBUMS'){
	echo "<form method='POST' enctype='multipart/form-data' action='uploadAlbumClips.php'><br>";
    }
    else {
	echo "<form method='POST' enctype='multipart/form-data' action='upload.php'><br>";
    }
    echo "<input type=hidden name=sid value=$sid>\n";
    $msgFile = "Writeups/submitClips_msg";
    if ($_GET['lang'] != 'E'){
    $msgFile .= '_malayalam';
    }
    printContents("${msgFile}.txt");
    echo "</table>\n";
    $i++;
  }
  return $song;
}

?>
