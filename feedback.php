<?php session_start();

{
    error_reporting (E_ERROR);
    $_GET['lang'] = $_SESSION['lang'];
    require_once("includes/utils.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("_includes/_xtemplate_header.php");

    $_GET['encode']='utf';


    $cLink = msi_dbconnect();
    printXHeader('');
    $tit_tag = 'MSI Feedback';
    $tit2_tag = 'We love hearing from you. Tell us if you have comments questions or concerns';
    $tit3_tag = 'See What Others Are Saying';
    $contact_tag = 'Your Contact';
    $subfeedback = 'Submit Feedback';
    if ($_GET['lang'] != 'E'){
	$tit_tag = get_uc($tit_tag,'');
	$contact_tag = get_uc($contact_tag,'');
	$tit2_tag = get_uc($tit2_tag,'');
        $tit3_tag = get_uc($tit3_tag,'');
	$subfeedback = get_uc($subfeedback,'');
    }

    echo "<div align=center style=\"width:100%;background-color:#fefafa\">\n";
    echo "<div class=pheading>$tit_tag</div>\n";
    echo "<div class=psubtitle>$tit2_tag</div>\n";
    echo "<div class=psubtitle><a href=\"index.php?i=5\">$tit3_tag</a></div><P>\n";
    echo "<form action=submitfeedback.php method=post>";
    echo "<textarea placeholder=\"$tit_tag\" cols=80 rows=10 name=comments></textarea>";
    echo "<P><div class=fixedsmall><b>$contact_tag:</b>&nbsp;<input type=text name=user value=\"\" size=30></font></div>\n";
    echo "<P>";
    echo "<div class=fixedsmall>To reduce spam, you are required to type in the characters exactly like you see below</div>";
    echo "<img id=\"captcha\" src=\"/securimage/securimage_show.php\" alt=\"CAPTCHA Image\" />\n";
    echo "<input type=\"text\" name=\"captcha_code\" size=\"10\" maxlength=\"6\" />\n";
    echo "<div class=fixedtiny><a href=\"#\" onclick=\"document.getElementById('captcha').src = '/securimage/securimage_show.php?' + Math.random(); return false\">Reload Image</a></div>\n";
    echo "<input type=submit name=submit value=\"$subfeedback\">\n";
    echo "</form>";
    echo "</div>\n";
    printFancyFooters();
    mysql_close($cLink);
}


?>
