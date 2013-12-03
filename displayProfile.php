<?php session_start();

{
    error_reporting (E_ERROR);
    $_GET['lang'] = $_SESSION['lang'];
    require_once("includes/utils.php");
    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("_includes/_profileUtils.php");
    require_once("includes/searchUtils.php");
    require_once("includes/cache.php");
/*
    if (isNonAdminUser()){    
	$ch = new cache($_GET['lang'],'Profiles');
    }
*/
    $cLink = msi_dbconnect();
    printXHeader('Popup');

    $artist=$_GET['artist'];	
    $category = $_GET['category'];
    artistData ($artist, $category,'MOVIES');
    getRelevantArticles(array("$artist"));

    $regenMsg='Regenerate This Data';
    $dateMsg ='This Page was Generated';
    $today = date("F j, Y, g:i a T");
    if ($_GET['lang'] != 'E') { $regenMsg = get_uc($regenMsg,''); $dateMsg = get_uc($dateMsg,'');}
    $catstring = "category=$category&artist=$artist";
    echo "<div class=psubtitle>$dateMsg $today | <a href=\"RemoveCache.php?typename=Profiles&type=displayProfile.php&$catstring\">$regenMsg</a></div>\n";


    printFancyFooters();
    mysql_close($cLink);




/*
    if (isNonAdminUser()){    
	$ch-> close();
    }
*/
}
