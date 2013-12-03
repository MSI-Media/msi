<?php session_start();
{
    error_reporting (E_ERROR);
    require_once("_includes/_searchUtils.php");
    require_once("includes/utils.php");
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
    $songScript  = $_Master_albumsong_script;

    $optag = $_GET['tag'];
    $lang = $_GET['lang'];
    $_qs    = eliminateSortVals($_SERVER['QUERY_STRING']);
    $query = '';
	
    if ($optag == "Last100"){
	if ($_GET['lang'] == 'E'){
	    printContents("Writeups/Last100SongsMsg.html");
	}
	else {
	    printContents("Writeups/Last100SongsMsg_Malayalam.html");
	}
    }
    else if ($optag == "LastAddedSongsList"){
	$query      = "SELECT * FROM ASONGS ORDER BY S_ID DESC LIMIT 100";
    }
    else if ($optag == "LastUpdatedSongsList"){
	$query      = "SELECT * FROM ASONGS ORDER BY S_ATS DESC LIMIT 100";
    }
    else if ($optag == "Search"){
	$whereClauses = array ();
	$query      = "SELECT * FROM ASONGS WHERE ";
	$mid = $_GET['mid'];
	$sl = $_GET['sl'];
	$cat = $_GET['category'];
	$art = $_GET['artist'];
	$rag = $_GET['raga'];
	$mus = $_GET['musician'];
	$mov = $_GET['movie'];
	$lown = $_GET['lyricsowner'];
	$lavail = $_GET['lyrics_avail'];
	$profiles     = $_GET['profile'];
	$articles     = $_GET['art'];
	$song = $_GET['song'];	     
	$audiofiles   = $_GET['af'];
	$sortorder    = $_GET['sortorder'];
	$sorttype     = $_GET['sorttype'];
	if ($sorttype == 2) { $sorttype = 'DESC'; }
	else { $sorttype = 'ASC'; }
	if ($sortorder == 1) { $sortorder = 'S_SONG';}
	else if ($sortorder == 2) { $sortorder = 'S_MOVIE';}
	else if ($sortorder == 3 || !$sortorder) { $sortorder = 'S_YEAR';}
	else if ($sortorder == 4) { $sortorder = 'S_MUSICIAN';}
	else if ($sortorder == 5) { $sortorder = 'S_WRITERS';}
	else if ($sortorder == 6) { $sortorder = 'S_SINGERS';}
	$mlavail = $_GET['mal_lyrics_avail'];
	$kavail = $_GET['karaoke_avail'];
	$startlet = $_GET['startlet'];
	$genre = $_GET['genre'];
	$audio = $_GET['audio'];
	$video = $_GET['videos'];
	$karaoke = $_GET['karaoke'];
	$unicode = $_GET['unicode'];
	$lyrics  = $_GET['lyrics'];

	$singstate = $_GET['songstate'];

	$naudio = $_GET['naudio'];
	$nvideo = $_GET['nvideo'];
	$nmlyrics = $_GET['nunicode'];
	$nlyrics  = $_GET['nlyrics'];


	$cown = $_GET['clipsowner'];
	$kown = $_GET['karaokeowner'];
	$vown = $_GET['videosowner'];
	$lyr = $_GET['lyricist'];

	$other_fields_active = 0;
	if ($actors || $cat || $art || $mus || $lyr || $sin || $yr) {
	   $other_fields_active = 1;
	}

	if (!$lyr && $_GET['writers']){
	   $lyr = $_GET['writers'];
	}
	$sin = $_GET['singers'];
        $singtype = $_GET['singtype'];
	$yr  = $_GET['year'];
	$limit = $_GET['limit'];
	$alimit = $_GET['alimit'];
	$similar = $_GET['similarsong'];
/*
	if (!$limit){
	    $limit = 100;
	}
*/
    $totsongs = 'Total Songs Matched';
	if ($_GET['lang'] != 'E') { $totsongs = get_uc($totsongs,''); }
    if ($limit > 0) { 
	echo "<div class=pbiggersubtitle>$totsongs : $limit </div>";
    }


	$back  = "Go Back To The Search Filter Page";
	$sorry = "You just searched Albums Database. You can search the Movies database for the same criteria you used by clicking here";
	if ($_GET['lang'] != 'E') { $sorry = get_uc($sorry,''); $back = get_uc($back,''); }
	$qs = $_SERVER['QUERY_STRING'];
	$qsorig = $qs;
	$qs = str_replace("db=albumsongs","db=movieongs",$qs);
	echo "<div class=pbiggersubtitle><a href=\"$_Master_search?${qsorig}&db=albumsongs\">$back</a><br>\n";
	
	if ($alimit > 0) { 
	    $qs = str_replace("alimit=","alm=",$qs);
	    $qs = str_replace("limit=","alimit=",$qs);
	    $qs = str_replace("alm=","limit=",$qs);
	    echo "<a href=\"$_Master_songlist_script?${qs}\">$sorry</a>\n";
	}
	echo "</div>";

	if ($lyr != ''){	    array_push ($tags,$lyr);	}
	if ($mus != '') { array_push ($tags,$mus); }
	if ($movie != '') { array_push ($tags,$movie); }
	if ($year != '' ) { array_push ($tags,$year); }
	if ($bgm != '') { array_push ($tags,$bgm); }
	if ($actors != '') { array_push ($tags,$actors); }
	if ($sin != '') { array_push ($tags,$sin); }

	$missing = $_GET['missing'];
	$num_pages = ceil($limit / 25);
	$page_num = $_GET['page_num'];
	$page_size = 25;
	$qs    = $_SERVER['QUERY_STRING'];
	$qrs   = explode  ('&page_num',$qs);
	$url   = $_SERVER['PHP_SELF'] . "?" . $qrs[0] ;
	$start = ($page_num - 1)  * $page_size;
	if ($start <= 0) { $start = 0; }

	if ($_GET['debug'] == 1) {
	    echo "$start is the limit start<BR>";
	}

	$end   = $page_size;
	$pic_list = array();
	$link_list = array();
	$artlink_list = array();

	$picroot  = "pics";
	if ($sl == 1) { 
	    array_push ($headers, printDetailHeadersOneLine ('Search Type',"Similar Words"));
	}
	echo "<div style=\"font-size:12apt;font-family:Lucida Sans;font-weight:bold;text-align:center;\">\n";
	if ($cat != "" && $art != ""){
	    if ($cat == 'raga'){
		array_push ($whereClauses, "( S_RAGA like \"$art\" or S_RAGA like \"%,$art\" or S_RAGA like \"$art,%\" or S_RAGA like \"%,$art,%\") ");
		array_push ($headers, printDetailHeadersOneLine ('Raga',"$art"));
	    }

	    else if ($cat == 'movie'){
		array_push ($whereClauses, "( S_MOVIE = \"$mov\") ");
		array_push ($headers, printDetailHeadersOneLine ('Album',"$art"));
	    }
	    else if ($cat == 'musician'){
			array_push ($whereClauses, "( S_MUSICIAN like \"$art\" or S_MUSICIAN like \"%,$art\" or S_MUSICIAN like \"$art,%\" or S_MUSICIAN like \"%,$art,%\") ");
		     $multi_mus = explode(',',$art);
	         foreach ($multi_mus as $ms){
				$ms = ltrim(rtrim($ms));
				if (file_exists("$picroot/Musicians/${ms}.jpg")){
				    if (!in_array("$picroot/Musicians/${ms}.jpg",$pic_list)){
					array_push ($pic_list,"$picroot/Musicians/${ms}.jpg");
				    }
					array_push ($link_list,"$_Master_profile_script?artist=${ms}&category=musician");
					array_push ($artlink_list,"$ms");
				}
			}
			array_push ($headers, printDetailHeadersOneLine ('Musician',"$art"));
	    }
	    else if ($cat == 'lyricist'){
			array_push ($whereClauses, "( S_WRITERS like \"$art\" or S_WRITERS like \"%,$art\" or S_WRITERS like \"$art,%\" or S_WRITERS like \"%,$art,%\") ");
			
			$multi_lyr = explode(',',$art);
	                foreach ($multi_lyr as $ml){
				$ms = ltrim(rtrim($ml));
                                $ms = stripTrads($ms);
				if (file_exists("$picroot/Lyricists/${ms}.jpg")){
				    if (!in_array("$picroot/Lyricists/${ms}.jpg",$pic_list)){
					array_push ($pic_list,"$picroot/Lyricists/${ms}.jpg");
				    }
					array_push ($link_list,"$_Master_profile_script?artist=${ms}&category=lyricist");
					array_push ($artlink_list,"$ms");
				}
			}
			array_push ($headers, printDetailHeadersOneLine ('Lyricist',"$art"));
	    }
	    else if ($cat == 'year'){
			array_push ($whereClauses, "S_YEAR = \"$art\"");
			array_push ($headers, printDetailHeadersOneLine ('Year',"$art"));
	    }
	    else if ($cat == 'singers'){
            if ($singtype == 'solo') {
               array_push ($whereClauses, "( S_SINGERS = \"$art\" ) ");
               array_push ($headers, printDetailHeadersOneLine ('Singer Type',"$singtype"));
            }
            else if ($singtype == 'duet'){
          	    array_push ($whereClauses, "( S_SINGERS like \"%,$art\" or S_SINGERS like \"$art,%\" or S_SINGERS like \"%,$art,%\") ");
                    array_push ($headers, printDetailHeadersOneLine ('Singer Type',"$singtype"));
            }
            else {
          	    array_push ($whereClauses, "( S_SINGERS like \"$art\" or S_SINGERS like \"%,$art\" or S_SINGERS like \"$art,%\" or S_SINGERS like \"%,$art,%\") ");
            }
	    $multi_singers = explode(',',$art);	
	    foreach ($multi_singers as $ms){
		$ms = ltrim(rtrim($ms));
		if (file_exists("$picroot/Singers/${ms}.jpg")){
		    if (!in_array("$picroot/Singers/${ms}.jpg",$pic_list)){
			array_push ($pic_list,"$picroot/Singers/${ms}.jpg");
		    }
		    array_push ($link_list,"$_Master_profile_script?artist=${ms}&category=singers");
		    array_push ($artlink_list,"$ms");
		}
	    }
	    array_push ($headers, printDetailHeadersOneLine ('Singer',"$art"));

	    }
	}
	if ($mid != ''){
	    array_push ($whereClauses, " M_ID = $mid ");
	    array_push ($headers, printDetailHeadersOneLine ('Movie ID',"$mid"));
	}
	if ($lavail != ''){
	    array_push ($whereClauses, " S_LYR = \"$lavail\" ");
	    array_push ($headers, printDetailHeadersOneLine ('Lyrics Available',"$lavail"));
	}
	if ($kavail != ''){

            $query      = "SELECT * FROM ASONGS,ALBUM_KCLIPS WHERE ";
	    array_push ($whereClauses, " S_KCLIP = \"$kavail\" ");
            array_push ($whereClauses,  " ALBUM_KCLIPS.S_ID = ASONGS.S_ID ");
	    array_push ($headers, printDetailHeadersOneLine ('Karaoke Available',"$kavail"));
	}
	if ($mlavail != ''){
	    array_push ($whereClauses, " S_MLYR = \"$mlavail\" ");
	    array_push ($headers, printDetailHeadersOneLine ('Unicode Lyrics Available',"$mlavail"));
	}
	if ($rag != ''){
	    $pos = stripos($rag, "Raagamalika");
	    if ($pos !== false) {
		array_push ($whereClauses, "( S_RAGA like \"$rag%\" )");
	    }	       
	    else {
		array_push ($whereClauses, "( S_RAGA like \"$rag\" or S_RAGA like \"%,$rag\" or S_RAGA like \"$rag,%\" or S_RAGA like \"%,$rag,%\") ");
	    }
	    array_push ($headers, printDetailHeadersOneLine ('Raga',"$rag"));
	}
	if ($lown != ''){
	    array_push ($whereClauses, "(S_LYROWNER like \"$lown\" or S_LYROWNER like \"%,$lown\" or S_LYROWNER like \"$lown,%\" or S_LYROWNER like \"%,$lown,%\")");
	    array_push ($headers, printDetailHeadersOneLine ('Lyrics Owner',"$lown"));
	}
	if ($mov != ''){
	    $precise = getPreciseMovieSongName($mov,'albums');
	    if ($precise && $sl != 1){
		array_push ($whereClauses , " S_MOVIE like \"$mov\" ");
	    }
	    else {
		array_push ($whereClauses, addClauseToArray("S_MOVIE",$mov));
	    }
	    array_push ($headers, printDetailHeadersOneLine ('Album',"$mov"));
	}

	if ($song != ''){
	    $precise = getPreciseMovieSongName($song,'albumsongs');
	    if ($precise && $sl != 1){
		array_push ($whereClauses , " S_SONG like \"$song\" ");
	    }
	    else {
		array_push ($whereClauses , addClauseToArray ('S_SONG',"$song"));
    }
	    array_push ($headers, printDetailHeadersOneLine ('Song',"$song"));
	}

	if ($singstate != "" && $singstate != "All"){
	    if ($singstate == 'Duets'){
		array_push ($whereClauses, " (S_SINGERS like \"%,%\") ");	
	    }
	    else if ($singstate == "Solos"){
		array_push ($whereClauses, " (S_SINGERS not like \"%,%\") ");	
	    }
	    array_push ($headers, printDetailHeadersOneLine ('Song Status',"$singstate"));
	}

	if ($genre != ''){
	    array_push ($whereClauses, "(S_GENRE = \"$genre\")");
	    array_push ($headers, printDetailHeadersOneLine ('Genre',"$genre"));
	}
	if ($cown != ''){
	    array_push ($whereClauses, "(S_CLIPOWN like \"$cown\" or S_CLIPOWN like \"%,$cown\" or S_CLIPOWN like \"$cown,%\" or S_CLIPOWN like \"%,$cown,%\")");
	    array_push ($headers, printDetailHeadersOneLine ('Clips Owner',"$cown"));
	}
	if ($startlet != ''){
	    array_push ($whereClauses, "(S_SONG like \"$startlet%\")");
	    array_push ($headers, printDetailHeadersOneLine ('Songs Starting with Alphabet',"$startlet"));
	}
	if ($vown != ''){
	    $query      = "SELECT * FROM ASONGS,ALBUM_UTUBE WHERE ";
	    array_push ($whereClauses, " ASONGS.S_ID=ALBUM_UTUBE.UT_ID and ALBUM_UTUBE.UT_OWN=\"$vown\" and ALBUM_UTUBE.UT_STAT=\"Published\" ");
	    array_push ($headers, printDetailHeadersOneLine ('Videos Owner',"$vown"));
	}
	if ($similar != ''){
		$query = "SELECT S_ID,S_SONG,S_MOVIE,S_YEAR,S_WRITERS,S_MUSICIAN FROM ASONGS,USONGS WHERE "; 
		$los = strlen($similar);
//		array_push ($whereClauses, " ASONGS.S_ID=USONGS.U_ID and (SOUNDEX(ASONGS.S_SONG)=SOUNDEX(\"$similar\") or SOUNDEX(USONGS.U_SONG)=SOUNDEX(\"$similar\") or SOUNDEX(SUBSTRING(ASONGS.S_SONG,1,$los))=SOUNDEX(\"$similar\")) ");
		$similar2 = str_replace('r','l',$similar);
		$los2 = strlen($similar2);
		array_push ($whereClauses," ASONGS.S_ID=USONGS.U_ID and ((SOUNDEX(ASONGS.S_SONG)=SOUNDEX(\"$similar\") or SOUNDEX(USONGS.U_SONG)=SOUNDEX(\"$similar\") or SOUNDEX(SUBSTR(ASONGS.S_SONG,1,$los)) = SOUNDEX(\"$similar\")) OR (SOUNDEX(ASONGS.S_SONG)=SOUNDEX(\"$similar2\") or SOUNDEX(USONGS.U_SONG)=SOUNDEX(\"$similar2\") or SOUNDEX(SUBSTR(ASONGS.S_SONG,1,$los2)) = SOUNDEX(\"$similar2\")))");
		array_push ($headers, printDetailHeadersOneLine ('Similar Sounding Songs',"$similar"));
	}
	if ($kown != ''){

            $query      = "SELECT * FROM ASONGS,ALBUM_KCLIPS WHERE ";
            array_push ($whereClauses,  " ALBUM_KCLIPS.S_ID = ASONGS.S_ID ");
	    array_push ($whereClauses, "(S_KCLIPOWN like \"$kown\" or S_KCLIPOWN like \"%,$kown\" or S_KCLIPOWN like \"$kown,%\" or S_KCLIPOWN like \"%,$kown,%\")");
	    array_push ($headers, printDetailHeadersOneLine ('Karaokes Owner',"$kown"));
	}
	if ($mus != ''){
//	    array_push ($whereClauses, "( S_MUSICIAN like \"$mus\" or S_MUSICIAN like \"%,$mus\" or S_MUSICIAN like \"$mus,%\" or S_MUSICIAN like \"%,$mus,%\") ");
            array_push ($whereClauses,  addClauseToArray ('S_MUSICIAN',"$mus"));

	   
	    $multi_mus = explode(',',$mus);
	    $pic_array=array();
	    foreach ($multi_mus as $ms){
			$ms = ltrim(rtrim($ms));

		$pic_array = addPicture("$picroot","Musicians","$ms","musician");
		     if ($pic_array[0] != '') {
			 array_push ($pic_list,$pic_array[0]);
			 array_push ($link_list,$pic_array[1]);
			 array_push ($artlink_list,$pic_array[2]);
		     }

	    }
array_push ($headers, printDetailHeadersOneLine ('Musician',"$mus"));

	}
	if ($audio == 1){
            array_push ($whereClauses,  " S_CLIP = 'Y' ");
	    array_push ($headers, printDetailHeadersOneLine ('Audio',"Available"));
	}
	if ($video == 1){
//	    array_push ($tables, "ALBUM_UTUBE");
            $query      = "SELECT * FROM ASONGS,ALBUM_UTUBE WHERE ";
	    array_push ($whereClauses, " (ASONGS.S_ID=ALBUM_UTUBE.UT_ID and ALBUM_UTUBE.UT_STAT=\"Published\") ");
array_push ($headers, printDetailHeadersOneLine ('Video',"Available"));
	}
	if ($karaoke == 1){
            $query      = "SELECT * FROM ASONGS,ALBUM_KCLIPS WHERE ";
            array_push ($whereClauses,  " ALBUM_KCLIPS.S_KCLIP = 'Y' ");
            array_push ($whereClauses,  " ALBUM_KCLIPS.S_ID = ASONGS.S_ID ");
	    array_push ($headers, printDetailHeadersOneLine ('Karaoke',"Available"));
	}
	if ($unicode == 1){
            array_push ($whereClauses,  " S_MLYR = 'Y' ");
	    array_push ($headers, printDetailHeadersOneLine ('Unicode Lyrics',"Available"));
	}
	if ($lyrics == 1){
            array_push ($whereClauses,  " S_LYR = 'Y' ");
	    array_push ($headers, printDetailHeadersOneLine ('Lyrics',"Available"));
	}

	if ($nvideo == 1){
            $query      = "SELECT * FROM ASONGS,ALBUM_UTUBE WHERE ";
	    array_push ($whereClauses, " (ASONGS.S_ID=ALBUM_UTUBE.UT_ID and ALBUM_UTUBE.UT_STAT != \"Published\") ");
	    array_push ($headers, printDetailHeadersOneLine ('Video',"Not Available"));
	}
	if ($naudio == 1){
	    array_push ($whereClauses, " (ASONGS.S_CLIP != \"Y\") ");
	    array_push ($headers, printDetailHeadersOneLine ('Audio',"Not Available"));
	}
	if ($nlyrics == 1){
	    array_push ($whereClauses, " (ASONGS.S_LYR != \"Y\") ");
	    array_push ($headers, printDetailHeadersOneLine ('Lyrics',"Not Available"));
	}
	if ($nmlyrics == 1){
	    array_push ($whereClauses, " (ASONGS.S_MLYR != \"Y\") ");
	    array_push ($headers, printDetailHeadersOneLine ('Unicode',"Not Available"));
	}


	if ($lyr != ''){
//	    array_push ($whereClauses, "( S_WRITERS like \"$lyr\" or S_WRITERS like \"%,$lyr\" or S_WRITERS like \"$lyr,%\" or S_WRITERS like \"%,$lyr,%\") ");
            array_push ($whereClauses , addClauseToArray ('S_WRITERS',"$lyr"));
	    $multi_lyr = explode(',',$lyr);	    
	    $pic_array = array();
	    foreach ($multi_lyr as $ly) {
		$ly        = ltrim(rtrim($ly));
                $ly = stripTrads($ly);

		$pic_array = addPicture("$picroot","Lyricists","$ly","lyricist");
		     if ($pic_array[0] != '') {
			 array_push ($pic_list,$pic_array[0]);
			 array_push ($link_list,$pic_array[1]);
			 array_push ($artlink_list,$pic_array[2]);
		     }


		array_push ($headers, printDetailHeadersOneLine ('Lyricist',"$ly"));
	    }
	}
	if ($yr != ''){
	    array_push ($whereClauses, "S_YEAR like \"$yr%\"");
	    if (strlen($yr) == 3){
		array_push ($headers, printDetailHeadersOneLine ('Year',"${yr}0s.."));
	    }
	    else {
		array_push ($headers, printDetailHeadersOneLine ('Year',"${yr}"));
	    }
	}
	if ($sin != ''){
            if ($singtype == 'solo') {
//               array_push ($whereClauses, "( S_SINGERS = \"$sin\" ) ");
               array_push ($whereClauses , addClauseToArray ('S_SINGERS',"$sin"));
array_push ($headers, printDetailHeadersOneLine ('Singer Type',"$singtype"));
            }
            else if ($singtype == 'duet'){
//		array_push ($whereClauses, "( S_SINGERS like \"%,$sin\" or S_SINGERS like \"$sin,%\" or S_SINGERS like \"%,$sin,%\") ");
                array_push ($whereClauses , addClauseToArray ('S_SINGERS',"$sin"));
array_push ($headers, printDetailHeadersOneLine ('Singer Type',"$singtype"));
            }
            else {
//          	    array_push ($whereClauses, "( S_SINGERS like \"$sin\" or S_SINGERS like \"%,$sin\" or S_SINGERS like \"$sin,%\" or S_SINGERS like \"%,$sin,%\") ");
		    array_push ($whereClauses,  addClauseToArray ('S_SINGERS',"$sin"));	
            }
	    $multi_singers = explode(',',$sin);
//	    if ($sl != 1) { 
		$pic_array = array();
		foreach ($multi_singers as $ms){
		    $ms = ltrim(rtrim($ms));
		    $pic_array = addPicture("$picroot","Singers","$ms","singers");
		    if ($pic_array[0] != '') {
			array_push ($pic_list,$pic_array[0]);
			array_push ($link_list,$pic_array[1]);
			array_push ($artlink_list,$pic_array[2]);
		    }
		}
//	    }
	    array_push ($headers, printDetailHeadersOneLine ('Singer',"$sin"));
	}
	if ($mid == 0) {
	    $query .=  implode (' AND ',$whereClauses) . " order by $sortorder $sorttype limit $start,$end";
	}
	else if ($whereClauses != '') {
	    $query .=  implode (' AND ',$whereClauses)  . " ORDER by $sortorder $sorttype";
	}

	if ($missing != ''){
	    array_push ($headers, printDetailHeadersOneLine ('Movie Songs - Missing',"$missing"));
	    if ($missing == 'ml'){
		$query = "SELECT * from ASONGS WHERE S_LYR  != 'Y'";
	    }
	    else if ($missing == 'mlv'){
		$query = "SELECT * from ASONGS,ALBUM_UTUBE WHERE S_LYR  != 'Y' and ALBUM_UTUBE.UT_STAT='Published' and ALBUM_UTUBE.UT_ID=ASONGS.S_ID ORDER BY $sortorder";
	    }
	    else if ($missing == 'ul'){
		$query = "SELECT * from ASONGS WHERE S_MLYR != 'Y'";
	    }
	    else if ($missing == 'ulv'){
		$query = "SELECT * from ASONGS,ALBUM_UTUBE WHERE S_MLYR  != 'Y' and ALBUM_UTUBE.UT_STAT='Published' and ALBUM_UTUBE.UT_ID=ASONGS.S_ID ORDER BY $sortorder";
	    }
	    else if ($missing == 'audio'){
		$query = "SELECT * from ASONGS WHERE S_CLIP != 'Y'";
	    }
	    else if ($missing == 'video'){
		$query = "SELECT *  FROM ASONGS LEFT JOIN ALBUM_UTUBE  ON ALBUM_UTUBE.UT_ID = ASONGS.S_ID WHERE (ALBUM_UTUBE.UT_ID IS NULL or ALBUM_UTUBE.UT_URL = \"\" or ALBUM_UTUBE.UT_STAT != 'Published')";
	    }
	    $query .= " limit $start,$end";
	}
    }

    echo " </div>\n";

    if ($_GET['show_sql'] == 1){
       echo $query, "<BR>";
    }
    $result        = mysql_query($query);
    $num_results   = mysql_num_rows($result);
    echo "<div class=psubheading>" . implode (" | ", $headers) . "</div>";
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


    $i=0;
    if ($num_results == 0 && PopularSongsAvailable($song,'Albums') && !$other_fields_active){
    printPopularSongs($song,'Albums');
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
	if (PopularSongsAvailable($song,'Albums') && !$other_fields_active){
	    if (printPopularSongs($song,'Albums')){		
		$othsongs = 'Search Results';
		if ($_GET['lang'] != 'E') { $othsongs = get_uc($othsongs,''); }
		echo "<div class=pleftsubheading>$othsongs</div>";
	    }
	}


	$i=0;

	echo "<table class=ptables>\n";

//	printPicList($pic_list,$link_list,$artlink_list);

	echo "<tr class=tableheader>\n";
/*
	printDetailCellHeads ('Song');
	printDetailCellHeads ('Album');
	$missing=$_GET['missing'];
	if ($missing != ''){
	    if ($missing == 'mlv' || $missing == 'ulv'){
		printDetailCellHeads ('Listen');
	    }
	}

	printDetailCellHeads ('Year');
	printDetailCellHeads ('Musician');
	printDetailCellHeads ('Lyricist');
	printDetailCellHeads ('Singers');
*/
	printDetailCellHeadsSorts ('Song',1,"$songScript?${_qs}");
	printDetailCellHeadsSorts ('Album',2,"$songScript?${_qs}");
	printDetailCellHeadsSortsSmall ('Year',3,"$songScript?${_qs}");
	printDetailCellHeadsSorts ('Musician',4,"$songScript?${_qs}");
	printDetailCellHeadsSorts ('Lyricist',5,"$songScript?${_qs}");
	printDetailCellHeadsSorts ('Singers',6,"$songScript?${_qs}");

	echo "</tr>\n";
	while ($i < $num_results){
	    echo "<tr class=ptableslist>\n";
	    $sid = mysql_result($result, $i, "S_ID");
	    $mid = mysql_result($result, $i, "M_ID");
	    printDetailCells (mysql_result($result, $i, "S_SONG"),"$songScript?$sid",$i);
	    printDetailCells (mysql_result($result, $i, "S_MOVIE"),"$movieScript?$mid",$i);

	if ($missing != ''){
	    if ($missing == 'mlv' || $missing == 'ulv'){
		printDetailCells (provideListenString($sid,mysql_result($result, $i, "S_CLIP"), 'Movies'),'','');
	    }
	}


	    printDetailCellsSmall (mysql_result($result, $i, "S_YEAR"),'',$i);
	    printDetailCells (mysql_result($result, $i, "S_MUSICIAN"),'',$i);
	    printDetailCells (mysql_result($result, $i, "S_WRITERS"),'',$i);
	    printDetailCells (mysql_result($result, $i, "S_SINGERS"),'',$i);
	    echo "</tr>";
	    $i++;
	}
	if ($limit > $page_size) {
	    writeNavigation ($page_num,"1",$num_pages,$start,$limit,$page_size,$url,$_GET['lang']);
	}
	echo "</table>\n";



    }
    mysql_close($cLink);
    printHtmlContents("_includes/_Footer.html");
}

function stripTrads($ugenre) 
{
    $ugenre = str_replace('Traditional','',$ugenre);
    $ugenre = str_replace('പരമ്പരാഗതം','',$ugenre);
    $ugenre = str_replace('(','',$ugenre);
    $ugenre = str_replace(')','',$ugenre);
   
    return ltrim(rtrim($ugenre));
}
?>
