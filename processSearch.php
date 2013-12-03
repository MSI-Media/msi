<?php session_start();
{
    error_reporting (E_ERROR);

    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("includes/utils.php");
    require_once("includes/searchUtils.php");
    require_once("_includes/_moviePageUtils.php");
    $_GET['encode']='utf';

    $profileScript = $_Master_profile_script;
    $profilemaster = $_Master_profile;
    $movieScript = $_Master_movielist_script  ; 
    $songScript = $_Master_songlist_script   ;
    $albumScript = $_Master_albumlist_script   ;
    $asongScript = $_Master_albumsonglist_script   ;


    $cLink = msi_dbconnect();
    printXHeader('');
    mysql_query("SET NAMES utf8");


    $search_type = $_POST['search_type'];
    $profile = $_POST['profiles'];
    $db = $_POST['db'];
    $articles = $_POST['articles'];
    $audiofiles = $_POST['audiofiles'];
    $genre = $_POST['genre'];
    $moviename = $_POST['moviename'];
    $songname = $_POST['songname'];
    if ($db == "albums"){
	$moviename = $_POST['albumname'];
    }

    $year = $_POST['year'];


    $moviestate = $_POST['moviestatus'];
    $singstate = $_POST['singstatus'];
    $raga = $_POST['raga'];
    $karaoke = $_POST['karaoke'];
    $lyrics = $_POST['lyrics'];
    $mlyrics = $_POST['mlyrics'];
    $audio = $_POST['audio'];

    $nkaraoke = $_POST['n_karaoke'];
    $nlyrics = $_POST['n_lyrics'];
    $nmlyrics = $_POST['n_mlyrics'];
    $naudio = $_POST['n_audio'];
    $nvideo = $_POST['n_video'];
    $reviews = $_POST['reviews'];
    $songbooks = $_POST['songbooks'];
    $promos = $_POST['promos'];
    $posters = $_POST['posters'];
    $martifacts = $_POST['martifacts'];
    $video = $_POST['video'];

    $singers = $_POST['singers'];
    $lyricist = $_POST['lyricist'];
    $musician = $_POST['musician'];
    $director = $_POST['director'];
    $producer = $_POST['producer'];
    $actor = $_POST['actor'];
    $bgm = $_POST['bgm'];
    $story = $_POST['story'];
    $screenplay = $_POST['screenplay'];

    if ($db == "videosongs"){
	$videosongs = true;
	$video=1;
	$db = "moviesongs";
    }

    $picroot  = "pics";

    if ($db == ''){

    $search_type = $_GET['search_type'];
    $profile = $_GET['profiles'];
    $db = $_GET['db'];
    $articles = $_GET['articles'];
    $audiofiles = $_GET['audiofiles'];
    $genre = $_GET['genre'];
    $moviename = $_GET['moviename'];
    $songname = $_GET['songname'];
    if ($db == "albums" || $db == "albumsongs"){
	$moviename = $_GET['albumname'];
    }
    $singers = $_GET['singers'];
    $moviestate = $_GET['moviestatus'];
    $singstate = $_GET['singstatus'];
    $lyricist = $_GET['lyricist'];
    $musician = $_GET['musician'];
    $year = $_GET['year'];
    $director = $_GET['director'];
    $producer = $_GET['producer'];
    $actor = $_GET['actor'];
    $bgm = $_GET['bgm'];
    $raga = $_GET['raga'];
    $karaoke = $_GET['karaoke'];
    $lyrics = $_GET['lyrics'];
    $mlyrics = $_GET['mlyrics'];
    $audio = $_GET['audio'];

    $nkaraoke = $_GET['n_karaoke'];
    $nlyrics = $_GET['n_lyrics'];
    $nmlyrics = $_GET['n_mlyrics'];
    $naudio = $_GET['n_audio'];
    $nvideo = $_GET['n_video'];

    $story = $_GET['story'];
    $screenplay = $_GET['screenplay'];
    $promos = $_GET['promos'];
    $reviews = $_GET['reviews'];
    $songbooks = $_GET['songbooks'];
    $posters = $_GET['posters'];
    $martifacts = $_GET['martifacts'];
    $video = $_GET['video'];


    }


    $bgm = ltrim(rtrim($bgm));
    $raga = ltrim(rtrim($raga));
    $singers =    ltrim(rtrim($singers));
    $musician =    ltrim(rtrim($musician));
    $lyricist =    ltrim(rtrim($lyricist));
    $director =    ltrim(rtrim($director));
    $producer =    ltrim(rtrim($producer));
    $actor =    ltrim(rtrim($actor));
    $story =    ltrim(rtrim($story));
    $screenplay =    ltrim(rtrim($screenplay));

    if (( $moviename != '' && strlen($moviename) < 2) || ( $songname != '' && strlen($songname) < 4)){
	$errmsg = 'Movie and Song Names should be more than 3 characters';
	if ($_SESSION['lang'] != 'E') { $errmsg = get_uc($errmsg); }
	echo "<script>alert(\"$errmsg\");</script>";
	echo "<script>history.back();</script>";
    }

    $bgm      = processString($bgm);
    $raga      = processString($raga);
    $singers   = processString($singers);
    $musician   = processString($musician);
    $lyricist   = processString($lyricist);
    $director   = processString($director);
    $producer   = processString($producer);
    $story     = processString($story);
    $screenplay    = processString($screenplay);
    $actor    = processString($actor);

    if ($search_type != 1) {

    if ($singers) {
    	$pos = strpos($singers,",");	
	if ($pos !== false) {
	    // If there are commas for singers let  us use similar search
	    $search_type = 1;
	    $_POST['search_type'] = 1;
	    $_GET['search_type'] = 1;
	    $actlist = array();
	    $givlist = explode (',',$singers);
	    foreach ($givlist as $giv){
		$giv = ltrim(rtrim($giv));
		array_push ($actlist , correctPopularNames($picroot,"Singers",$giv));
	    }
	    $singers = implode(',',$actlist);
	    if ($givlist[0] != $actlist[0]){
		$_POST['search_type'] = 0;
		$search_type = 0;
	    }
	}
	else {
	    $singers = correctPopularNames($picroot,"Singers",$singers);
	}
    }
    if ($lyricist){
	$lyricist = correctPopularNames($picroot,"Lyricists",$lyricist);
    }

    if ($musician){
	$musician = correctPopularNames($picroot,"Musicians",$musician);
    }
    if ($director){
	$director = correctPopularNames($picroot,"Directors",$director);
    }
    if ($producer){
	$producer = correctPopularNames($picroot,"Producers",$producer);
    }
    if ($bgm){
	$bgm = correctPopularNames($picroot,"Musicians",$bgm);
    }
    if ($story){
	$story = correctPopularNames($picroot,"Screenplay",$story);
    }
    if ($screenplay){
	$screenplay = correctPopularNames($picroot,"Screenplay",$screenplay);
    }


    }

    if ($actor){
    	$pos = strpos($actor,",");	
	if ($pos !== false) {
	    $actlist = array();
	    $givlist = explode (',',$actor);
	    foreach ($givlist as $giv){
		$giv = ltrim(rtrim($giv));
		array_push ($actlist , correctPopularNames($picroot,"Actors",$giv));
	    }
	    $actor = implode(',',$actlist);
	    if ($givlist[0] != $actlist[0]){
		$_POST['search_type'] = 0;
		$search_type = 0;
	    }
	}
	else {

	    $actor = correctPopularNames($picroot,"Actors",$actor);
	}
    }

    $addedMD=0;	
    $query = '';
    if ($db == "moviesongs" || $db == "albumsongs" || $db == "allsongs" ){
	if ($db == "moviesongs"){
	    if ($videosongs){
		$script = $_Master_vidscript;
	    }
	    else {
		$script = "$songScript";
	    }
	    $query = "SELECT COUNT(SONGS.S_ID) ccn FROM SONGS ";
	}
	else if ($db == "albumsongs") {
	    $script = "$asongScript";
	    $query = "SELECT COUNT(ASONGS.S_ID) ccn FROM ASONGS ";
	}

	$tables = array();
	$query_str = array();
	$url_str = array();
	if ($moviename != ""){

            $db2 = "movies";
	    if ($db == "albumsongs") { $db2 = "albums"; }
   	    if (!$search_type) {	
		$precise = getPreciseMovieSongName($moviename,$db2);
	    }
	    if ($precise != ''){
		$moviename = $precise;
	    }
	    else if (!$search_type) {
		$search_type = 1;	
		$_POST['search_type'] = 1;
		$_GET['search_type'] = 1;
	    }

	    $moviename = ltrim(rtrim($moviename));
	    if ($precise != ''){
		array_push ($query_str , "S_MOVIE like \"$moviename\"");
	    }
	    else {
		array_push ($query_str , addClauseToArray("S_MOVIE","$moviename"));
	    }
	    array_push ($url_str, "movie=$moviename");
	}
	if ($musician != ""){
	    $musician = ltrim(rtrim($musician));
	    array_push ($query_str , addClauseToArray("S_MUSICIAN","$musician"));
	    array_push ($url_str, "musician=$musician");
	}

	if ($lyricist != ""){
	    $lyricist = ltrim(rtrim($lyricist));
	    array_push ($query_str , addClauseToArray("S_WRITERS","$lyricist"));
	    array_push ($url_str, "lyricist=$lyricist");
	}

	if ($singers != ""){
	    $singers = ltrim(rtrim($singers));
	    array_push ($query_str , addClauseToArray("S_SINGERS","$singers"));
	    array_push ($url_str, "singers=$singers");
	}
	if ($songname != ""){
	    $songname = ltrim(rtrim($songname));
   	    if (!$search_type) {	
		$precise = getPreciseMovieSongName($songname,$db);
	    }
	    if ($precise != ''){
		$songname = $precise;
	    }
	    else if (!$search_type) {
		$search_type = 1;	
		$_POST['search_type'] = 1;
		$_GET['search_type'] = 1;
	    }
	    if ($precise != ''){
		array_push ($query_str , "S_SONG like \"$songname\"");
	    }
	    else {
		array_push ($query_str , addClauseToArray("S_SONG","$songname"));
	    }
	    array_push ($url_str, "song=$songname");
	}
	if ($raga != ""){	
	    $raga = ltrim(rtrim($raga));
	    if (stripos($raga, "Raagamalika") !== false){	
		array_push ($query_str, "( S_RAGA like \"$raga%\" )");
	    }	       
	    else {
		array_push ($query_str, addClauseToArray("S_RAGA","$raga"));
	    }
	    array_push ($url_str, "raga=$raga");
	}
	if ($genre != ""){	
	    $genre = ltrim(rtrim($genre));
	    array_push ($query_str, addClauseToArray("S_GENRE","$genre"));
	    array_push ($url_str, "genre=$genre");
	}
	if ($year != ""){	
	    array_push ($query_str, " S_YEAR like \"$year%\" ");
	    array_push ($url_str, "year=$year");
	}

	if ($audio != ""){
	    array_push ($query_str , addClauseToArray("S_CLIP","Y"));
	    array_push ($url_str, "audio=1");
	}
	if ($karaoke != ""){
	    array_push ($query_str , addClauseToArray("S_KCLIP","Y"));
	    array_push ($url_str, "karaoke=1");
	}
	if ($mlyrics != ""){
	    array_push ($query_str , addClauseToArray("S_MLYR","Y"));
	    array_push ($url_str, "unicode=1");
	}
	if ($lyrics != ""){
	    array_push ($query_str , addClauseToArray("S_LYR","Y"));
	    array_push ($url_str, "lyrics=1");
	}
	if ($singstate != "" && $singstate != "All"){
	    if ($singstate == 'Duets'){
		array_push ($query_str, " (S_SINGERS like \"%,%\") ");	
	    }
	    else if ($singstate == "Solos"){
		array_push ($query_str, " (S_SINGERS not like \"%,%\") ");	
	    }
	    array_push ($url_str, "songstate=$singstate");
	}
	if ($moviestate != "" && $moviestate != "All"){
	    if ( !in_array( "MOVIES", $tables)){
		array_push ($tables, "MOVIES");
	    }	  

	    if ($moviestate == "Unreleased") {
		array_push ($query_str, " (SONGS.M_ID=MOVIES.M_ID and MOVIES.M_COMMENTS = \"*\") ");	
	    }
	    else if ($moviestate == "Dubbed") {
		array_push ($query_str, " (SONGS.M_ID=MOVIES.M_ID and MOVIES.M_COMMENTS = \"Dubbed\") ");	
	    }
	    else if ($moviestate == "InProduction") {
		array_push ($query_str, " (SONGS.M_ID=MOVIES.M_ID and MOVIES.M_COMMENTS = \"Pre\") ");	
	    }
	    else if ($moviestate == "Released"){
		array_push ($query_str, " (SONGS.M_ID=MOVIES.M_ID and MOVIES.M_COMMENTS = '') ");	
	    }
	    array_push ($url_str, "state=$moviestate");
	}
	if ($naudio != ""){
	    array_push ($query_str , "S_CLIP != 'Y'");
	    array_push ($url_str, "naudio=1");
	}
	if ($nkaraoke != ""){
	    array_push ($query_str , "S_KCLIP != 'Y'");
	    array_push ($url_str, "nkaraoke=1");
	}
	if ($nmlyrics != ""){
	    array_push ($query_str , "S_MLYR != 'Y'");
	    array_push ($url_str, "nunicode=1");
	}
	if ($nlyrics != ""){
	    array_push ($query_str , "S_LYR != 'Y'");
	    array_push ($url_str, "nlyrics=1");
	}
	if ($nvideo != ""){
	    if ($db == "moviesongs") {	
		if ( !in_array( "UTUBE", $tables)){
		    array_push ($tables, "UTUBE");
		}	  
		array_push ($query_str, " (SONGS.S_ID=UTUBE.UT_ID and UTUBE.UT_STAT != \"Published\") ");	
	    }
	    else if ($db == "albumsongs") {	
		if ( !in_array( "ALBUM_UTUBE", $tables)){
		    array_push ($tables, "ALBUM_UTUBE");
		}	  
		array_push ($query_str, " (ASONGS.S_ID=ALBUM_UTUBE.UT_ID and ALBUM_UTUBE.UT_STAT != \"Published\") ");
	    }

	    array_push ($url_str, "nvideo=1");
	}

	if ($video != ""){
	    if ( !in_array( "UTUBE", $tables)){	
		array_push ($tables, "UTUBE");
	    }
	    array_push ($query_str, " (S_ID=UTUBE.UT_ID and UTUBE.UT_STAT=\"Published\") ");
	    array_push ($url_str, "videos=1");
	}
	if ($actor != ""){
	    if ( !in_array( "UTUBE", $tables)){
		array_push ($tables, "UTUBE");
	    }
	    array_push ($query_str, " S_ID=UTUBE.UT_ID ");
	    array_push ($query_str , addClauseToArray("UTUBE.UT_ACTORS","$actor"));
	    array_push ($url_str, "actor=$actor");
	}

    }
    else if ($db == "movies" || $db == "albums"){
	if ($db == "movies"){
	    $script = $_Master_movielist_script;
	    $query = "SELECT COUNT(DISTINCT MOVIES.M_ID) ccn FROM MOVIES ";
	}
	else {
	    $script = $_Master_albumlist_script;
	    $query = "SELECT COUNT(DISTINCT ALBUMS.M_ID) ccn FROM ALBUMS ";
	}

	$tables = array();
	$query_str = array();
	$url_str = array();
	if ($musician != ""){
	    $musician = ltrim(rtrim($musician));
	    if ($db == "movies"){
		array_push ($query_str , addClauseToArray("MOVIES.M_MUSICIAN","$musician"));
	    }
	    else {
		array_push ($query_str , addClauseToArray("ALBUMS.M_MUSICIAN","$musician"));
	    }
	    array_push ($url_str, "musician=$musician");
	}
	if ($moviename != ""){
   	    if (!$search_type) {	
		$precise = getPreciseMovieSongName($moviename,$db);
	    }
	    if ($precise != ''){
		$moviename = $precise;
	    }
	    else if (!$search_type) {
		$search_type = 1;	
		$_POST['search_type'] = 1;
		$_GET['search_type'] = 1;
	    }

	    $moviename = ltrim(rtrim($moviename));

	    if ($db == "movies"){
		array_push ($query_str , addClauseToArray("MOVIES.M_MOVIE","$moviename"));
	    }
	    else {
		array_push ($query_str , addClauseToArray("ALBUMS.M_MOVIE","$moviename"));
	    }
	    array_push ($url_str, "movie=$moviename");
	}
	if ($genre != "" && $db == "albums"){	
	    array_push ($query_str, addClauseToArray("M_COMMENTS","$genre"));
	    array_push ($url_str, "genre=$genre");
	}
	if ($lyricist != ""){
	    $lyricist = ltrim(rtrim($lyricist));
	    if ($db == "movies"){
		array_push ($query_str , addClauseToArray("MOVIES.M_WRITERS","$lyricist"));
	    }
	    else {
		array_push ($query_str , addClauseToArray("ALBUMS.M_WRITERS","$lyricist"));
	    }
	    array_push ($url_str, "lyricist=$lyricist");
	}

	if ($director != ""){
	    $director = ltrim(rtrim($director));
	    if ($db == "movies"){
		array_push ($query_str , addClauseToArray("MOVIES.M_DIRECTOR","$director"));
	    }
	    else {
		array_push ($query_str , addClauseToArray("ALBUMS.M_DIRECTOR","$director"));
	    }
	    array_push ($url_str, "director=$director");
	}

	if ($year != ""){	
	    array_push ($query_str, " M_YEAR like \"$year%\" ");
	    array_push ($url_str, "year=$year");
	}

	if ($db == "movies"){

	    if ($producer != ""){
	    if ( !in_array( "MDETAILS", $tables)){
		array_push ($tables, "MDETAILS");
	    }

		array_push ($query_str , addClauseToArray("M_PRODUCER","$producer"));
		array_push ($query_str, " MDETAILS.M_ID=MOVIES.M_ID ");
		array_push ($url_str, "producer=$producer");
	    }

	    if ($bgm != ""){
	    if ( !in_array( "MDETAILS", $tables)){
		array_push ($tables, "MDETAILS");
	    }

		array_push ($query_str , addClauseToArray("M_BGM","$bgm"));
		array_push ($query_str, " MDETAILS.M_ID=MOVIES.M_ID ");
		array_push ($url_str, "bgm=$bgm");
	    }

	    if ($moviestate != "" && $moviestate != "All"){
		if ($moviestate == "Unreleased") {
		    array_push ($query_str, " ( MOVIES.M_COMMENTS = \"*\") ");	
		}
		else if ($moviestate == "Dubbed") {
		    array_push ($query_str, " ( MOVIES.M_COMMENTS = \"Dubbed\") ");	
		}
		else if ($moviestate == "InProduction") {
		    array_push ($query_str, " ( MOVIES.M_COMMENTS = \"Pre\") ");	
		}
		else if ($moviestate == "Released"){
		    array_push ($query_str, " ( MOVIES.M_COMMENTS = '') ");	
		}
		array_push ($url_str, "state=$moviestate");
	    }

	    if ($actor != ""){
	    if ( !in_array( "MDETAILS", $tables)){
		array_push ($tables, "MDETAILS");
	    }

		array_push ($query_str, addClauseToArray("M_CAST","$actor"));
		array_push ($query_str, " MDETAILS.M_ID=MOVIES.M_ID ");
		array_push ($url_str, "actor=$actor");
	    }

	    if ($story != ""){
	    if ( !in_array( "MDETAILS", $tables)){
		array_push ($tables, "MDETAILS");
	    }

		array_push ($query_str, addClauseToArray("M_STORY","$story"));
		array_push ($query_str, " MDETAILS.M_ID=MOVIES.M_ID ");
		array_push ($url_str, "story=$story");
	    }

	    if ($screenplay != ""){
	    if ( !in_array( "MDETAILS", $tables)){
		array_push ($tables, "MDETAILS");
	    }

		array_push ($query_str, addClauseToArray("M_SCREENPLAY","$screenplay"));
		array_push ($query_str, " MDETAILS.M_ID=MOVIES.M_ID ");
		array_push ($url_str, "screenplay=$screenplay");
	    }


	}

	if ($songbooks == 1){
	    if ( !in_array( "PPUSTHAKAM", $tables)){
		array_push ($tables, "PPUSTHAKAM");
	    }
	    array_push($query_str, " PPUSTHAKAM.P_ID=MOVIES.M_ID ");
	    array_push ($url_str, "songbooks=Available");
	}

	if ($reviews == 1){
	    if ( !in_array( "MD_LINKS", $tables)){
		array_push ($tables, "MD_LINKS");
	    }
	    array_push($query_str, " MD_LINKS.M_ID=MOVIES.M_ID ");
	    array_push ($url_str, "reviews=Available");
	}

	if ($promos == 1){
	    if ( !in_array( "PROMOS", $tables)){
		array_push ($tables, "PROMOS");
	    }
	    array_push($query_str, " PROMOS.P_ID=MOVIES.M_ID AND PROMOS.P_STAT=\"Published\" ");
	    array_push ($url_str, "promos=Available");
	}

	if ($posters == 1){
	    if ( !in_array( "PICTURES", $tables)){
		array_push ($tables, "PICTURES");
	    }
	    array_push($query_str, " PICTURES.M_ID=MOVIES.M_ID  AND PICTURES.P_STATUS=\"Y\" ");
	    array_push ($url_str, "posters=Available");
	}


/*
	if ($martifacts == "posters"){
	    array_push ($tables, "PICTURES");
	    array_push($query_str, " PICTURES.M_ID=MOVIES.M_ID ");
	    array_push ($url_str, "posters=Available");
	}
*/

    }
    if ($tables[0] != "") { $query .= " ,"; }
    $query .= implode (",", $tables);
    $query .= " WHERE ";

    
    
    $query .= implode (" AND " , $query_str);
    $limit = runQuery($query,'ccn');
    if ($limit == 0 &&  PopularSongsAvailable($songname)){
      $limit = 1;
    }
    $aquery = $query;
    if ($db == "movies"){
	$aquery = str_replace("MOVIES","ALBUMS",$aquery);
    }
    else if ($db == "albums"){
	$aquery = str_replace("ALBUMS","MOVIES",$aquery);
    }
    else if ($db == "moviesongs"){
	$aquery = str_replace("SONGS","ASONGS",$aquery);
    }
    else if ($db == "albumsongs"){
	$aquery = str_replace("ASONGS","SONGS",$aquery);
    }
    $alimit = runQuery($aquery,'ccn');
//  echo $query, $limit;
//  print_r($url_str);

    if ($limit == 0 && $_GET['db'] == ''){
	$gstr = array();
	foreach ( $_POST as $foo=>$bar ) {
	    if ( !in_array( $foo, $gstr ) && !empty($bar)) {
		if ($foo != 'db' && $foo != "search_type" && $foo != "moviestatus" && $foo != "singstatus"){
		    if (strlen($bar) != strlen(utf8_decode($bar)))  {
			$bar = get_ucname($bar,'');
		    }			    
		}
		array_push ($gstr , "$foo=$bar");
	    }
	}
	$tagstr = implode ('&', $gstr);
	echo "<script>location.replace(\"$_Master_search_process?${tagstr}\");</script>";
    }
    else if ($limit == 0 && (($moviename != '' && $db == 'movies') || ($songname != '' && $db == 'moviesongs') || ($albumname != '' && $db == 'albums') || ($songname != '' && $db == 'albumsongs'))){
    	if ($moviename != "") { $tagstr = $moviename; } 
	else if ($songname != "") { $tagstr = $songname; } 
	else if ($albumname != "") { $tagstr = $moviename; } 
	else if ($albumsongs != "") { $tagstr = $songname; }

	$tagextras = array();
	if ($musician) { array_push ($tagextras, "m=$musician"); }
	if ($lyricist) { array_push ($tagextras, "l=$lyricist"); }
	if ($singers) { array_push ($tagextras, "s=$singers"); }
	if ($raga) { array_push ($tagextras, "r=$raga"); }
	if ($genre) { array_push ($tagextras, "g=$genre"); }
	if ($director) { array_push ($tagextras, "d=$director"); }
	if ($producer) { array_push ($tagextras, "p=$producer"); }
	if ($story) { array_push ($tagextras, "st=$story"); }
	if ($screenplay) { array_push ($tagextras, "sc=$screenplay"); }
	$tagstr .= '&' . implode ('&',$tagextras);
	writeToCacheFile("cache/logs/search.txt","$_Master_similarsearch?tag=Search&db=$db&q=${tagstr}");
	echo "<script>location.replace(\"$_Master_similarsearch?tag=Search&db=$db&q=${tagstr}\");</script>";
    }
    else {
	if ($url_str[0] == ''){
//	    echo "<script>Please select some criteria to search</script>";
//	    echo $_GET['db'] , $_GET['moviename'], "<BR>";
	    echo "<script>history.back();</script>";
	}
	else {
	    $rem = array();
	    if ($articles != ""){
		array_push ($rem, "art=$articles");
	    }
	    if ($audiofiles != ""){
		array_push ($rem, "af=$audiofiles");
	    }
	    if ($search_type != ""){
		array_push ($rem, "sl=$search_type");
	    }
	    if ($profile != ""){
		array_push ($rem, "profile=$profile");
	    }
	    if ($rem) { 
		$qs = implode ("&",$url_str) . '&' . implode ("&", $rem);
	    }
	    else {
		$qs = implode ("&",$url_str) ;
	    }

	    writeToCacheFile("cache/logs/search.txt","${script}?tag=Search&${qs}&limit=$limit");
	    if ($alimit > 0){
		echo "<script>location.replace(\"${script}?tag=Search&${qs}&limit=$limit&alimit=$alimit\");</script>";
	    }
	    else{
		echo "<script>location.replace(\"${script}?tag=Search&${qs}&limit=$limit\");</script>";
	    }

	}
    }
    printFancyFooters();	
    mysql_close($cLink);

}

//    else if ($search_type == 1 && (($_GET['moviename'] != '' && $_GET['db'] == 'movies') || ($_GET['songname'] != '' && $_GET['db'] == 'moviesongs') || ($_GET['albumname'] != '' && $_GET['db'] == 'albums') || ($_GET['songname'] != '' && $_GET['db'] == 'albumsongs'))){



?>
