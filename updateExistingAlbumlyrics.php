<?php session_start();
error_reporting (E_ERROR);

include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
$securimage = new Securimage();

    require_once("includes/utils.php");
    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_moviePageUtils.php");

    global $_RootOfMedia;

    $cLink = msi_dbconnect();
    printXHeader('');
    global $_RootOfMedia;

if ($securimage->check($_POST['captcha_code']) == false) {
  echo "<script>alert('The code you entered was incorrect.  Go back and try again.');</script>";
  echo "<script>history.back();</script>";
}
else {


    $lyrics = $_POST['lyrics'];
    $sid    = $_POST['sid'];
    $name   = $_POST['uname'];

    $lyricsismal = 0;
    $today = date("F j, Y");
    $xlyrics = str_replace("\r","<br>",$lyrics);
    $pos = strrpos($xlyrics, '&#3342;');

    if ($pos != false){
      $fil = "php/temp/album_mal_lyrics/${sid}.html";
      if (file_exists("$fil")){
	$fil = "php/temp/album_mal_lyrics/${sid}.1.html";
	if (file_exists("$fil")){
	  $fil = "php/temp/album_mal_lyrics/${sid}.2.html";
	}
      }
      $lyricsismal = 1;
    }
    else {
      $fil = "php/temp/album_lyrics/${sid}.html";
      if (file_exists("$fil")){
	$fil = "php/temp/album_lyrics/${sid}.1.html";
	if (file_exists("$fil")){
	  $fil = "php/temp/album_lyrics/${sid}.2.html";
	}
      }
    }
//------------------------------------------------------
// Remove Cache Files
// Helps with Locking
//------------------------------------------------------
    $langs = array ('M','E');
    $id = $sid;
    $type = 'as.php';
    $typename = 'AlbumSongs';
   foreach ($langs as $lang){
       $cachedir = "cache/$lang/$typename";
       if (file_exists ("$cachedir/%2F${type}%3F${id}")){
	   unlink ("$cachedir/%2F${type}%3F${id}");
       }
       if (file_exists ("$cachedir/%2F${type}%3F${id}%26cl%3D1")){
	   unlink ("$cachedir/%2F${type}%3F${id}%26cl%3D1");
       }
       if (file_exists ("$cachedir/%2F${type}%3F${id}%26%26cl%3D1")){
	   unlink ("$cachedir/%2F${type}%3F${id}%26%26cl%3D1");
       }
   }
//------------------------------------------------------



    $fh      = fopen ("$fil",w);
    fwrite ($fh, "<i>Added by $name on $today</i><br>\n");    
    fwrite($fh, $xlyrics);
    fclose($fh);
  if (strpos($name, "@") !== false){
  $name = "$name" . "@msiguest.com";
  }
    $mailHead = "From:$name\nContent-Type: text/html";	

    printFancyFooters();
    mysql_close($cLink);

    mail("msiadmins@googlegroups.com","UPDATE LYRICS: MSI 2012 [msidb.org] ALBUM Lyrics Addition","<br>($sid)<pre>$lyrics</pre><div style=\"background-color:#ffffcc\">First Verify whether these lyrics have been updated by another admin already: <a href=\"$_RootOfMedia/as.php?sid=$sid\">Click Here To Verify the generated Lyrics File</a> <br>if not, <a href=\"$_RootOfMedia/admin/index.php?display=Lyrics\">Manage These Lyrics in Admin Panel</a><br><a href=\"$_RootOfMedia/admin/index.php?display=Update&sid=$sid&type=autoalbum\">Manage This Song Details</a><br></div>",$mailHead);

    echo "<script>alert(\"Thanks for submitting comments on lyrics.\\nWe will make the necessary updates soon\");</script>\n";
    echo "<script>location=\"$_RootOfMedia/manageLyrics.php?song_id=${sid}&mode=album\";</script>";
}


?>
