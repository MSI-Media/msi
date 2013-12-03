<?php session_start();
{
    error_reporting (E_ERROR);
    require_once("includes/utils.php");

    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_searchUtils.php");
    require_once("_includes/_moviePageUtils.php");


    $_GET['lang'] = $_SESSION['lang'];
    $_GET['encode']='utf';

    $cLink = msi_dbconnect();
    printXHeader('vidlist');

    $headers = array();
    $tags = array();

    $movieScript = $_Master_movie_script;
    $songScript  = $_Master_song_script;

    $optag = $_GET['tag'];
    $lang = $_GET['lang'];

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
	$query      = "SELECT * FROM SONGS ORDER BY S_ID DESC LIMIT 100";
    }
    else if ($optag == "LastUpdatedSongsList"){
	$query      = "SELECT * FROM SONGS ORDER BY S_ATS DESC LIMIT 100";
    }
    else if ($optag == "Search"){
	$whereClauses = array ();
	$query      = "SELECT * FROM SONGS WHERE ";
	$mid = $_GET['mid'];
	$sl = $_GET['sl'];
	$cat = $_GET['category'];
	$art = $_GET['artist'];
	$rag = $_GET['raga'];
	$mus = $_GET['musician'];
	$song = $_GET['song'];	     
	$mov = $_GET['movie'];
	$lown = $_GET['lyricsowner'];
	$actors = $_GET['actor'];
	$profiles     = $_GET['profile'];
	$articles     = $_GET['art'];
	$audiofiles   = $_GET['af'];
	$lavail = $_GET['lyrics_avail'];
	$mlavail = $_GET['mal_lyrics_avail'];
	$kavail = $_GET['karaoke_avail'];
	$startlet = $_GET['startlet'];
	$genre = $_GET['genre'];

	$audio = $_GET['audio'];
	$video = $_GET['videos'];
	$karaoke = $_GET['karaoke'];
	$unicode = $_GET['unicode'];
	$lyrics  = $_GET['lyrics'];

	$naudio = $_GET['naudio'];
	$nvideo = $_GET['nvideo'];
	$nkaraoke = $_GET['nkaraoke'];
	$nmlyrics = $_GET['nunicode'];
	$nlyrics  = $_GET['nlyrics'];

	$moviestate = $_GET['state'];
	$singstate = $_GET['songstate'];

	$cown = $_GET['clipsowner'];
	$kown = $_GET['karaokeowner'];
	$vown = $_GET['videosowner'];
	$lyr = $_GET['lyricist'];
	if (!$lyr && $_GET['writers']){
	   $lyr = $_GET['writers'];
	}
	$sin = $_GET['singers'];
        $singtype = $_GET['singtype'];
	$yr  = $_GET['year'];
	$limit = $_GET['limit'];
	$alimit = $_GET['alimit'];
	$similar = $_GET['similarsong'];
	$similar = $sl;
/*
	if (!$limit){
	    $limit = 100;
	}
*/
    $video=1;
    $totsongs = 'Total Songs Matched';
	if ($_GET['lang'] != 'E') { $totsongs = get_uc($totsongs,''); }
    if ($limit > 0) { 
	echo "<div class=pbiggersubtitle>$totsongs : $limit </div>";
    }


    $share_this_playlist = "Share This Playlist";
	$back  = "Go Back To The Search Filter Page";
//	$sorry = "You just searched Movies Database. You can search the Non-Movies database for the same criteria you used by clicking here";
	if ($_GET['lang'] != 'E') { $sorry = get_uc($sorry,''); $back = get_uc($back,''); }
	$qs = $_SERVER['QUERY_STRING'];
	$qsorig = $qs;
	$qs = str_replace("db=moviesongs","db=albumsongs",$qs);
	echo "<div class=pbiggersubtitle><a href=\"$_Master_playlist?${qsorig}\">$back</a><br>\n";
	if ($_GET['noshare'] != 1) {
	    echo "<div align=center><a href=\"$_Master_shareplay?${qsorig}\"><img src=\"icons/share.png\" height=30 alt=\"$share_this_playlist\"></a></div>";
	}
	if ($alimit > 0) { 
	    $qs = str_replace("alimit=","alm=",$qs);
	    $qs = str_replace("limit=","alimit=",$qs);
	    $qs = str_replace("alm=","limit=",$qs);
	}
	echo "</div>";


	$missing = $_GET['missing'];
	$num_pages = ceil($limit / 25);
	$page_num = $_GET['page_num'];
	$page_size = 25;
	$qs    = $_SERVER['QUERY_STRING'];
	$qrs   = explode  ('&page_num',$qs);
	$url   = $_SERVER['PHP_SELF'] . "?" . $qrs[0] ;
	$url0  = $url;
	$start = ($page_num - 1)  * $page_size;
	if ($start <= 0) { $start = 0; }

	if ($_GET['debug'] == 1) {
	    echo "$start is the limit start<BR>";
	}

	$end   = $page_size;
	$pic_list = array();
	$link_list = array();
	$artlink_list = array();

	if ($sl == 1) { 
	    array_push ($headers, printDetailHeadersOneLine ('Search Type',"Similar Words"));
	}
	$picroot  = "pics";
	echo "<div style=\"font-size:12apt;font-family:Lucida Sans;font-weight:bold;text-align:center;\">\n";
	if ($cat != "" && $art != ""){
	    if ($cat == 'raga'){
		$pos = stripos($art, "Raagamalika");
		if ($pos !== false) {
		    array_push ($whereClauses, "( S_RAGA like \"$art%\" )");
		}	       
		else {	    
		    array_push ($whereClauses, "( S_RAGA like \"$art\" or S_RAGA like \"%,$art\" or S_RAGA like \"$art,%\" or S_RAGA like \"%,$art,%\") ");
		}
		array_push ($headers, printDetailHeadersOneLine ('Raga',"$art"));
	    }
	    else if ($cat == 'movie'){
		array_push ($whereClauses, "( S_MOVIE = \"$mov\") ");
		array_push ($headers, printDetailHeadersOneLine ('Movie',"$art"));
	    }
	    else if ($cat == 'musician'){
		array_push ($whereClauses, "( S_MUSICIAN like \"$art\" or S_MUSICIAN like \"%,$art\" or S_MUSICIAN like \"$art,%\" or S_MUSICIAN like \"%,$art,%\") ");
		$multi_mus = explode(',',$art);
		$pic_array = array();
		foreach ($multi_mus as $ms){
		    $ms = ltrim(rtrim($ms));
		    if ($similar == 1) { 
		    $pic_array = addPicture("$picroot","Musicians","$ms","musician");
		    if ($pic_array[0] != '') {
			array_push ($pic_list,$pic_array[0]);
			array_push ($link_list,$pic_array[1]);
			array_push ($artlink_list,$pic_array[2]);
		    }
		    array_push ($headers, printDetailHeadersOneLine ('Musician',"$art"));
		    }
		}
	    }
	    else if ($cat == 'lyricist'){
			array_push ($whereClauses, "( S_WRITERS like \"$art\" or S_WRITERS like \"%,$art\" or S_WRITERS like \"$art,%\" or S_WRITERS like \"%,$art,%\") ");
			
			$multi_lyr = explode(',',$art);
	                foreach ($multi_lyr as $ml){
				$ms = ltrim(rtrim($ml));
                                $ms = stripTrads($ms);
				if (file_exists("$picroot/Lyricists/${ms}.jpg")){
					array_push ($pic_list,"$picroot/Lyricists/${ms}.jpg");
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
		//array_push ($whereClauses, "( S_SINGERS like \"$art\" or S_SINGERS like \"%,$art\" or S_SINGERS like \"$art,%\" or S_SINGERS like \"%,$art,%\") ");
		$multi_singers = explode(',',$art);	
		if ($sl != 1) { 
		    foreach ($multi_singers as $ms){
			$ms = ltrim(rtrim($ms));
			if (file_exists("$picroot/Singers/${ms}.jpg")){
			    array_push ($pic_list,"$picroot/Singers/${ms}.jpg");
			    array_push ($link_list,"$_Master_profile_script?artist=${ms}&category=singers");
			    array_push ($artlink_list,"$ms");
			}
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
	    array_push ($whereClauses, " S_KCLIP = \"$kavail\" ");
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
	    $precise = getPreciseMovieSongName($mov,'movies');
	    if ($precise && $sl != 1){
		array_push ($whereClauses , " S_MOVIE like \"$mov\" ");
	    }
	    else {
		array_push ($whereClauses, addClauseToArray("S_MOVIE",$mov));
	    }
	    array_push ($headers, printDetailHeadersOneLine ('Movie',"$mov"));
	}

	if ($moviestate != "" && $moviestate != "All"){
	    $query      = "SELECT * FROM SONGS,MOVIES WHERE ";
	    if ($moviestate == "Unreleased") {
		array_push ($whereClauses, " (SONGS.M_ID=MOVIES.M_ID and MOVIES.M_COMMENTS = \"*\") ");	
	    }
	    else if ($moviestate == "Dubbed") {
		array_push ($whereClauses, " (SONGS.M_ID=MOVIES.M_ID and MOVIES.M_COMMENTS = \"Dubbed\") ");	
	    }
	    else if ($moviestate == "InProduction") {
		array_push ($whereClauses, " (SONGS.M_ID=MOVIES.M_ID and MOVIES.M_COMMENTS = \"Pre\") ");	
	    }
	    else if ($moviestate == "Released"){
		array_push ($whereClauses, " (SONGS.M_ID=MOVIES.M_ID and MOVIES.M_COMMENTS = '') ");	
	    }
	    array_push ($headers, printDetailHeadersOneLine ('Movie State',"$moviestate"));
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
	    $query      = "SELECT * FROM SONGS,UTUBE WHERE ";
	    array_push ($whereClauses, " SONGS.S_ID=UTUBE.UT_ID and UTUBE.UT_OWN=\"$vown\" and UTUBE.UT_STAT=\"Published\" ");
	    array_push ($headers, printDetailHeadersOneLine ('Videos Owner',"$vown"));
	}
	if ($actors != ''){
	    $query      = "SELECT * FROM SONGS,UTUBE WHERE ";

	    $pos = strpos($actors,",");	
	    if ($pos !== false) {
		$actlist = explode(',',$actors);
		foreach ($actlist as $act){
		    array_push ($whereClauses, " SONGS.S_ID=UTUBE.UT_ID and UTUBE.UT_ACTORS like \"%$act%\"");
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
		array_push ($whereClauses, " SONGS.S_ID=UTUBE.UT_ID and UTUBE.UT_ACTORS like \"%$actors%\"");
		if (file_exists("$picroot/Actors/${actors}.jpg")){
		    array_push ($pic_list,"$picroot/Actors/${actors}.jpg");
		    array_push ($link_list,"$_Master_profile_script?artist=${actors}&category=actors");
		    array_push ($artlink_list,"$actors");
		}
	    }
	    array_push ($headers, printDetailHeadersOneLine ('Actors',"$actors"));

	}
/*
	if ($similar != ''){
		$query = "SELECT S_ID,S_SONG,S_MOVIE,S_YEAR,S_WRITERS,S_MUSICIAN FROM SONGS WHERE "; 
		$los = strlen($similar);
		$similar2 = str_replace('r','l',$similar);
		$los2 = strlen($similar2);
		array_push ($whereClauses," ((SOUNDEX(SONGS.S_SONG)=SOUNDEX(\"$similar\") or SOUNDEX(SUBSTR(SONGS.S_SONG,1,$los)) = SOUNDEX(\"$similar\")))" );
		array_push ($headers, printDetailHeadersOneLine ('Similar Sounding Songs',"$similar"));
	}
*/
	if ($kown != ''){
	    array_push ($whereClauses, "(S_KCLIPOWN like \"$kown\" or S_KCLIPOWN like \"%,$kown\" or S_KCLIPOWN like \"$kown,%\" or S_KCLIPOWN like \"%,$kown,%\")");
	    array_push ($headers, printDetailHeadersOneLine ('Karaokes Owner',"$kown"));
	}
	if ($mus != ''){
//	    array_push ($whereClauses, "( S_MUSICIAN like \"$mus\" or S_MUSICIAN like \"%,$mus\" or S_MUSICIAN like \"$mus,%\" or S_MUSICIAN like \"%,$mus,%\") ");
            array_push ($whereClauses,  addClauseToArray ('S_MUSICIAN',"$mus"));

	   
	    $multi_mus = explode(',',$mus);
           $pic_array = array();
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
//	    array_push ($tables, "UTUBE");
            $query      = "SELECT * FROM SONGS,UTUBE WHERE ";
	    array_push ($whereClauses, " (SONGS.S_ID=UTUBE.UT_ID and UTUBE.UT_STAT=\"Published\") ");
//	    array_push ($headers, printDetailHeadersOneLine ('Video',"Available"));
	}

//	printShortHeaders ('Movie Songs - Missing',"$missing");
	if ($nvideo == 1){
            $query      = "SELECT * FROM SONGS,UTUBE WHERE ";
	    array_push ($whereClauses, " (SONGS.S_ID=UTUBE.UT_ID and UTUBE.UT_STAT != \"Published\") ");
	    array_push ($headers, printDetailHeadersOneLine ('Video',"Not Available"));
	}
	if ($naudio == 1){
	    array_push ($whereClauses, " (SONGS.S_CLIP != \"Y\") ");
	    array_push ($headers, printDetailHeadersOneLine ('Audio',"Not Available"));
	}
	if ($nkaraoke == 1){
	    array_push ($whereClauses, " (SONGS.S_KCLIP != \"Y\") ");
	    array_push ($headers, printDetailHeadersOneLine ('Karaoke',"Not Available"));
	}
	if ($nlyrics == 1){
	    array_push ($whereClauses, " (SONGS.S_LYR != \"Y\") ");
	    array_push ($headers, printDetailHeadersOneLine ('Lyrics',"Not Available"));
	}
	if ($nmlyrics == 1){
	    array_push ($whereClauses, " (SONGS.S_MLYR != \"Y\") ");
	    array_push ($headers, printDetailHeadersOneLine ('Unicode',"Not Available"));
	}

	if ($karaoke == 1){
            array_push ($whereClauses,  " S_KCLIP = 'Y' ");
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

	if ($song != ''){
	    $precise = getPreciseMovieSongName($song,'moviesongs');
	    if ($precise && $sl != 1){
		array_push ($whereClauses , " S_SONG like \"$song\" ");
	    }
	    else {
		array_push ($whereClauses , addClauseToArray ('S_SONG',"$song"));
	    }
	    array_push ($headers, printDetailHeadersOneLine ('Song',"$song"));
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
		array_push ($whereClauses , addClauseToArray ('S_SINGERS',"$sin"));
		array_push ($headers, printDetailHeadersOneLine ('Singer Type',"$singtype"));
            }
            else if ($singtype == 'duet'){
                array_push ($whereClauses , addClauseToArray ('S_SINGERS',"$sin"));
		array_push ($headers, printDetailHeadersOneLine ('Singer Type',"$singtype"));
            }
            else {
		array_push ($whereClauses, addClauseToArray ('S_SINGERS', "$sin"));
            }
	    $multi_singers = explode(',',$sin);
//	    if ($sl != 1){
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
	    $query .=  implode (' AND ',$whereClauses) . " order by S_YEAR limit $start,$end";
	}
	else if ($whereClauses != '') {
	    $query .=  implode (' AND ',$whereClauses)  . " ORDER by S_YEAR";
	}

	if ($missing != ''){
//	    array_push ($headers, printDetailHeadersOneLine ('Movie Songs - Missing',"$missing"));
	    if ($missing == 'ml'){
		$query = "SELECT * from SONGS WHERE S_LYR  != 'Y'";
	    }
	    else if ($missing == 'mlv'){
		$query = "SELECT * from SONGS,UTUBE WHERE S_LYR  != 'Y' and UTUBE.UT_STAT='Published' and UTUBE.UT_ID=SONGS.S_ID ORDER BY S_YEAR";
	    }
	    else if ($missing == 'ul'){
		$query = "SELECT * from SONGS WHERE S_MLYR != 'Y'";
	    }
	    else if ($missing == 'ulv'){
		$query = "SELECT * from SONGS,UTUBE WHERE S_MLYR  != 'Y' and UTUBE.UT_STAT='Published' and UTUBE.UT_ID=SONGS.S_ID ORDER BY S_YEAR";
	    }
	    else if ($missing == 'audio'){
		$query = "SELECT * from SONGS WHERE S_CLIP != 'Y'";
	    }
	    else if ($missing == 'video'){
		$query = "SELECT *  FROM SONGS LEFT JOIN UTUBE  ON UTUBE.UT_ID = SONGS.S_ID WHERE (UTUBE.UT_ID IS NULL or UTUBE.UT_URL = \"\" or UTUBE.UT_STAT != 'Published')";
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

    echo "<table width=100% class=ptables><tr><td>\n";
  printPicList($pic_list,$link_list,$artlink_list);
    echo "</td></tr></table>";

	if ($lyr != ''){	    array_push ($tags,$lyr);	}
	if ($mus != '') { array_push ($tags,$mus); }
	if ($movie != '') { array_push ($tags,$movie); }
	if ($year != '' ) { array_push ($tags,$year); }
	if ($bgm != '') { array_push ($tags,$bgm); }
	if ($actors != '') { array_push ($tags,$actors); }
	if ($sin != '') { array_push ($tags,$sin); }

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
    if ($num_results == 0){
       if ($_GET['lang'] == 'E'){
	   printContents("Writeups/MissingSearch.html");
       }      
       else {
	   printContents("Writeups/MissingSearch_Malayalam.html");
       }
    }
    else {
	$i=0;

	echo "<table width=100% class=ptables>\n";
	$autoplay = 'true';
	while ($i < $num_results){

	    $sid = mysql_result($result, $i, "S_ID");
	    $mid = mysql_result($result, $i, "M_ID");
	    $song = mysql_result($result, $i, "S_SONG");
	    $movie = mysql_result($result, $i, "S_MOVIE");
	    $year = mysql_result($result, $i, "S_YEAR");



	    $vid = runQuery("SELECT UT_URL FROM UTUBE WHERE UT_ID=$sid",'UT_URL');
	    $url = $vid;
	    $url = str_replace("http://www.youtube.com/watch?v=",'',$url);
	    $url = str_replace("http://www.msimusic.org/Videos?v=",'',$url);
	    $url = str_replace("http://msimusic.org/Videos?v=",'',$url);
	    $url = str_replace("&feature=related",'',$url);
	    global $_MasterRootDir;
	    global $_RootofMSI,$_RootDir;
	    $_GDMasterRootofMSI = "http://msidb.info";
	    if (file_exists("$_MasterRootDir/Videos/${url}.flv")) { $alternateVideo = 1; $video_url = "$_GDMasterRootofMSI/Videos/${url}.flv"; }
	    else if (file_exists("$_MasterRootDir/Videos/${sid}.flv")) { $alternateVideo = 1; $video_url = "$_GDMasterRootofMSI/Videos/${sid}.flv"; }
	    else if (file_exists("$_MasterRootDir/Videos/${sid}.FLV")) { $alternateVideo = 1; $video_url = "$_GDMasterRootofMSI/Videos/${sid}.FLV"; }
	    if (strpos($vid,"youtube") !== false){
		$vid_exists = 1;
	    }
	    else {
		$vid_exists = link_exists($vid);
	    }

	    if (strpos($vid,"msimusic") !== false || !$vid_exists){
		$video_url = "$_GDMasterRootofMSI/Videos/${sid}.flv"; 
		if (!link_exists($video_url)){
		    $video_url = "$_GDMasterRootofMSI/Videos/${url}.flv"; 
		}
		$alternateVideo=1;
	    }
	    if ($alternateVideo){
		$vid = $video_url;
	    }
	    if ($vid_exists){
	        $vid_exists = 0;
		$alternateVideo = false;
		if ($autoplay == 'true'){
		    
		    echo "<tr><td colspan=7 bgcolor=#ffffff align=center><a href=\"$vid\" data-width=\"400\" data-height=\"300\" class=\"html5lightbox\" autoplay=\"$autoplay\" data-group=\"group1\" title=\"$song ($movie $year)\"><img src=\"icons/playbutton.png\" border=0 height=100></a></td></tr>\n";		    
			$first_video_skip = 1;
//		    echo "<table class=ptables><tr class=tableheader>\n";
		    $song_tag = 'Song Details';
		    $play_tag = 'Play Song';
		    if ($_GET['lang']!='E'){
			$song_tag   = get_uc($song_tag,'');
			$play_tag   = get_uc($play_tag,'');
		    }
//		    echo ( "<th class=pcellheadscenter>$song_tag</th>\n");
//		    echo ( "<th class=pcellheadscenter>$play_tag</th>\n");
//		    echo "</tr>\n";
		    $autoplay = 'false';
//		    echo "</table>";
		}

		echo "<table class=ptables><tr bgcolor=#000000><td width=90% style=\"border:1px dotted;\" bgcolor=#ffffff  >\n";
		printDetailDivs ($song,"$songScript?$sid",$i);
		echo "(";
		printDetailDivs ($movie,"$movieScript?$mid",$i);
		printDetailDivs ($year,'',$i);
		echo ") - ";
		$musician = mysql_result($result, $i, "S_MUSICIAN");
		$lyricist = mysql_result($result, $i, "S_WRITERS");
		$singers = mysql_result($result, $i, "S_SINGERS");
		printDetailDivs ($musician,'',$i);
		echo ",";
		printDetailDivs ($lyricist,'',$i);
		echo ",";
		printDetailDivs ($singers,'',$i);
		if ($_GET['lang'] != 'E'){ 
		    $song = get_uc($song,'');
		    $movie = get_uc($movie,'');
		    $musician = get_uc($musician,'');
		    $lyricist = get_uc($lyricist,'');
		    $singers = get_uc($singers,'');
		}
		    if ($first_video_skip){
			$first_video_skip = 0;
		    echo "</td><td style=\"border:1px dotted;\" bgcolor=#ffffff ><a href=\"$vid\" data-width=\"480\" data-height=\"320\" class=\"html5lightbox\" autoplay=\"$autoplay\"  title=\"$song ($movie $year)\"><img src=\"icons/playbutton.png\" height=25 border=0></a></td>\n";
		    }
		else {
		    echo "</td><td style=\"border:1px dotted;\" bgcolor=#ffffff ><a href=\"$vid\" data-width=\"480\" data-height=\"320\" class=\"html5lightbox\" autoplay=\"$autoplay\" data-group=\"group1\" title=\"$song ($movie $year)\"><img src=\"icons/playbutton.png\" height=25 border=0></a></td>\n";
		}
		if ($autoplay == 'true'){ $autoplay = 'false'; }
		echo "</tr></table>";
	    }

	    $i++;
	}
	if ($limit > $page_size) {
	    writeNavigation ($page_num,"1",$num_pages,$start,$limit,$page_size,$url0,$_GET['lang']);
	}
	echo "</table>\n";



    }
    printFancyFooters();
    mysql_close($cLink);

}


function link_exists($strURL) {
    $resURL = curl_init();
    curl_setopt($resURL, CURLOPT_URL, $strURL);
    curl_setopt($resURL, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($resURL, CURLOPT_HEADERFUNCTION, 'curlHeaderCallback');
    curl_setopt($resURL, CURLOPT_FAILONERROR, 1);

    curl_exec ($resURL);

    $intReturnCode = curl_getinfo($resURL, CURLINFO_HTTP_CODE);
    curl_close ($resURL);

    if ($intReturnCode != 200 && $intReturnCode != 302 && $intReturnCode != 304) {
       return false;
    }Else{
        return true ;
    }
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
