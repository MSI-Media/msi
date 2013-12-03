<?php  session_start();

{
    error_reporting (E_ERROR);
    require_once("includes/utils.php");
    require_once("_includes/_searchUtils.php");
    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_moviePageUtils.php");

    $_GET['lang'] = $_SESSION['lang'];
    $_GET['encode']='utf';

    $cLink = msi_dbconnect();
    printXHeader('');
    $headers = array();
    $tags = array();
    $movieScript = $_Master_album_script;
    $optag = $_GET['tag'];
    $lang = $_GET['lang'];
    $_qs    = eliminateSortVals($_SERVER['QUERY_STRING']);
    $pic_list = array();
    $link_list = array();
    $artlink_list = array();

    if ($optag == "Last100"){
	$query        = "SELECT DISTINCT(ALBUMS.M_ID),ALBUMS.M_MOVIE,ALBUMS.M_YEAR,ALBUMS.M_COMMENTS,ALBUMS.M_MUSICIAN,ALBUMS.M_WRITERS,ALBUMS.M_DIRECTOR FROM ALBUMS ORDER BY M_ID DESC LIMIT 100";
    }
    else {

	$limit = $_GET['limit'];
	$alimit = $_GET['alimit'];

    $totsongs = 'Total Albums Matched';
	if ($_GET['lang'] != 'E') { $totsongs = get_uc($totsongs,''); }
    if ($limit > 0) { 
	echo "<div class=pbiggersubtitle>$totsongs : $limit </div>";
    }

    	$back  = "Go Back To The Search Filter Page";
	$sorry = "You just searched Albums Database. You can search the Movies database for the same criteria you used by clicking here";
	if ($_GET['lang'] != 'E') { $sorry = get_uc($sorry,''); $back = get_uc($back,''); }
	$qs = $_SERVER['QUERY_STRING'];
	$qsorig = $qs;
	$qs = str_replace("db=albums","db=movies",$qs);
	echo "<div class=pbiggersubtitle><a href=\"$_Master_search?${qsorig}&db=albums\">$back</a><br>\n";

	if ($alimit > 0) { 
	    $qs = str_replace("alimit=","alm=",$qs);
	    $qs = str_replace("limit=","alimit=",$qs);
	    $qs = str_replace("alm=","limit=",$qs);
	    echo "<a href=\"$_Master_movielist_script?${qs}\">$sorry</a>\n";
	}

	echo "</div>";

	$num_pages = ceil($limit / 25);
	$page_num = $_GET['page_num'];
	$page_size = 25;
	$qs    = $_SERVER['QUERY_STRING'];
	$qrs   = explode  ('&page_num',$qs);
	$url   = $_SERVER['PHP_SELF'] . "?" . $qrs[0] ;
	$start = ($page_num - 1)  * $page_size;
	if ($start <= 0) { $start = 0; }
	$end   = $page_size;
	$picroot  = "pics";

    	$whereClauses = array ();

//    	$query        = "SELECT * FROM ALBUMS ";
	$query        = "SELECT DISTINCT(ALBUMS.M_ID),ALBUMS.M_MOVIE,ALBUMS.M_YEAR,ALBUMS.M_COMMENTS,ALBUMS.M_MUSICIAN,ALBUMS.M_WRITERS,ALBUMS.M_DIRECTOR FROM ALBUMS ";
	$songbooks    = $_GET['songbooks'];
	$profiles     = $_GET['profile'];
	$articles     = $_GET['art'];
	$audiofiles   = $_GET['af'];
	$sortorder    = $_GET['sortorder'];
	$sorttype     = $_GET['sorttype'];
	if ($sorttype == 2) { $sorttype = 'DESC'; }
	else { $sorttype = 'ASC'; }
	if ($sortorder == 1) { $sortorder = 'M_MOVIE';}
	else if ($sortorder == 2) { $sortorder = 'M_YEAR';}
	else if ($sortorder == 3) { $sortorder = 'M_MUSICIAN';}
	else if ($sortorder == 4) { $sortorder = 'M_WRITERS';}
	else if ($sortorder == 5) { $sortorder = 'M_DIRECTOR';}
	$posters      = $_GET['posters'];
	$reviews      = $_GET['reviews'];
	$promos       = $_GET['promos'];
	$pictures     = $_GET['pictures'];
	$lyricist     = $_GET['lyricist'];
	$dlyricist     = $_GET['dlyricist'];
	$profiles     = $_GET['profile'];
        $year         = $_GET['year'];
        $movie        = $_GET['movie'];
	$director     = $_GET['director'];
	$ddirector     = $_GET['ddirector'];
	$bgm          = $_GET['bgm'];
	$genre        = $_GET['genre'];
	$musician     = $_GET['musician'];
	$dmusician     = $_GET['dmusician'];
	$actors       = $_GET['actor'];
        $singers      = $_GET['singers'];
	$missing      = $_GET['missing'];
	$startlet = $_GET['startlet'];
	$similar      = $_GET['similarmovie'];
	$sl       = $_GET['sl'];	

	$other_fields_active = 0;
	if ($lyricist || $musician || $director  || $year  || $singers){
	   $other_fields_active = 1;
	}

	if ($lyricist != ''){	    array_push ($tags,$lyricist);	}
	if ($musician != '') { array_push ($tags,$musician); }
	if ($director != '') { array_push ($tags,$director); }

	if ($dlyricist != ''){	    array_push ($tags,$dlyricist);	}
	if ($dmusician != '') { array_push ($tags,$dmusician); }
	if ($ddirector != '') { array_push ($tags,$ddirector); }

	if ($movie != '') { array_push ($tags,$movie); }
	if ($year != '' ) { array_push ($tags,$year); }
	if ($bgm != '') { array_push ($tags,$bgm); }
	if ($actors != '') { array_push ($tags,$actors); }
	if ($singers != '') { array_push ($tags,$singers); }


	echo "<div style=\"font-size:12pt;font-family:Lucida Sans;font-weight:bold;text-align:center;\"> \n";
	$query        = "SELECT * FROM ALBUMS ";
	$tables       = array();
	if ($sl == 1) { 
	    array_push ($headers, printDetailHeadersOneLine ('Search Type',"Similar Words"));
	}
	if ($songbooks != ""){
	    array_push ($tables, "PPUSTHAKAM");
	    array_push ($whereClauses, " ALBUMS.M_ID=PPUSTHAKAM.P_ID ");
	    array_push ($headers, printDetailHeadersOneLine ('Pattupusthakams',"Available"));
	}
	if ($posters != ""){
	    array_push ($tables, "APICTURES");
	    array_push ($whereClauses, " ALBUMS.M_ID=APICTURES.M_ID ");
	    array_push ($headers, printDetailHeadersOneLine ('Pictures',"Available"));
	}
	if ($promos != ""){
	    array_push ($tables, "PROMOS");
	    array_push ($whereClauses, " ALBUMS.M_ID=PROMOS.P_ID and (PROMOS.P_USER = \"$promos\" or PROMOS.P_USER like \"%,$promos\" or PROMOS.P_USER like \"$promos,%\")");
	    array_push ($headers, printDetailHeadersOneLine ('Promos Owner',"$promos"));
	}
	if ($similar != ""){
	    array_push ($whereClauses, " ALBUMS.M_ID=UALBUMS.M_ID and (SOUNDEX(ALBUMS.M_MOVIE)=SOUNDEX(\"$similar\") or SOUNDEX(UALBUMS.M_MOVIE)=SOUNDEX(\"$similar\")) ");
	    array_push ($headers, printDetailHeadersOneLine ('Similar Sounding Names',"$similar"));
	}
	if ($reviews != ""){
	    array_push ($tables, "MD_LINKS");
	    array_push ($whereClauses, " ALBUMS.M_ID=MD_LINKS.M_ID ");
	    array_push ($headers, printDetailHeadersOneLine ('Reviews',"Available"));
	}

	if ($startlet != ""){
	    $query        = "SELECT * FROM ALBUMS ";
	    array_push ($whereClauses, "  M_MOVIE like \"$startlet%\"");
	    array_push ($headers, printDetailHeadersOneLine ('Album Names Starting With',"$startlet"));
	}


	if ($pictures != ""){
	    array_push ($tables, "APICTURES");
	    array_push ($whereClauses, " ALBUMS.M_ID=APICTURES.M_ID ");
	    array_push ($whereClauses, " APICTURES.P_OWNER=\"$pictures\"");
//	    array_push ($headers, printDetailHeadersOneLine ('Pictures',"Available"));
	    array_push ($headers, printDetailHeadersOneLine ('Picture Owner',"$pictures"));
	}
        if ($singers != ""){
            $colname = "SONGS.S_SINGERS";
            $art = $singers;
	    array_push ($whereClauses, " ($colname=\"$art\" or $colname like \"%,$art\" or $colname like \"%,$art,%\" or $colname like \"$art,%\") ");
	    array_push ($headers, printDetailHeadersOneLine ('Singers',"$singers"));
	}
        if ($year != ""){
	    array_push ($whereClauses, " (ALBUMS.M_YEAR like \"$year%\") " );
	    if (strlen($year) == 3){
		array_push ($headers, printDetailHeadersOneLine ('Year',"${year}0s..."));
	    }
	    else {
		array_push ($headers, printDetailHeadersOneLine ('Year',"${year}"));
	    }
	}
        if ($director != ""){

	    array_push ($whereClauses , addClauseToArray("ALBUMS.M_DIRECTOR","$director"));

	    array_push ($headers, printDetailHeadersOneLine ('Label',"$director"));
	}
        if ($ddirector != ""){

	    array_push ($whereClauses , addClauseToArray("ALBUMS.M_DIRECTOR","$director"));
	    array_push ($whereClauses , addClauseToArray("ALBUMS.M_DIRECTOR","%Drama%"));
	    array_push ($headers, printDetailHeadersOneLine ('Label',"$director"));
	}

        if ($movie != ""){
	    array_push ($whereClauses , addClauseToArray("ALBUMS.M_MOVIE","$movie"));
	    array_push ($headers, printDetailHeadersOneLine ('Album',"$movie"));
	}
        if ($genre != ""){
	    array_push ($whereClauses , addClauseToArray("ALBUMS.M_COMMENTS","$genre"));
	    array_push ($headers, printDetailHeadersOneLine ('Genre',"$genre"));
	}
	if ($lyricist != ""){
	    array_push ($whereClauses , addClauseToArray("ALBUMS.M_WRITERS","$lyricist"));
	    $lyricist = ltrim(rtrim($lyricist));

	    $pic_array = array();
	    $pic_array = addPicture("$picroot","Lyricists","$lyricist","lyricist");
	    if ($pic_array[0] != '') {
		array_push ($pic_list,$pic_array[0]);
		array_push ($link_list,$pic_array[1]);
		array_push ($artlink_list,$pic_array[2]);
	    }

	    array_push ($headers, printDetailHeadersOneLine ('Lyricist',"$lyricist"));
	}

	if ($dlyricist != ""){
	    array_push ($whereClauses , addClauseToArray("ALBUMS.M_WRITERS","$dlyricist"));
	    array_push ($whereClauses , addClauseToArray("ALBUMS.M_DIRECTOR","%Drama%"));

	    $dlyricist = ltrim(rtrim($dlyricist));

	    $pic_array = array();
	    $pic_array = addPicture("$picroot","Lyricists","$dlyricist","lyricist");
	    if ($pic_array[0] != '') {
		array_push ($pic_list,$pic_array[0]);
		array_push ($link_list,$pic_array[1]);
		array_push ($artlink_list,$pic_array[2]);
	    }

	    array_push ($headers, printDetailHeadersOneLine ('Lyricist',"$dlyricist"));
	}






	if ($singers != ""){
	    $query        = "SELECT DISTINCT(SONGS.M_ID),S_YEAR,M_MOVIE,S_MUSICIAN,S_WRITERS FROM SONGS WHERE ";
	    array_push ($whereClauses, " SONGS.S_SINGERS = \"$singers\" " );
	    $singers = ltrim(rtrim($singers));
	    if (file_exists("$picroot/Singers/${singers}.jpg")){
		array_push ($pic_list,"$picroot/Singers/${singers}.jpg");
		array_push ($link_list,"$_Master_profile_script?artist=${singers}&category=singers");
		array_push ($artlink_list,"$singers");
	    }
	    array_push ($headers, printDetailHeadersOneLine ('Singers',"$singers"));
	}
        if ($musician != ""){
	    array_push ($whereClauses , addClauseToArray("ALBUMS.M_MUSICIAN","$musician"));
	    $musician = ltrim(rtrim($musician));

	    $pic_array = array();
	    $pic_array = addPicture("$picroot","Musicians","$musician","musician");
	    if ($pic_array[0] != '') {
		array_push ($pic_list,$pic_array[0]);
		array_push ($link_list,$pic_array[1]);
		array_push ($artlink_list,$pic_array[2]);
	    }


	    array_push ($headers, printDetailHeadersOneLine ('Musician',"$musician"));
	}

        if ($dmusician != ""){
	    array_push ($whereClauses , addClauseToArray("ALBUMS.M_MUSICIAN","$dmusician"));
	    array_push ($whereClauses , addClauseToArray("ALBUMS.M_DIRECTOR","%Drama%"));

	    $dmusician = ltrim(rtrim($dmusician));

	    $pic_array = array();
	    $pic_array = addPicture("$picroot","Musicians","$dmusician","musician");
	    if ($pic_array[0] != '') {
		array_push ($pic_list,$pic_array[0]);
		array_push ($link_list,$pic_array[1]);
		array_push ($artlink_list,$pic_array[2]);
	    }


	    array_push ($headers, printDetailHeadersOneLine ('Musician',"$dmusician"));
	}


	if ($tables[0] != "") { $query .= " ,";}
	$query .= implode (',',$tables);
	$query .= " WHERE ";

	if ($query != ""){
            if ($singers != ''){
		if (!$sortorder){
		    $sortorder = 'ASONGS.S_YEAR';
		}
            }
            else {
		if (!$sortorder){
		    $sortorder = 'ALBUMS.M_YEAR';
		}
            }
	}
	$query .=  implode (' AND ',$whereClauses) . " ORDER BY $sortorder $sorttype limit $start,$end";	

    }
    if ($_GET['show_sql'] == 1){
       echo $query, "<BR>";
    }
    echo "</div>\n";

    echo "<div class=psubheading>" . implode (" | ", $headers) . "</div>";
//    echo "</div>";
//  echo "                    <div class=\"main\">\n";
    echo "<table class=ptables><tr><td>\n";
    printPicList($pic_list,$link_list,$artlink_list);
    echo "</td></tr></table>";

    if ($profiles == 1){
	getProfiles($tags);
    }   

    if ($articles == 1){
	getArticles($tags);
    }   


    if ($audiofiles == 1){
	getAudiofils($tags);
    }

    if ($query != "") {
	$result     = mysql_query($query);
	$num_results=mysql_num_rows($result);
	$i=0;
    if ($num_results == 0){
       if ($_GET['lang'] == 'E'){
	   printContents("Writeups/MissingSearch.html");
       }      
       else {
	   printContents("Writeups/MissingSearch_Malayalam.html");
       }

    }
	else {


	    if (PopularMoviesAvailable($movie,'Albums') && !$other_fields_active){
		if (printPopularMovies($movie,'Albums')){
		    $othsongs = 'Search Results';

		    if ($_GET['lang'] != 'E') { $othsongs = get_uc($othsongs,''); }
		    echo "<div class=pleftsubheading>$othsongs</div>";
		}
	    }

	    $i=0;
	    echo "</div>";
	    echo "<table class=ptables>\n";

	    echo "<tr class=tableheader>\n";
            if ($singers != '') {
//	    printDetailCellHeads ('Album');
//	    printDetailCellHeads ('Year');
//	    printDetailCellHeads ('Musician');
//	    printDetailCellHeads ('Lyricist');

	    printDetailCellHeadsSorts ('Album',1,"$_Master_albumlist_script?${_qs}");
	    printDetailCellHeadsSortsSmall ('Year',2,"$_Master_albumlist_script?${_qs}");
	    printDetailCellHeadsSorts ('Musician',3,"$_Master_albumlist_script?${_qs}");
	    printDetailCellHeadsSorts ('Lyricist',4,"$_Master_albumlist_script?${_qs}");

	    echo "</tr>\n";
	    while ($i < $num_results){
		echo "<tr>\n";
		$mid = mysql_result($result, $i, "M_ID");
		printDetailCells (mysql_result($result, $i, "S_MOVIE"),"$movieScript?$mid",$i);
		printDetailCellsSmall (mysql_result($result, $i, "S_YEAR"),'',$i);
		printDetailCells (mysql_result($result, $i, "S_MUSICIAN"),'',$i);
		printDetailCells (mysql_result($result, $i, "S_WRITERS"),'',$i);
		echo "</tr>";
		$i++;
	    }
            }
            else {
/*
	    printDetailCellHeads ('Album');
	    printDetailCellHeads ('Year');
	    printDetailCellHeads ('Musician');
	    printDetailCellHeads ('Lyricist');
	    printDetailCellHeads ('Label');
*/
	    printDetailCellHeadsSorts ('Album',1,"$_Master_albumlist_script?${_qs}");
	    printDetailCellHeadsSortsSmall ('Year',2,"$_Master_albumlist_script?${_qs}");
	    printDetailCellHeadsSorts ('Musician',3,"$_Master_albumlist_script?${_qs}");
	    printDetailCellHeadsSorts ('Lyricist',4,"$_Master_albumlist_script?${_qs}");
	    printDetailCellHeadsSorts ('Label',5,"$_Master_albumlist_script?${_qs}");

	    echo "</tr>\n";
	    while ($i < $num_results){
		echo "<tr>\n";
		$mid = mysql_result($result, $i, "M_ID");
		printDetailCells (mysql_result($result, $i, "M_MOVIE"),"$movieScript?$mid",$i);
		printDetailCellsSmall (mysql_result($result, $i, "M_YEAR"),'',$i);
		printDetailCells (mysql_result($result, $i, "M_MUSICIAN"),'',$i);
		printDetailCells (mysql_result($result, $i, "M_WRITERS"),'',$i);
		printDetailCells (mysql_result($result, $i, "M_DIRECTOR"),'',$i);
		echo "</tr>";
		$i++;
	    }
            }
	    if ($limit > $page_size) {
		writeNavigation ($page_num,"1",$num_pages,$start,$limit,$page_size,$url,$_GET['lang']);
	    }
	    echo "</table>\n";
	}
    }
    mysql_close($cLink);
    printHtmlContents("_includes/_Footer.html");
}

?>
