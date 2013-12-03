<?php session_start();
{
    error_reporting (E_ERROR);
    require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    //require_once("includes/cache.php");
    //$ch = new cache($_GET['lang'],'Profiles');
    

    $profileScript = $_Master_profile_script;
    $profilemaster = $_Master_profile;
    $movieScript = $_Master_movielist_script  ; 
    $songScript = $_Master_songlist_script   ;
    $albumScript = $_Master_albumlist_script   ;
    $asongScript = $_Master_albumsonglist_script   ;

    $_GET['encode']='utf';

    $cLink = msi_dbconnect();
    printXHeader('');
    mysql_query("SET NAMES utf8");


    $category = $_GET['category'];
    $nodetails = false;
    $startLet = $_GET['let'];
    $sortorder='';

    if (isset($_SESSION['lang'])) {
       $lang = $_SESSION['lang'];
       $_GET['lang'] = $_SESSION['lang'];
    }
    if (!$startLet){
	if ($category == 'actors' || $category == 'producer' || $category == 'screenplay' || $category == 'story' || $category == 'camera' || $category == 'art director' || $category == 'editor' || $category == 'director' || $category == 'dialog' || $category == 'bgm' || $category == 'distribution' || $category  == 'banner' || $category == 'musician' || $category == 'lyricist'){
	    $startLet = 'Prominent';
	}
	else {
	    $startLet = 'A';
	}
    }
    if ($startLet == 'Prominent' ) {
	if ($category != 'musician' && $category != 'lyricist') {
	    $sortorder = 'numerical';
	}
    }
    if ($category == 'producer'){
        $table   = 'MDETAILS';	
	$colname = 'M_PRODUCER';
    }
    else if ($category == 'banner'){
        $table   = 'MDETAILS';	
	$colname = 'M_BANNER';
    }
    else if ($category == 'distribution'){
        $table   = 'MDETAILS';	
	$colname = 'M_DISTRIBUTION';
    }
    else if ($category == 'art director'){
        $table   = 'MDETAILS';	
	$colname = 'M_ART';
    }
    else if ($category == 'camera'){
        $table   = 'MDETAILS';	
	$colname = 'M_CAMERA';
    }
    else if ($category == 'editor'){
        $table   = 'MDETAILS';	
	$colname = 'M_EDITOR';
    }
    else if ($category == 'design'){
        $table   = 'MDETAILS';	
	$colname = 'M_DESIGN';
    }
    else if ($category == 'story'){
        $table   = 'MDETAILS';	
	$colname = 'M_STORY';
    }
    else if ($category == 'screenplay'){
        $table   = 'MDETAILS';	
	$colname = 'M_SCREENPLAY';
    }
    else if ($category == 'dialog'){
        $table   = 'MDETAILS';	
	$colname = 'M_DIALOG';
    }
    else if ($category == 'bgm'){
        $table   = 'MDETAILS';	
	$colname = 'M_BGM';
    }
    else if ($category == 'actors'){
	$table   = 'MDETAILS';	
	$colname = 'M_CAST';
    }
    else if ($category == 'musician'){
        $table   = 'MOVIES';	
	$table2  = 'ALBUMS';
	$colname = 'M_MUSICIAN';
        $stable   = 'SONGS';	
	$stable2  = 'ASONGS';
	$scolname = 'S_MUSICIAN';
    }
    else if ($category == 'lyricist'){
        $table   = 'MOVIES';	
	$table2  = 'ALBUMS';
	$colname = 'M_WRITERS';
        $stable   = 'SONGS';	
	$stable2  = 'ASONGS';
	$scolname = 'S_WRITERS';
    }

    else if ($category == 'director'){
        $table   = 'MOVIES';	
	$colname = 'M_DIRECTOR';
    }
    else if ($category == 'makeup'){
	$table   = 'MOTHER';	
	$colname = 'M_MAKEUP';
	$startLet = "";
	$nodetails = true;
    }
    else if ($category == 'sound'){
	$table   = 'MOTHER';	
	$colname = 'M_SOUND';
	$startLet = "";
	$nodetails = true;
    }
    else if ($category == 'dubbing'){
	$table   = 'MOTHER';	
	$colname = 'M_DUBBING';
	$startLet = "";
	$nodetails = true;
    }
    else if ($category == 'choreography'){
	$table   = 'MOTHER';	
	$colname = 'M_DANCE';
	$startLet = "";
	$nodetails = true;
    }
    else if ($category == 'still'){
	$table   = 'MOTHER';	
	$colname = 'M_STILL';
	$startLet = "";
	$nodetails = true;
    }
    else if ($category == 'critics'){
	$table   = 'MOTHER';	
	$colname = 'M_CRITIC';
	$startLet = "";
	$nodetails = true;
    }
    $cat_name = get_uc(ucfirst("$category"),'');
    $tag = get_uc(ucfirst("Profiles"),'');

    if ($lang == 'E'){
       $cat_name = ucfirst($category);
       $tag      = "Profiles";
    }


    if ($category != "") {
	echo "<P><div class=ptext>\n";
	if ($nodetails) {
	    //--
	}
	else {
   	   if ($category == 'actors' || $category == 'producer' || $category == 'screenplay' || $category == 'story' || $category == 'camera' || $category == 'art director' || $category == 'editor' || $category == 'director' || $category == 'dialog' || $category == 'bgm' || $category == 'distribution' || $category  == 'banner' || $category == 'musician'  || $category == 'lyricist'){
  	     $pl = "Prominent";
	      if ($_GET['lang'] != 'E') { $pl = get_uc($pl,''); }
	      echo "<a href=\"$profilemaster?category=$category&let=Prominent\" class=active>$pl</a> | ";
            }
	    foreach(range('A','Z') as $letter){
		if ($startLet == $letter){
		    echo "$letter | ";
		}
		else {
		    echo "<a href=\"$profilemaster?category=$category&let=$letter\" class=active>$letter</a> | ";
		}
	    }
	}
	echo "</div>";

      
	$artists = array();

	if ($category == 'actors'){
	    $artists = readFileToArray("php/data/Actors/${startLet}.txt");
	}
	else if ($startLet == 'Prominent'){
	    $artists = readFileToArray("php/data/Prominent/${category}.txt");
	}
	else {
            $totCount = runQuery("SELECT COUNT(DISTINCT $colname) ccn FROM $table WHERE  $colname not like \"%,%\"",'ccn');
            $msgString = "Available";
            if ($_SESSION['lang'] != 'E') { $msgString = get_uc($msgString,'');  }
            echo "<div class=pcellsbg>$msgString : $totCount</div><P>";
	    $query = "SELECT DISTINCT $colname  FROM $table WHERE $colname like \"$startLet%\" and $colname not like \"%,%\" ORDER BY $colname";
	    if ($_GET['show_sql'] == 1){
		echo  $query, "<BR>";
	    }   
	    $artists = buildArrayFromQuery("$query","$colname");

	    $query2 = "SELECT DISTINCT $colname  FROM $table WHERE $colname like \"%,$startLet%\"  ORDER BY $colname";
	    $artists2 = buildArrayFromQuery("$query2","$colname");
	    foreach ($artists2 as $a2){
		$a2elems = explode(',',$a2);
		foreach ($a2elems as $a2factors){
		    $a2factors = ltrim(rtrim($a2factors));
		    if (preg_match("/^$startLet/", $a2factors)){
			if (!in_array($a2factors, $artists)){
			    array_push ($artists, $a2factors);
			    if ($_GET['debug1'] == 1){
				echo "Adding $a2factors to $artists<BR>";
			    }
			}
		    }
		}
	    }

	}

	sort($artists);

	echo "<table width=100% align=center><tr><td width=60% valign=top>\n";
	echo "<table width=100% align=center>\n";
	if ($stable2 != ''){
	    printProfileHeaders('','','Songs','Albums','Album Songs','');
	}
	else if ($stable != ''){
	    printProfileHeaders('','','Songs','Albums','');
	}
	else if ($table2 != ''){
	    printProfileHeaders('','','Albums','');
	}
	else {
	    printProfileHeaders('','','','');
	}
	$cnt=0;
	foreach ($artists as $art){
	    $art=ltrim(rtrim($art));
	    if ($art != ""){
		if ($sortorder != 'numerical'){
		    echo "<tr>\n";
		}
		if ($nodetails) {
		    printDetailCells($art,"$profileScript?category=$category&artist=$art",$cnt);
		}
		else {
		   //$cntQry  = "SELECT COUNT(M_ID) as ccn FROM $table where $colname=\"$art\" or $colname like \"%, $art\" or $colname like \"%,$art\" or $colname like \"%,$art,%\" or $colname like \"$art,%\" or $colname like \"%$art ,%\"";

                   $cntQry = "SELECT COUNT(M_ID) as ccn FROM $table where $colname=\"$art\" or $colname like \"$art,%\" or $colname like \" $art ,%\" or $colname like \"%, $art\" or $colname like \"%,$art\" or $colname like \"%,$art,%\" or $colname like \"%, $art,%\" or $colname like \"%, $art ,%\" or $colname like \"%,$art ,%\" or $colname like \"%,$art,%\"   or $colname like \"%, $art,%\"   or $colname like \"$art,%\"  or $colname like \"%$art ,%\" ";


		    $cntQry2 = "SELECT COUNT(M_ID) as ccn FROM $table2 where $colname=\"$art\" or $colname like \"%,$art\" or $colname like \"%,$art,%\" or $colname like \"$art,%\"";
		    if ($_GET['debug'] == 1){ echo $cntQry, "<BR>"; }
		    $art_count = runQuery($cntQry, 'ccn');
		    $art_count2 = runQuery($cntQry2, 'ccn');
		    if ($stable != ''){
			$scntQry = "SELECT COUNT(S_ID) as ccn FROM $stable where $scolname=\"$art\" or $scolname like \"$art,%\" or $scolname like \" $art ,%\" or $scolname like \"%, $art\" or $scolname like \"%,$art\" or $scolname like \"%,$art,%\" or $scolname like \"%, $art,%\" or $scolname like \"%, $art ,%\" or $scolname like \"%,$art ,%\" or $scolname like \"%,$art,%\"   or $scolname like \"%, $art,%\"   or $scolname like \"$art,%\"  or $scolname like \"%$art ,%\" ";
			$sart_count = runQuery($scntQry, 'ccn');
		    }
		    if ($stable2 != ''){
			$scntQry2 = "SELECT COUNT(S_ID) as ccn FROM $stable2 where $scolname=\"$art\" or $scolname like \"$art,%\" or $scolname like \" $art ,%\" or $scolname like \"%, $art\" or $scolname like \"%,$art\" or $scolname like \"%,$art,%\" or $scolname like \"%, $art,%\" or $scolname like \"%, $art ,%\" or $scolname like \"%,$art ,%\" or $scolname like \"%,$art,%\"   or $scolname like \"%, $art,%\"   or $scolname like \"$art,%\"  or $scolname like \"%$art ,%\" ";
			$sart_count2 = runQuery($scntQry2, 'ccn');
		    }

		    if ($art_count > 0) { 
			if ($category == 'actors' || $table2 == ''){
			    if ($sortorder == 'numerical'){ 
				$numerical_basis["$art"] = $art_count;
			    }
			    else {
				printDetailCells($art,"",$cnt);
				printDetailCells($art_count,"$profileScript?category=$category&artist=$art&limit=$art_count",$cnt);	
			    }
			}  
			else {
			    printDetailCells($art,"$profileScript?category=$category&artist=$art&limit=$art_count",$cnt);
			    printDetailCells($art_count,"$movieScript?$category=$art&tag=Search&limit=$art_count",$cnt);	
			    if ($sart_count > 0){
				printDetailCells($sart_count,"$songScript?$category=$art&tag=Search&limit=$sart_count",$cnt);	
			    }
			    if ($art_count2 > 0) {
				printDetailCells($art_count2,"$albumScript?$category=$art&tag=Search&limit=$art_count2",$cnt);	
			    }
			    else{
				printDetailCells($art_count2,"",$cnt);	
			    }
			    if ($sart_count2 > 0){
				printDetailCells($sart_count2,"$asongScript?$category=$art&tag=Search&limit=$sart_count2",$cnt);	
			    }

			}
		    }
		}
		$cnt++;
		if ($sortorder != 'numerical'){
		    echo "</tr>";
		}
	    }
	}
	if ($sortorder == 'numerical'){
	    $c=0;
	    arsort($numerical_basis);
	    foreach ($numerical_basis as $art=>$art_count){
		echo "<tr>";
		printDetailCells($art,"",$cnt);
		printDetailCells($art_count,"$profileScript?category=$category&artist=$art&limit=$art_count",$cnt);	
		echo "</tr>";
		$c++;
	    }
	}
	echo "</table>";
	echo "</td><td valign=top>\n";
	echo "<div class=\"fb-like-box\" data-href=\"http://facebook.com/malayalasangeetham.info\" data-width=\"400\" data-height=\"600\" data-show-faces=\"true\" data-stream=\"true\" data-header=\"true\"></div>\n";
	echo "</td></tr></table>\n";
    }
    else {
	echo "<div class=ptext> Add missing category message</div>";
    }
    printFancyFooters();
    mysql_close($cLink);

    //$ch-> close();
}

?>
