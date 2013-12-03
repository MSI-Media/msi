<?php session_start();
{
   error_reporting (E_ERROR);
   require_once("_includes/_xtemplate_header.php");
   require_once("_includes/_bodycontents.php");
   require_once("_includes/_data.php");
   require_once("_includes/_moviePageUtils.php");
   require_once("_includes/_System.php");
   
   $_GET['encode']='utf';
   $cl = msi_dbconnect() ;
   printXheader('');
   $printBodyBlock=0;
   echo "<aside class=\"body_wrapper\">\n";
   echo "  <div class=\"main\">\n";

   $experts_file = "experts";
   if ($_GET['lang'] != 'E') { $experts_file .= "_malayalam"; }

   printHtmlContents("Writeups/${experts_file}.txt");


    $tit_tag = 'Your Questions';
    $contact_tag = 'Your Contact';
    $subfeedback = 'Submit Question';
    if ($_GET['lang'] != 'E'){
	$tit_tag = get_uc($tit_tag,'');
	$contact_tag = get_uc($contact_tag,'');
	$subfeedback = get_uc($subfeedback,'');
    }

    echo "<div align=center style=\"width:100%\">\n";
    echo "<div class=pheading>$tit_tag</div>\n";
    echo "<form action=submitquestions.php method=post>";
    echo "<textarea placeholder=\"$tit_tag\" cols=80 rows=10 name=comments></textarea>";
    echo "<P><div class=fixedsmall><b>$contact_tag:</b>&nbsp;<input type=text name=user value=\"\" size=30></font></div>\n";
    echo "<P>";
    echo "<div class=fixedsmall>To reduce spam, you are required to type in the characters exactly like you see below</div>";
    echo "<img id=\"captcha\" src=\"/securimage/securimage_show.php\" alt=\"CAPTCHA Image\" />\n";
    echo "<input type=\"text\" name=\"captcha_code\" size=\"10\" maxlength=\"6\" />\n";
    echo "<div class=fixedtiny><a href=\"#\" onclick=\"document.getElementById('captcha').src = '/securimage/securimage_show.php?' + Math.random(); return false\">Reload Image</a></div>\n";
    echo "<input type=submit name=submit value=\"$subfeedback\">\n";
    echo "</form>";

   echo "</div>";
   echo "</aside>\n";
   printFancyFooters();
   mysql_close($cl);
}
