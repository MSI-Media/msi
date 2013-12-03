<?php session_start();
{
    error_reporting (E_ERROR);
    require_once("includes/utils.php");

    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");

    require_once("includes/cache.php");

    if (isNonAdminUser()){    
	$ch = new cache($_GET['lang'],'Profiles');
    }



    $_GET['lang'] = $_SESSION['lang'];
    $profileScript = $_Master_profile_script;
    $songScript    = $_Master_songlist_script;
    $albumsongScript    = $_Master_albumsonglist_script;
    $movieScript   = $_Master_movielist_script;
    $albumScript   = $_Master_albumlist_script;

    $_GET['encode']='utf';

    $cLink = msi_dbconnect();
    printXHeader('');
    mysql_query("SET NAMES utf8");

    $startLet = $_GET['let'];
    if (!$startLet){
	$startLet = 'A';
    }


    echo "<div class=ptext>\n";
    foreach(range('A','Z') as $letter){
	if ($startLet == $letter){
	    echo "$letter | ";
	}
	else {
	    echo "<a href=\"$_Master_Singers_script?let=$letter\">$letter</a> | ";
	}
    }
    echo "</div>";

    //$singers = buildArrayFromQuery("SELECT DISTINCT S_SINGERS FROM SONGS WHERE S_SINGERS not like \"%,%\" and S_SINGERS like \"$startLet%\" ORDER BY S_SINGERS",'S_SINGERS');
    //print_r($singers);

    $singers = readFileToArray("php/data/Singers/${startLet}.txt");
    // print_r($singers);
   
    sort ($singers);
    echo "<table class=ptables>\n";

    $coltopheaders = array('Movie Songs','Album Songs');
    echo "<tr><td>&nbsp;</td>";
    foreach ($coltopheaders as $colheads){
        if ($_GET['lang'] != 'E') { $colheads = get_uc($colheads,''); }
        echo "<td class=pheading colspan=4 align=center bgcolor=#ef4f4f>$colheads</td>";
    }
    echo "</tr>";

    $colheaders = array('Singer','Movies','Songs','Solos','Duets','Albums','Songs','Solos','Duets','Classification');
    echo "<tr bgcolor=#efefef>";
    foreach ($colheaders as $colheads){
        if ($_GET['lang'] != 'E') { $colheads = get_uc($colheads,''); }
        echo "<td class=pcellheads>$colheads</td>";
    }
    echo "</tr>";

    $colname = "S_SINGERS";
    foreach ($singers as $art){
        if ($art != '') { 
        $art =ltrim(rtrim($art));
	echo "<tr>\n";
	printDetailCells($art,'',$cnt);

	$mlimit = runQuery("SELECT COUNT(DISTINCT M_ID) as ccn FROM SONGS where $colname=\"$art\" or $colname like \"%,$art\" or $colname like \"%,$art,%\" or $colname like \"$art,%\"",'ccn');
        printDetailCells($mlimit,"$movieScript?singers=$art&tag=Search&limit=$mlimit",$cnt); 

	$limit = runQuery("SELECT COUNT(S_ID) as ccn FROM SONGS where $colname=\"$art\" or $colname like \"%,$art\" or $colname like \"%,$art,%\" or $colname like \"$art,%\"",'ccn');
	printDetailCells($limit,"$songScript?singers=$art&tag=Search&limit=$limit",$cnt); 
       

	$sollimit = runQuery("SELECT COUNT(S_ID) as ccn FROM SONGS where $colname=\"$art\"",'ccn');
	printDetailCells($sollimit,"$songScript?singers=$art&singtype=solo&tag=Search&limit=$sollimit",$cnt); 

        $duetlimit = runQuery("SELECT COUNT(S_ID) as ccn FROM SONGS where $colname like \"%,$art\" or $colname like \"%,$art,%\" or $colname like \"$art,%\"",'ccn');
	printDetailCells($duetlimit,"$songScript?singers=$art&singtype=duet&tag=Search&limit=$duetlimit",$cnt); 


       //-------------------------------------------

	$amlimit = runQuery("SELECT COUNT(DISTINCT M_ID) as ccn FROM ASONGS where $colname=\"$art\" or $colname like \"%,$art\" or $colname like \"%,$art,%\" or $colname like \"$art,%\"",'ccn');
        printDetailCells($amlimit,"$albumScript?singers=$art&tag=Search&limit=$amlimit",$cnt); 

	$alimit = runQuery("SELECT COUNT(S_ID) as ccn FROM ASONGS where $colname=\"$art\" or $colname like \"%,$art\" or $colname like \"%,$art,%\" or $colname like \"$art,%\"",'ccn');
	printDetailCells($alimit,"$albumsongScript?singers=$art&tag=Search&limit=$alimit",$cnt); 
       

	$asollimit = runQuery("SELECT COUNT(S_ID) as ccn FROM ASONGS where $colname=\"$art\"",'ccn');
	printDetailCells($asollimit,"$albumsongScript?singers=$art&singtype=solo&tag=Search&limit=$asollimit",$cnt); 

        $aduetlimit = runQuery("SELECT COUNT(S_ID) as ccn FROM ASONGS where $colname like \"%,$art\" or $colname like \"%,$art,%\" or $colname like \"$art,%\"",'ccn');
	printDetailCells($aduetlimit,"$albumsongScript?singers=$art&singtype=duet&tag=Search&limit=$aduetlimit",$cnt); 

        //-------------------------------------------


	printDetailCells("Classification","$profileScript?category=singers&artist=$art&limit=$mlimit",$cnt);







	$cnt++;
	echo "</tr>";
        }
    }

    echo "</table>";
    printFancyFooters();
    mysql_close($cLink);

    if (isNonAdminUser()){    
	$ch-> close();
    }

}

?>
