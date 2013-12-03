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
$mid = $_GET['mid'];
$mode = $_GET['mode'];

getMovieDetails($mid,$mode);
    printFancyFooters();
mysql_close($cLink);



function getMovieDetails($mid,$mode){
    global $_Master_movie_script,$_Master_album_script,$_Master_upload_pics,$_Master_upload_apics;
    $table = 'MOVIES';
    if ($mode == 'ALBUMS') { $table = 'ALBUMS'; }

  $query = "SELECT * from $table WHERE M_ID=$mid";
  $res_qry = mysql_query($query);
  $num_qry = mysql_num_rows($res_qry);
  $i = 0;
  echo "<table class=ptables>\n";
  while ($i < $num_qry){

    $mov  = mysql_result($res_qry, $i, "M_MOVIE");
    $mmov = get_uc("$mov",'movie');

    $mustag = 'Musician';
    $lyrtag = 'Lyrics';
    $yrtag  = "Year";
    $sing   = 'Singers';
    $pictag = 'Submit Pictures';
    $dettag = 'Details';
    if ($_GET['lang'] != 'E'){
	$mustag = get_uc('Musician','');
	$lyrtag = get_uc('Lyrics','');
	$yrtag  = get_uc("Year",'');
	$sing   = get_uc('Singers','');
        $pictag = get_uc($pictag,'');
        $dettag = get_uc($dettag,'');
    }

    if ($_GET['lang'] == 'E'){
	echo "<tr><td colspan=2 class=pheading align=center>$mov</td></tr>";
    }
    else {
	echo "<tr><td colspan=2 class=pheading align=center>$mmov</td></tr>\n";
    }


	echo "<tr><td colspan=2 class=psubheading align=center>$pictag</td></tr>\n";
        $ulink = "$_Master_movie_script?$mid";
        if ($mode == 'ALBUMS') {
           $ulink = "$_Master_album_script?$mid";
        }
     echo "<tr><td colspan=2 class=psubtitle align=center><a href=\"${ulink}\">$dettag</a></font></td></tr>\n";
    $mus  = mysql_result($res_qry, $i, "M_MUSICIAN");
    $lyr  = mysql_result($res_qry, $i, "M_WRITERS");

    $yr  = mysql_result($res_qry, $i, "M_YEAR");


    if ($_GET['lang'] != 'E'){
	$mus = get_uc("$mus",'composer');
	$lyr = get_uc("$lyr",'lyricist');
    }


    echo "<tr bgcolor=#eeeeee><td class=ptextsmall>$mustag</td><td class=ptextsmall>$mus</td></tr>";
    echo "<tr><td class=ptextsmall>$lyrtag</td><td class=ptextsmall>$lyr</font></td></tr>";
    echo "<tr bgcolor=#eeeeee><td class=ptextsmall>$yrtag</td><td class=ptextsmall>$yr</td></tr>";


    mysql_query("SET NAMES latin1");

    if ($mode == 'ALBUMS'){
	echo "<form method='POST' enctype='multipart/form-data' action=$_Master_upload_apics><br>";
    }
    else {
	echo "<form method='POST' enctype='multipart/form-data' action=$_Master_upload_pics><br>";
    }

    echo "<input type=hidden name=mid value=$mid>\n";
    $msgFile = "Writeups/submitPictures_msg";
    if ($_GET['lang'] != 'E'){
    $msgFile .= '_malayalam';
    }
    printContents("${msgFile}.txt");
    echo "<tr><td class=fixedsmall> </td><td> </td></tr>";
    echo "</table>\n";
    $i++;
  }
  return $song;
}

?>
