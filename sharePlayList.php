<?php
{
    $_URL =	$_POST['pl_link'];
    $from = $_POST['new_contact'];
    $playlistname = $_POST['new_song'];
    
    if ($_URL == '' || $from == '' || $playlistname == ''){
	echo "<script>alert(\"Please provide Playlist, Name and Email\");</script>";
    }
    else {
	$to   = "msiadmins@googlegroups.com";
	$sub  = "MSI 2013 [msidb.org] Playlist Shared Notification";
	$mes = "<br>Following <a href=\"$_URL\">Playlist</a>, named \"$playlistname\" was uploaded by \"$from\"\n\r\n\r$_URL<P><P>";
	$mailHead = "From: $from" . "\r\n" .
	    'Reply-To: teammalayalasangeetham@gmail.com' . "\r\n" ;
	$mailHead  .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	mail($to,$sub,$mes, $mailHead);
	echo "<script>alert(\"Playlist Submitted !\");</script>";
    }
    echo "<script>history.back();</script>";
}
?>
