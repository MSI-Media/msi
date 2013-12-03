<?php session_start();

    error_reporting (E_ERROR);

    $_GET['lang'] = $_SESSION['lang'];
    require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");


    $clink = msi_dbconnect();
    printXHeader('');
?>

<form action=$_master_Submission_Record method=post>
<tr><td>
<table class=ptables>
<?php
    $_songtype = $_GET['t'];
    if ($_songtype == 'ALBUM'){
       echo "<input type=hidden name=dbaction value=\"Album Songs\">\n";
    }
else {
    echo "<input type=hidden name=dbaction value=Songs>\n";
}


$songfirst = 'Song First Words';
$singers = 'Singers';
$year = 'Year';
$music = 'Musician';
$lyrics = 'Lyricist';
$comm = 'Comments';
$cont = 'Your Contact';
$captcha_msg = "To reduce spam, you are required to type in the characters exactly like you see below";
$add = 'Add';
$mov = "Movie";
$lyricsdetails = "Lyrics";
if ($_songtype == 'ALBUM' ){	$mov = "Album"; }

if ($_GET['lang'] != 'E'){
    $songfirst = get_uc('Song First Words','');
    $singers = get_uc('Singers','');
    $year = get_uc('Year','');
    $music = get_uc("Musician",'');
    $lyrics = get_uc('Lyricist','');
    $lyricsdetails = get_uc($lyricsdetails,'');
    $comm = get_uc('Comments','');
    $cont = get_uc('Your Contact');
    $captcha_msg = get_uc($catpcha_msg,'');
    $add = get_uc('Add','');
    $mov = get_uc("$mov",'');	

}

echo "<tr bgcolor=#fffeee><td class=fixedsmall >$songfirst </td><td class=fixedsmall > <input type=text size=30 name=new_song></td></tr>\n";

if ($_songtype == 'ALBUM' ){
   echo "<input type=hidden song_class=album value=1>\n";
}

echo "<tr bgcolor=#fffeee><td class=fixedsmall >$mov </td><td class=fixedsmall > <input type=text size=30 name=new_movie></td></tr>\n";
echo "<tr bgcolor=#fffeee><td class=fixedsmall >$singers </td><td class=fixedsmall > <input type=text size=30 name=new_sing></td></tr>\n";
echo "<tr bgcolor=#fffeee><td class=fixedsmall >$year </td><td class=fixedsmall > <input type=text size=30 name=new_year></td></tr>\n";

echo "<tr bgcolor=#fffeee><td class=fixedsmall >$music </td><td class=fixedsmall > <input type=text size=30 name=new_md></td></tr>\n";

echo "<tr bgcolor=#fffeee><td class=fixedsmall >$lyrics </td><td class=fixedsmall > <input type=text size=30 name=new_lyr></td></tr>\n";

echo "<tr bgcolor=#fffeee><td class=fixedsmall >$comm </td><td class=fixedsmall > <input type=text size=30 name=new_com></td></tr>\n";

echo "<tr bgcolor=#fffeee><td class=fixedsmall >$cont </td><td class=fixedsmall > <input type=text size=30 name=new_contact></td></tr>\n";

echo "<tr bgcolor=#fffeee><td class=fixedsmall >$lyricsdetails </td><td class=fixedsmall > <textarea cols=40 rows=20 name=new_lyrics></textarea></td></tr>\n";


echo "<tr bgcolor=#fffeee><td class=fixedtiny colspan=2>\n";
echo "To reduce spam, you are required to type in the characters exactly like you see below<br>\n";

?>

<img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" />
<input type="text" name="captcha_code" size="10" maxlength="6" />
<div class=fixedtiny><a href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false">Reload Image</a></div>
</td></tr>

<?php

echo "<tr bgcolor=#fffeee><td class=fixedsmall  colspan=2><input type=submit name=Go value=$add></td></tr>\n";
?>
</table>
</td></tr>

<p>
</form>
<?php
    mysql_close($clink);
    printFancyFooters();
?>
