<?php session_start();

error_reporting (E_ERROR);
$_GET['lang'] = $_SESSION['lang'];


require_once("includes/utils.php");
    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_moviePageUtils.php");

set_time_limit(0);


$cLink = msi_dbconnect();
printXHeader('');

echo  "<form action=uploadArticles.php method=post>";
entryFormDetails();
echo "</form>";

printFancyFooters();
mysql_close($cLink);



function entryFormDetails($mid,$mode){



    $_title = 'Title';
    $_user  = 'Your Name';
    $_source = 'Source';
    $_url    = 'Web Address - Provide the complete URL of the Page';
    $_tags   = 'Keywords';
    $_submsg = 'Submit an article from anywhere on the Internet';
    $_subtext= 'We will provide link and credits to the original source with your name';
    if ($_GET['lang'] != 'E'){
        $_submsg = get_uc($_submsg,'');
         $_subtext = get_uc($_subtext,'');
         $_title = get_uc($_title,'');
         $_user = get_uc($_user,'');
         $_source = get_uc($_source ,'');
         $_url = get_uc($_url ,'');
         $_tags= get_uc($_tags,'');
    }
     echo "<div class=pheading align=center>$_submsg</div>";
          echo "<div class=psubheading align=center>$_subtext</div>";
    echo "<table width=100%>\n";
    echo "<tr bgcolor=#eeeeee><td class=ptextsmall>$_title</td><td class=ptextsmall><input type=text name=title size=60></td></tr>";
    echo "<tr><td class=ptextsmall>$_source</td><td class=ptextsmall><input type=text name=source size=60></font></td></tr>";
    echo "<tr bgcolor=#eeeeee><td class=ptextsmall>$_user</td><td class=ptextsmall><input type=text name=submitter size=60></td></tr>";
    echo "<tr ><td class=ptextsmall><font color=red>$_url</font></td><td class=ptextsmall><input type=text name=url size=60></td></tr>";
    echo "<tr bgcolor=#eeeeee><td class=ptextsmall>$_tags</td><td class=ptextsmall><input type=text name=tags size=60></td></tr>";
   echo "<tr><td colspan=2><input type=submit name=submit value=Submit></td></tr>";
    echo "</table>\n";

  return $song;
}

?>
