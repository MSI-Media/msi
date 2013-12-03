<?php session_start();

error_reporting (E_ERROR);
$_GET['lang'] = $_SESSION['lang'];

require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");

set_time_limit(0);


$cLink = msi_dbconnect();
printXHeader('') ;
$captcha_msg = "To reduce spam, you are required to type in the characters exactly like you see below";
$captcha_msg = get_uc($catpcha_msg,'');
$nomid_msg = "Sorry. You have not specified a movie id to submit the trailer";

if ($_GET['lang'] != 'E'){
    $nomid_msg = get_uc($nomid_msg,'');
}

$mid = $_GET['mid'];
$mode = $_GET['mode'];


$qs = $_SERVER['QUERY_STRING'];
$qs = str_replace("&cl=1","",$qs);
if ($qs > 0) {
    $mid = $qs;
}

if ($mid < 1) {
    echo "$nomid_msg<BR>";
}
else {
    getMovieDetails($mid,$mode);
}
printFancyFooters();
mysql_close($cLink);



function getMovieDetails($mid,$mode){

    global $_Master_movie_script ;
    global $_Master_album_script ;
    global $_Master_promo_script ;
    global $_Master_apromo_script ;

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

    $dirtag = 'Director';
    $mustag = 'Musician';
    $lyrtag = 'Lyrics';
    $yrtag  = "Year";
    $sing   = 'Singers';
    $addtag = "Add";
    $revtag = "Trailers";
    $emailtag = "Your Contact";
    $dettag = 'Details';
    if ($_GET['lang'] != 'E'){
	$dirtag = get_uc('Director','');
	$mustag = get_uc('Musician','');
	$lyrtag = get_uc('Lyrics','');
	$yrtag  = get_uc("Year",'');
	$sing   = get_uc('Singers','');
	$revtag = get_uc('Reviews','');
	$addtag = get_uc('Add','');
	$emailtag = get_uc("Your Contact",'');
        $dettag = get_uc($dettag,'');
    }

    $revtag = "Promotional Materials";
    $vidtag = "Youtube Trailers";
    $wikitag = "Wiki Page";
    $website = "Official Website";
    $facebook = "Official Facebook Page";
    $spam_msg = "You are required to type in the characters exactly like you see below to reduce spam";
    if ($_GET['lang'] == 'E'){
	echo "<tr><td colspan=2 class=pheading align=center><font color=#AA3322>$mov</td></tr>";
    }
    else {
        $revtag = get_uc($revtag,'');
        $vidtag = get_uc($vidtag,'');
	$wikitag = get_uc($wikitag,'');
	$website = get_uc($website,'');
	$facebook = get_uc($facebook, '');
	$spam_msg = get_uc($spam_msg,'');
	echo "<tr><td colspan=2 class=pheading align=center><font color=#AA3322>$mmov</font></td></tr>\n";
    }
    echo "<tr><td colspan=2 class=psubheading align=center><font color=#AA3322>$revtag</font></td></tr>\n";
        $ulink = "$_Master_movie_script?$mid";
        if ($mode == 'ALBUMS') {
           $ulink = "$_Master_album_script?$mid";
        }
     echo "<tr><td colspan=2 class=psubtitle align=center><a href=\"${ulink}\">$dettag</a></font></td></tr>\n";

    $dirt  = mysql_result($res_qry, $i, "M_DIRECTOR");
    $mus  = mysql_result($res_qry, $i, "M_MUSICIAN");
    $lyr  = mysql_result($res_qry, $i, "M_WRITERS");

    $yr  = mysql_result($res_qry, $i, "M_YEAR");


    if ($_GET['lang'] != 'E'){
	$mus = get_uc("$mus",'composer');
	$lyr = get_uc("$lyr",'lyricist');
	$dirt = get_uc("$dirt",'');
    }

    echo "<tr bgcolor=#eeeeee><td class=ptextsmall>$dirtag</td><td class=ptextsmall>$dirt</td></tr>";
    echo "<tr><td class=ptextsmall>$mustag</td><td class=ptextsmall>$mus</td></tr>";
    echo "<tr bgcolor=#eeeeee><td class=ptextsmall>$lyrtag</td><td class=ptextsmall>$lyr</font></td></tr>";
    echo "<tr ><td class=ptextsmall>$yrtag</td><td class=ptextsmall>$yr</td></tr>";


    mysql_query("SET NAMES latin1");

    if ($mode == 'ALBUMS'){
	echo "<form method='POST'  action=$_Master_opromo_script><br>";
    }
    else {
	echo "<form method='POST'  action=$_Master_promo_script><br>";
    }

    echo "<input type=hidden name=mid value=$mid>\n";
    echo "<tr bgcolor=#eeeeee><td valign=top class=ptextsmall>$emailtag</td><td class=ptextsmall>\n";
    echo "<input type=text name=email_address value=>\n";
    echo "</td></tr>";

    echo "<tr ><td valign=top class=ptextsmall>$vidtag</td><td class=ptextsmall>\n";
    echo "<input type=text size=60 name=promovideo>\n";
    echo "</td></tr>";
    echo "<tr><td colspan=2>\n";

    echo "<tr bgcolor=#eeeeee><td valign=top class=ptextsmall>$website</td><td class=ptextsmall>\n";
    echo "<input type=text size=60 name=website>\n";
    echo "</td></tr>";
    echo "<tr><td colspan=2>\n";

    echo "<tr><td valign=top class=ptextsmall>$wikitag</td><td class=ptextsmall>\n";
    echo "<input type=text size=60 name=wikisite>\n";
    echo "</td></tr>";
    echo "<tr><td colspan=2>\n";

    echo "<tr bgcolor=#eeeeee><td valign=top class=ptextsmall>$facebook</td><td class=ptextsmall>\n";
    echo "<input type=text size=60 name=facebook>\n";
    echo "</td></tr>";
    echo "<tr><td colspan=2>\n";


    echo $spam_msg,"<BR>";	
?>
<img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" />
<input type="text" name="captcha_code" size="10" maxlength="6" />
<div class=fixedtiny><a href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false">Reload Image</a></div>
<?php

    echo "</td></tr>";

    echo "<tr bgcolor=#eeeeee><td colspan=2 class=ptextsmall><input type=submit name=submit value=\"$addtag\">\n";
    echo "</form>\n";
    echo "</td></tr>";
    echo "</table>\n";
    $i++;
  }
  return $song;
}

?>
