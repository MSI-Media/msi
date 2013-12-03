<?php session_start();
error_reporting (E_ERROR);

include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
$securimage = new Securimage();

    require_once("includes/utils.php");
    require_once("includes/data.php");
    require_once("includes/moviePageUtils.php");
    printHeaders('');
    global $_RootOfMedia;
    $_GET['encode']='utf';


if ($securimage->check($_POST['captcha_code']) == false) {
  echo "<script>alert('The code you entered was incorrect.  Go back and try again.');</script>";
  echo "<script>history.back();</script>";
}
else {


    $comments = $_POST['comments'];
    $name   = $_POST['user'];

    $lyricsismal = 0;
    $today = date("F j, Y");
    $xlyrics = str_replace("\r","<br>",$comments);

    $mailHead = "From:$name\nContent-Type: text/html";	

    printFooters();
    mail("msiadmins@googlegroups.com","MSI Site Comments: MSI 2012 [msidb.org]","<br>From $name<P><font size=+1><pre>$comments</pre></font><P>",$mailHead);
    echo "<script>alert(\"Thanks\");</script>\n";
    echo "<script>location=\"$_RootOfMedia\";</script>";
}


?>
