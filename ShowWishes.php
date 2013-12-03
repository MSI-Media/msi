<?php session_start();
{
    error_reporting (E_ERROR);
    $_GET['lang'] = $_SESSION['lang'];
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("includes/utils.php");
    require_once("_includes/_moviePageUtils.php");


    $_GET['encode']='utf';


    $conLink = msi_dbconnect();
    printXHeader('Popup');
    $artist = $_GET['artist'];

	if ($_SESSION['lang'] != 'E') {	
	    echo "<div class=pheading>",get_uc($artist,""), " </div>";
	    echo "<div class=psubheading> ",get_uc("Best Wishes",""), "</div>";
	}
	else {
	    echo "<div class=pheading>$artist</div>";
	    echo "<div class=psubheading> Best Wishes</div>";
	}

    if (file_exists("Writeups/Wishes/${artist}.txt")){
	echo "<div class=ptablesnotfixed>\n";
	printContents("Writeups/Wishes/${artist}.txt");
	echo "</div>\n";
    }
    else if (file_exists("pics/Wishes/${artist}.jpg")){
	echo "<div class=ptablesnotfixed>\n";
	echo "<a href=\"pics/Wishes/${artist}.jpg\" class=\"top_up\"><img src=\"pics/Wishes/${artist}.jpg\" height=200></a>\n";
	echo "</div>\n";
    }

    printFancyFooters();
    mysql_close($conLink);

}


?>
