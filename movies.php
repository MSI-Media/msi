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
    $movieScript = $_Master_movie_script;
    $optag = $_GET['tag'];
    $lang = $_GET['lang'];
    $_qs    = eliminateSortVals($_SERVER['QUERY_STRING']);
    $pic_list = array();
    $link_list = array();
    $artlink_list = array();


    if ($optag == "Last100"){
	if ($_GET['lang'] == 'E'){
	    printContents("Writeups/Last100MoviesMsg.html");
	}
	else {
	    printContents("Writeups/Last100MoviesMsg_Malayalam.html");
	}
    }
    else if ($optag == "LastAddedMoviesList"){
	$query      = "SELECT * FROM MOVIES ORDER BY M_ID DESC LIMIT 100";
    }
    else if ($optag == "LastUpdatedMoviesList"){
	$query      = "SELECT * FROM MOVIES ORDER BY M_ATS DESC LIMIT 100";
    }
    else if ($optag == 'MissingSongs'){
	$nquery = "SELECT COUNT(MOVIES.M_ID) as ccn FROM MOVIES LEFT JOIN SONGS ON MOVIES.M_ID = SONGS.M_ID  WHERE SONGS.M_ID IS NULL AND MOVIES.M_MUSICIAN != 'No Songs' ORDER BY MOVIES.M_YEAR ";
	$limit  = runQuery($nquery, 'ccn');
	$num_pages = ceil($limit / 25);
	$page_num = $_GET['page_num'];
	if ($page_num == '') { $page_num = 1; }
	$page_size = 25;
	$qs    = $_SERVER['QUERY_STRING'];
	$qrs   = explode  ('&page_num',$qs);
	$url   = $_SERVER['PHP_SELF'] . "?" . $qrs[0] ;
	$start = ($page_num - 1)  * $page_size;
	if ($start <= 0) { $start = 0; }
	$end   = $page_size;
	if (!$sortorder){
	    $sortorder = 'MOVIES.M_YEAR';
	}
	$sorttype     = $_GET['sorttype'];
	if ($sorttype == 2) { $sorttype = 'DESC'; }
	else { $sorttype = 'ASC'; }



	$query  = "SELECT * FROM MOVIES LEFT JOIN SONGS ON MOVIES.M_ID = SONGS.M_ID  WHERE SONGS.M_ID IS NULL AND MOVIES.M_MUSICIAN != 'No Songs' ORDER BY $sortorder $sorttype limit $start,$end";
    }
    else {

	$limit = $_GET['limit'];
	$alimit = $_GET['alimit'];

	$totsongs = 'Total Movies Matched';
	if ($_GET['lang'] != 'E') { $totsongs = get_uc($totsongs,''); }
	if ($limit > 0) { 
	    echo "<div class=pbiggersubtitle>$totsongs : $limit </div>";
	}
	$back  = "Go Back To The Search Filter Page";
	$sorry = "You just searched Movies Database. You can search the Non-Movies database for the same criteria you used by clicking here";
	if ($_GET['lang'] != 'E') { $sorry = get_uc($sorry,''); $back = get_uc($back,''); }
	$qs = $_SERVER['QUERY_STRING'];
	$qsorig = $qs;
	$qs = str_replace("db=movies","db=albums",$qs);
	echo "<div class=pbiggersubtitle><a href=\"$_Master_search?${qsorig}&db=movies\">$back</a><br>\n";
	if ($alimit > 0) { 
	    $qs = str_replace("alimit=","alm=",$qs);
	    $qs = str_replace("limit=","alimit=",$qs);
	    $qs = str_replace("alm=","limit=",$qs);
	    echo "<a href=\"$_Master_albumlist_script?${qs}\">$sorry</a>\n";
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

//    	$query        = "SELECT * FROM MOVIES ";
	$query        = "SELECT DISTINCT(MOVIES.M_ID),MOVIES.M_MOVIE,MOVIES.M_YEAR,MOVIES.M_COMMENTS,MOVIES.M_MUSICIAN,MOVIES.M_WRITERS,MOVIES.M_DIRECTOR FROM MOVIES ";
	$songbooks    = $_GET['songbooks'];
	$posters      = $_GET['posters'];
	$reviews      = $_GET['reviews'];
	$promos       = $_GET['promos'];
	$producer     = $_GET['producer'];
	$pictures     = $_GET['pictures'];
	$lyricist     = $_GET['lyricist'];
        $year         = $_GET['year'];
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
        $movie        = $_GET['movie'];
	$director     = $_GET['director'];
	$bgm          = $_GET['bgm'];
	$story         = $_GET['story'];
	$screenplay      = $_GET['screenplay'];
	$moviestate = $_GET['state'];
	$musician     = $_GET['musician'];
	$actors       = $_GET['actor'];
        $singers      = $_GET['singers'];
	$missing      = $_GET['missing'];
	$missingsongs = $_GET['missingsongs'];
	$startlet = $_GET['startlet'];
	$sl       = $_GET['sl'];	

	$other_fields_active = 0;
	if ($lyricist || $musician || $director || $producer || $year || $bgm || $story || $screenplay || $actors || $singers){
	   $other_fields_active = 1;
	}

	if ($lyricist != ''){	    array_push ($tags,$lyricist);	}
	if ($musician != '') { array_push ($tags,$musician); }
	if ($director != '') { array_push ($tags,$director); }
	if ($producer != '') { array_push ($tags,$producer); }
	if ($movie != '') { array_push ($tags,$movie); }
	if ($year != '' ) { array_push ($tags,$year); }
	if ($bgm != '') { array_push ($tags,$bgm); }
	if ($story != '') { array_push ($tags,$story); }
	if ($screenplay != '') { array_push ($tags,$screenplay); }
	if ($actors != '') { array_push ($tags,$actors); }
	if ($singers != '') { array_push ($tags,$singers); }

	$similar      = $_GET['similarmovie'];
	echo "<div style=\"font-size:12pt;font-family:Lucida Sans;font-weight:bold;text-align:center;\"> \n";
//	$query        = "SELECT * FROM MOVIES ";
	$query        = "SELECT DISTINCT(MOVIES.M_ID),MOVIES.M_MOVIE,MOVIES.M_YEAR,MOVIES.M_COMMENTS,MOVIES.M_MUSICIAN,MOVIES.M_WRITERS,MOVIES.M_DIRECTOR FROM MOVIES ";
	$tables       = array();
	if ($sl == 1) { 
	    array_push ($headers, printDetailHeadersOneLine ('Search Type',"Similar Words"));
	}
	if ($songbooks != ""){
	    array_push ($tables, "PPUSTHAKAM");
	    array_push ($whereClauses, " MOVIES.M_ID=PPUSTHAKAM.P_ID ");
	    array_push ($whereClauses, " PPUSTHAKAM.P_USER=\"$songbooks\" ");
	    array_push ($headers, printDetailHeadersOneLine ('Pattupusthakams',"Available"));
	    array_push ($headers, printDetailHeadersOneLine ('Pattupusthakam Owner',"$songbooks"));
	}
	if ($posters != ""){
	    array_push ($tables, "PICTURES");
	    array_push ($whereClauses, " MOVIES.M_ID=PICTURES.M_ID ");
//	    array_push ($whereClauses, " PICTURES.P_OWNER=\"$posters\" ");
	    array_push ($headers, printDetailHeadersOneLine ('Pictures',"Available"));
	}
	if ($promos != ""){
	    array_push ($tables, "PROMOS");
	    array_push ($whereClauses, " MOVIES.M_ID=PROMOS.P_ID AND PROMOS.P_STAT=\"Published\" ");
	    array_push ($headers, printDetailHeadersOneLine ('Promos',"Available"));
	}
/*
	if ($promos != ""){
	    array_push ($tables, "PROMOS");
	    array_push ($whereClauses, " MOVIES.M_ID=PROMOS.P_ID and (PROMOS.P_USER = \"$promos\" or PROMOS.P_USER like \"%,$promos\" or PROMOS.P_USER like \"$promos,%\")");
	    array_push ($headers, printDetailHeadersOneLine ('Promos Owner',"$promos"));
	}
*/
	if ($similar != ""){
	    array_push ($whereClauses, " MOVIES.M_ID=UMOVIES.M_ID and (SOUNDEX(MOVIES.M_MOVIE)=SOUNDEX(\"$similar\") or SOUNDEX(UMOVIES.M_MOVIE)=SOUNDEX(\"$similar\")) ");
	    array_push ($headers, printDetailHeadersOneLine ('Similar Sounding Names',"$similar"));
	}
	if ($reviews != ""){
	    array_push ($tables, "MD_LINKS");
	    array_push ($whereClauses, " MOVIES.M_ID=MD_LINKS.M_ID ");
	    array_push ($headers, printDetailHeadersOneLine ('Reviews',"Available"));
	}
	if ($startlet != ""){
//	    $query        = "SELECT * FROM MOVIES ";
	    $query        = "SELECT DISTINCT(MOVIES.M_ID),MOVIES.M_MOVIE,MOVIES.M_YEAR,MOVIES.M_COMMENTS,MOVIES.M_MUSICIAN,MOVIES.M_WRITERS,MOVIES.M_DIRECTOR FROM MOVIES ";
	    array_push ($whereClauses, "  M_MOVIE like \"$startlet%\"");
	    array_push ($headers, printDetailHeadersOneLine ('Movie Names Starting With',"$startlet"));
	}
	if ($pictures != ""){
	    array_push ($tables, "PICTURES");
	    array_push ($whereClauses, " MOVIES.M_ID=PICTURES.M_ID ");
	    array_push ($whereClauses, " PICTURES.P_OWNER=\"$pictures\"");
	    array_push ($headers, printDetailHeadersOneLine ('Pictures',"Available"));
	    array_push ($headers, printDetailHeadersOneLine ('Picture Owner',"$pictures"));

	}
        if ($singers != ""){
            $colname = "SONGS.S_SINGERS";
            $art = $singers;
	    array_push ($whereClauses, " ($colname=\"$art\" or $colname like \"%,$art\" or $colname like \"%,$art,%\" or $colname like \"$art,%\") ");
	    array_push ($headers, printDetailHeadersOneLine ('Singers',"$singers"));
	}
        if ($year != ""){
	    array_push ($whereClauses, " (MOVIES.M_YEAR like \"$year%\") " );
	    if (strlen($year) == 3){
		array_push ($headers, printDetailHeadersOneLine ('Year',"${year}0s.."));
	    }
	    else {
		array_push ($headers, printDetailHeadersOneLine ('Year',"${year}"));
	    }

	}
        if ($director != ""){

	    array_push ($whereClauses , addClauseToArray("MOVIES.M_DIRECTOR","$director"));
	    array_push ($headers, printDetailHeadersOneLine ('Director',"$director"));
	    $director = ltrim(rtrim($director));
	    $pic_array = addPicture("$picroot","Directors","$director","director");
	    if ($pic_array[0] != '') {
		array_push ($pic_list,$pic_array[0]);
		array_push ($link_list,$pic_array[1]);
		array_push ($artlink_list,$pic_array[2]);
	    }
	}

        if ($movie != ""){
	    array_push ($whereClauses , addClauseToArray("MOVIES.M_MOVIE","$movie"));
//	    array_push ($headers, printDetailHeadersOneLine ('Movie',"$movie"));
	}

	if ($lyricist != ""){
	    array_push ($whereClauses , addClauseToArray("MOVIES.M_WRITERS","$lyricist"));
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
	if ($singers != ""){
	    $query        = "SELECT DISTINCT(SONGS.M_ID),S_YEAR,S_MOVIE,S_MUSICIAN,S_WRITERS FROM SONGS ";
//	    array_push ($whereClauses, " SONGS.S_SINGERS = \"$singers\" " );
	    $singers = ltrim(rtrim($singers));
	    $pic_array = array();
	    $pic_array = addPicture("$picroot","Singers","$singers","singers");
	    if ($pic_array[0] != '') {
		array_push ($pic_list,$pic_array[0]);
		array_push ($link_list,$pic_array[1]);
		array_push ($artlink_list,$pic_array[2]);
	    }

	    array_push ($headers, printDetailHeadersOneLine ('Singers',"$singers"));
	}
        if ($musician != ""){
	    array_push ($whereClauses , addClauseToArray("MOVIES.M_MUSICIAN","$musician"));
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

        if ($bgm != ""){

	    if ( !in_array( "MDETAILS", $tables)){
		array_push ($tables, "MDETAILS");	
	    }	  

	    array_push ($whereClauses , addClauseToArray("MDETAILS.M_BGM","$bgm"));
	    array_push ($whereClauses, " MDETAILS.M_ID=MOVIES.M_ID ");
	    $bgm = ltrim(rtrim($bgm));

	    $pic_array = array();
	    $pic_array = addPicture("$picroot","Musicians","$bgm","bgm");
	    if ($pic_array[0] != '') {
		array_push ($pic_list,$pic_array[0]);
		array_push ($link_list,$pic_array[1]);
		array_push ($artlink_list,$pic_array[2]);
	    }

	    array_push ($headers, printDetailHeadersOneLine ('Background Music',"$bgm"));
	}


        if ($story != ""){
	    if ( !in_array( "MDETAILS", $tables)){
		array_push ($tables, "MDETAILS");	
	    }	  


	    array_push ($whereClauses , addClauseToArray("MDETAILS.M_STORY","$story"));
	    array_push ($whereClauses, " MDETAILS.M_ID=MOVIES.M_ID ");
	    $story = ltrim(rtrim($story));

	    $pic_array = array();
	    $pic_array = addPicture("$picroot","Screenplay","$story","story");
	    if ($pic_array[0] != '') {
		array_push ($pic_list,$pic_array[0]);
		array_push ($link_list,$pic_array[1]);
		array_push ($artlink_list,$pic_array[2]);
	    }


	    array_push ($headers, printDetailHeadersOneLine ('Story',"$story"));
	}


        if ($screenplay != ""){
	    if ( !in_array( "MDETAILS", $tables)){
		array_push ($tables, "MDETAILS");	
	    }	  

	    array_push ($whereClauses , addClauseToArray("MDETAILS.M_SCREENPLAY","$screenplay"));
	    array_push ($whereClauses, " MDETAILS.M_ID=MOVIES.M_ID ");
	    $screenplay = ltrim(rtrim($screenplay));

	    $pic_array = array();
	    $pic_array = addPicture("$picroot","Screenplay","$screenplay","screenplay");
	    if ($pic_array[0] != '') {
		array_push ($pic_list,$pic_array[0]);
		array_push ($link_list,$pic_array[1]);
		array_push ($artlink_list,$pic_array[2]);
	    }

	    array_push ($headers, printDetailHeadersOneLine ('Screenplay',"$screenplay"));
	}

	if ($moviestate != "" && $moviestate != "All"){
	    if ($moviestate == "Unreleased") {
		array_push ($whereClauses, " ( MOVIES.M_COMMENTS = \"*\") ");	
	    }
	    else if ($moviestate == "Dubbed") {
		array_push ($whereClauses, " ( MOVIES.M_COMMENTS = \"Dubbed\") ");	
	    }
	    else if ($moviestate == "InProduction") {
		array_push ($whereClauses, " ( MOVIES.M_COMMENTS = \"Pre\") ");	
	    }
	    else if ($moviestate == "Released"){
		array_push ($whereClauses, " ( MOVIES.M_COMMENTS = '') ");	
	    }
	    array_push ($headers, printDetailHeadersOneLine ('Movie State',"$moviestate"));
	}

        if ($producer != ""){
	    if ( !in_array( "MDETAILS", $tables)){
		array_push ($tables, "MDETAILS");	
	    }	  
	    array_push ($whereClauses , addClauseToArray("MDETAILS.M_PRODUCER","$producer"));
	    array_push ($whereClauses, " MDETAILS.M_ID=MOVIES.M_ID ");

	    $pic_array = array();
	    $pic_array = addPicture("$picroot","Producers","$producer","producer");
	    if ($pic_array[0] != '') {
		array_push ($pic_list,$pic_array[0]);
		array_push ($link_list,$pic_array[1]);
		array_push ($artlink_list,$pic_array[2]);
	    }

	    array_push ($headers, printDetailHeadersOneLine ('Producer',"$producer"));
	}

        if ($actors != ""){
	    if ( !in_array( "MDETAILS", $tables)){
		array_push ($tables, "MDETAILS");	
	    }	  

	    $pos = strpos($actors,",");	
	    if ($pos !== false) {
		$actlist = explode(',',$actors);
		foreach ($actlist as $act){
		    array_push ($whereClauses , addClauseToArray("MDETAILS.M_CAST","$act"));
		    array_push ($whereClauses, " MDETAILS.M_ID=MOVIES.M_ID ");
		    $act = ltrim(rtrim($act));
		    $pic_array = array();
		    $pic_array = addPicture("$picroot","Actors","$act","actors");
		    if ($pic_array[0] != '') {
			array_push ($pic_list,$pic_array[0]);
			array_push ($link_list,$pic_array[1]);
			array_push ($artlink_list,$pic_array[2]);
		    }
		}
	    }
	    else {
		array_push ($whereClauses , addClauseToArray("MDETAILS.M_CAST","$actors"));
		array_push ($whereClauses, " MDETAILS.M_ID=MOVIES.M_ID ");
		$actors = ltrim(rtrim($actors));
		$pic_array = array();
		$pic_array = addPicture("$picroot","Actors","$actors","actors");
		if ($pic_array[0] != '') {
		    array_push ($pic_list,$pic_array[0]);
		    array_push ($link_list,$pic_array[1]);
		    array_push ($artlink_list,$pic_array[2]);
		}
	    }
	    array_push ($headers, printDetailHeadersOneLine ('Actors',"$actors"));
	}
	if ($tables[0] != "") { $query .= " ,";}
	$query .= implode (',',$tables);
	$query .= " WHERE ";

	if ($query != ""){
            if ($singers != ''){
		if (!$sortorder){
		    $sortorder = 'SONGS.S_YEAR';
		}
            }
            else {
		if (!$sortorder){
		    $sortorder = 'MOVIES.M_YEAR';
		}
            }
	}
	$query .=  implode (' AND ',$whereClauses) . " ORDER BY $sortorder $sorttype limit $start,$end";	

	echo "<div style=\"font-size:12apt;font-family:Lucida Sans;font-weight:bold;text-align:center;\">\n";
	if ($missing != ''){
	    printShortHeaders ('Movies - Missing',"$missing");
	    if ($missing == 'pictures'){
		$query = "SELECT * FROM MOVIES LEFT JOIN PICTURES ON MOVIES.M_ID = PICTURES.M_ID WHERE PICTURES.M_ID IS NULL ORDER BY MOVIES.M_YEAR limit $start,$end";
	    }
	    else if ($missing == 'reviews'){
	        $query = "SELECT * FROM MOVIES LEFT JOIN MD_LINKS  ON MD_LINKS.M_ID = MOVIES.M_ID WHERE MD_LINKS.M_ID IS NULL ORDER BY MOVIES.M_YEAR limit $start,$end";
	    }
	}
	echo "</div>\n";
    }
    if ($_GET['show_sql'] == 1){
       echo $query, "<BR>";
    }
    echo "</div>\n";

    if ($headers != ''){
	echo "<div class=psubheading>" . implode (" | ", $headers) . "</div>";
    }

    if ($pic_list[0] != ''){
	echo "<table class=ptables><tr><td>\n";
	printPicList($pic_list,$link_list,$artlink_list);
	echo "</td></tr></table>";
    }
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
	if ($num_results == 0 && PopularMoviesAvailable($movie,'Movies') && !$other_fields_active){
	    printPopularMovies($movie,'Movies');
	}
	else if ($num_results == 0){
	    if ($_GET['lang'] == 'E'){
		printContents("Writeups/MissingSearch.html");
	    }      
	    else {
		printContents("Writeups/MissingSearch_Malayalam.html");
	    }
	}
	else {
	    if ($movie != '') { 
		if (PopularMoviesAvailable($movie,'Movies') && !$other_fields_active){
		    if (printPopularMovies($movie,'Movies')){
			$othsongs = 'Search Results';
			if ($_GET['lang'] != 'E') { $othsongs = get_uc($othsongs,''); }
			echo "<div class=pleftsubheading>$othsongs</div>";
		    }
		}
	    }


	    $i=0;

	    echo "<table class=ptables>\n";

            if ($singers != '') {

		echo "<tr class=tableheader>\n";
		printDetailCellHeadsSorts ('Movie',1,"$_Master_movielist_script?${_qs}");
		printDetailCellHeadsSortsSmall ('Year',2,"$_Master_movielist_script?${_qs}");
		printDetailCellHeadsSorts ('Musician',3,"$_Master_movielist_script?${_qs}");
		printDetailCellHeadsSorts ('Lyricist',4,"$_Master_movielist_script?${_qs}");
		echo "</tr>\n";

		while ($i < $num_results){
//		    echo "<tr class=ptableslist>\n";
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

		echo "<tr class=tableheader>\n";
		printDetailCellHeadsSorts ('Movie',1,"$_Master_movielist_script?${_qs}");
		printDetailCellHeadsSortsSmall ('Year',2,"$_Master_movielist_script?${_qs}");
		printDetailCellHeadsSorts ('Musician',3,"$_Master_movielist_script?${_qs}");
		printDetailCellHeadsSorts ('Lyricist',4,"$_Master_movielist_script?${_qs}");
		printDetailCellHeadsSorts ('Director',5,"$_Master_movielist_script?${_qs}");
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
	    if ($_GET['debug2013'] == 1) { echo "Limit: $limit , Page_size: $page_size, Num_pages: $num_pages<BR>";}
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
