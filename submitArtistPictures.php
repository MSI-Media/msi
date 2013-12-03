<?php session_start();

error_reporting (E_ERROR);
$_GET['lang'] = $_SESSION['lang'];

require_once("includes/utils.php");

    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");;	

set_time_limit(0);


$cLink = msi_dbconnect();
printXHeader('');

printForm();
printFancyFooters();
mysql_close($cLink);



function printForm(){
 
     global $_Master_artistpic_upload;	

    $dettag = 'Submit Artist Pictures';
 
    if ($_GET['lang'] != 'E'){
        $dettag = get_uc($dettag,'');
    }

    echo "<table class=ptables>\n";
     echo "<tr><td colspan=2 class=pheading align=center>$dettag</td></tr>\n";
 


    mysql_query("SET NAMES latin1");


    echo "<form method='POST' enctype='multipart/form-data' action=$_Master_artistpic_upload><br>";
    
    $msgFile = "Writeups/submitArtistPictures_msg";
    if ($_GET['lang'] != 'E'){
    $msgFile .= '_malayalam';
    }
    printContents("${msgFile}.txt");
    echo "<tr><td class=fixedsmall> </td><td> </td></tr>";
    echo "</table>\n";


}

?>