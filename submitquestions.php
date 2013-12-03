<?php session_start();
error_reporting (E_ERROR);

include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
$securimage = new Securimage();

    require_once("_includes/_xtemplate_header.php");	
    require_once("_includes/_bodycontents.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("_includes/_System.php");
    printXHeader('');
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

    printFancyFooters();
    mail("msiadmins@googlegroups.com","MSI Experts Questions: MSI 2013 [msidb.org]","<br>From $name<P><font size=+1><pre>$comments</pre></font><P>",$mailHead);
    echo "<script>alert(\"Thanks. We will reply soon.\");</script>\n";
    echo "<script>location=\"$_RootOfMedia\";</script>";
}


?>
