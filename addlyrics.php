<?php

    error_reporting (E_ERROR);
    require_once("includes/utils.php");
    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_moviePageUtils.php");

    $_GET['encode']='utf';


$lyrics = $_POST['lyrics'];
$name = $_POST['uname'];
$sid  = $_POST['sid'];
$mode = $_POST['mode'];

if (!$lyrics || !$name){
  echo "<script>alert(\"Please provide comments and your name/contact\");</script>";
   echo "<script>history.back();</script>";	
}
else {
    if (!$sid or $sid < 1){
	  echo "<script>alert(\"There is a problem with your request. Please contact the administrator via email before proceeding with more updates\");</script>\n";
	  echo "<script>history.back();</script>";	
    }
    else {	


    $cLink = msi_dbconnect();
    printXHeader('');
    $tit_tag = 'Lyrics Submission';
    $hdr_tag = 'Please Confirm Your Submission';
    $contact_tag = 'Your Contact';
    if ($_GET['lang'] != 'E'){
	$tit_tag = get_uc($tit_tag,'');
	$hdr_tag = get_uc($hdr_tag,'');
	$contact_tag = get_uc($contact_tag,'');
    }
    $songname = '';
    if ($mode == 'album'){
	$songname = getSongNameForTitle($sid,'ASONGS');
    }
    else {
	$songname = getSongNameForTitle($sid,'SONGS');
    }

    echo "<div class=pheading>$tit_tag</div>\n";
    echo "<div class=psubheading>$hdr_tag ($songname)</div>\n";

    if ($mode == 'album'){
	echo "<form action=$_Master_alyrupdate method=post>";
    }
    else {
	echo "<form action=$_Master_lyrupdate method=post>";
    }

	echo "<textarea cols=80 rows=20 name=lyrics>$lyrics</textarea>";
	echo "<input type=hidden name=uname value=\"$name\">\n";
	echo "<div class=fixedsmall><b>$contact_tag:&nbsp;</b><font color=red>$name</font></div>\n";
	echo "<input type=hidden name=sid value=\"$sid\">\n";



	echo "<div class=fixedsmall>To reduce spam, you are required to type in the characters exactly like you see below</div>";
	echo "<img id=\"captcha\" src=\"/securimage/securimage_show.php\" alt=\"CAPTCHA Image\" />\n";
	echo "<input type=\"text\" name=\"captcha_code\" size=\"10\" maxlength=\"6\" />\n";
	echo "<div class=fixedtiny><a href=\"#\" onclick=\"document.getElementById('captcha').src = '/securimage/securimage_show.php?' + Math.random(); return false\">Reload Image</a></div>\n";
	echo "<input type=submit name=submit value=\"Confirm Lyrics Changes\">\n";
	echo "</form>";
    }

    echo "</td></tr></table></div>";
    printFancyFooters();
    mysql_close($cLink);
}


?>
