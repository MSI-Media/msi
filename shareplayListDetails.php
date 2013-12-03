<?php session_start();

    error_reporting (E_ERROR);

    $_GET['lang'] = $_SESSION['lang'];
    require_once("includes/utils.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("_includes/_xtemplate_header.php");

    $clink = msi_dbconnect();
    printXHeader('');
    echo "	<form action=$_Master_playlist_sharer method=post>\n";
?>


<tr><td>
<table class=ptables>
<?php
    $linkname = "$_Master_vidscript" . '?' . $_SERVER['QUERY_STRING'];



$songfirst = 'Playlist Name';
$cont = 'Your Contact';
$pl_link = 'Link to the Playlist';
$captcha_msg = "To reduce spam, you are required to type in the characters exactly like you see below";
$add = 'Add';
$mov = "Movie";
if ($_songtype == 'ALBUM' ){	$mov = "Album"; }

if ($_GET['lang'] != 'E'){
    $songfirst = get_uc($songfirst,'');
    $cont = get_uc('Your Contact','');
    $pl_link = get_uc($pl_link,'');
    $captcha_msg = get_uc($catpcha_msg,'');
    $add = get_uc('Add','');
    $mov = get_uc("$mov",'');	

}

echo "<tr bgcolor=#ffffff><td class=fixedsmall >$songfirst </td><td class=fixedsmall > <input type=text size=50 name=new_song></td></tr>\n";


echo "<tr bgcolor=#ffffff><td class=fixedsmall >$cont </td><td class=fixedsmall > <input type=text size=50 name=new_contact></td></tr>\n";

echo "<tr bgcolor=#ffffff><td class=fixedsmall >$pl_link </td><td class=fixedsmall > <input type=text size=80 value=\"$linkname\" name=pl_link></td></tr>\n";


echo "<tr bgcolor=#ffffff><td class=fixedtiny colspan=2>\n";
echo "To reduce spam, you are required to type in the characters exactly like you see below<br>\n";

?>

<img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="captcha_code" size="10" maxlength="6" />
<div class=fixedtiny><a href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false">Reload Image</a></div>
</td></tr>

<?php

echo "<tr bgcolor=#ffffff><td class=fixedsmall  colspan=2><input type=submit name=Go value=$add></td></tr>\n";
?>
</table>
</td></tr>

<p>
</form>
<?php
    printFancyFooters();
    mysql_close($clink);

?>
