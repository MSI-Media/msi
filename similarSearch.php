<?php session_start();
{
    error_reporting (E_ERROR);
    require_once("includes/utils.php");
    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("includes/searchUtils.php");
    require_once("_includes/_moviePageUtils.php");

    $_GET['lang'] = $_SESSION['lang'];
    $_GET['encode']='utf';

    $cLink = msi_dbconnect();
    printXHeader('');

    $movieScript = $_Master_movie_script;
    $songScript  = $_Master_song_script;
    $albumScript = $_Master_album_script;
    $albumsongScript = $_Master_albumsong_script;
    $searchScript = $_Master_search;

    $_GET['tag'] = 'Search';
    $_GET['similar'] = $_GET['q'];

    $musician = $_GET['m'];
    $lyricist = $_GET['l'];
    $singers = $_GET['s'];
    $raga = $_GET['r'];
    $genre = $_GET['g'];
    $director = $_GET['d'];
    $producer  = $_GET['p'];
    $story = $_GET['st'];
    $screenplay = $_GET['sc'];

    $optag = $_GET['tag'];
    $db = $_GET['db'];
    $lang = $_GET['lang'];

    $query1 = '';
    $query2 = '';
    $query3 = '';
    $query4 = '';
    $query5 = '';
    $query6 = '';


    if ($optag == "Search"){
	$whereClauses1 = array ();
        $whereClauses2 = array ();
	$whereClauses3 = array ();
        $whereClauses4 = array ();
	$whereClauses5 = array ();
	$whereClauses6 = array ();
	$limit = $_GET['limit'];
	$similar = $_GET['similar'];
	$similar_words_typed = $similar;

	$qs    = $_SERVER['QUERY_STRING'];
	$qrs   = explode  ('&page_num',$qs);
	$url   = $_SERVER['PHP_SELF'] . "?" . $qrs[0] ;
	$start=0;
	$end=10;

	if ($_GET['debug'] == 1) {
	    echo "$start is the limit start<BR>";
	}

	$end   = $page_size;
	$pic_list = array();
	$link_list = array();
	$artlink_list = array();

	$picroot  = "pics";

	if ($_GET['lang'] != 'E'){
	    if ($musician) { array_push ($tagextras, "m=$musician"); $similar_words_typed .= ", " . get_uc('Musician','') .'=' .  get_uc($musician,''); }
	    if ($lyricist) { array_push ($tagextras, "l=$lyricist"); $similar_words_typed .= ", " . get_uc('Lyricist','') .'=' .  get_uc($lyricist,''); }
	    if ($singers) { array_push ($tagextras, "s=$singers"); $similar_words_typed .= ", " . get_uc('Singers','') .'=' .  get_uc($singers,''); }
	    if ($raga) { array_push ($tagextras, "r=$raga"); $similar_words_typed .= ", " . get_uc('Raga','') .'=' . " " . get_uc($raga,''); }
	    if ($genre) { array_push ($tagextras, "g=$genre"); $similar_words_typed .= ", " . get_uc('Genre','') .'=' .  get_uc($genre,''); }
	    if ($director) { array_push ($tagextras, "d=$director"); $similar_words_typed .= ", " . get_uc('Director','') .'=' .  get_uc($director,''); }
	    if ($producer) { array_push ($tagextras, "p=$producer"); $similar_words_typed .= ", " . get_uc('Producer','') .'=' .  get_uc($producer,''); }
	    if ($story) { array_push ($tagextras, "s=$story"); $similar_words_typed .= ", " . get_uc('Story','') .'=' . " " . get_uc($story,''); }
	    if ($screenplay) { array_push ($tagextras, "sc=$screenplay"); $similar_words_typed .= ", " . get_uc('Screenplay','') .'=' .  get_uc($screenplay,''); }
	}
	else {

	    if ($musician) { array_push ($tagextras, "m=$musician"); $similar_words_typed .= ", Musician = $musician "; }
	    if ($lyricist) { array_push ($tagextras, "l=$lyricist"); $similar_words_typed .= ", Lyricist = $lyricist "; }
	    if ($singers) { array_push ($tagextras, "s=$singers"); $similar_words_typed .= ", Singers = $singers "; }
	    if ($raga) { array_push ($tagextras, "r=$raga"); $similar_words_typed .= ", Raga = $raga "; }
	    if ($genre) { array_push ($tagextras, "g=$genre"); $similar_words_typed .= ", Genre = $genre "; }
	    if ($director) { array_push ($tagextras, "d=$director"); $similar_words_typed .= ", Director = $director "; }
	    if ($producer) { array_push ($tagextras, "p=$producer"); $similar_words_typed .= ", Producer = $producer "; }
	    if ($story) { array_push ($tagextras, "s=$story"); $similar_words_typed .= ", Story = $story "; }
	    if ($screenplay) { array_push ($tagextras, "sc=$screenplay");$similar_words_typed .= ", Screenplay = $screenplay "; }
	}


	$extraClauses1 = array();
	$extraClauses2 = array();
	$extraClauses5 = array();
	$extraClauses6 = array();
	$extraClauses1U = array();
	$extraClauses2U = array();
	$extraClauses5U = array();
	$extraClauses6U = array();




	if ($singers){
	    array_push ($extraClauses1, addClauseToArray("SONGS.S_SINGERS","$singers"));
	    array_push ($extraClauses1U, addClauseToArray("USONGS.U_SINGERS","$singers"));

	    array_push ($extraClauses5, addClauseToArray("ASONGS.S_SINGERS","$singers"));
	    array_push ($extraClauses5U, addClauseToArray("UASONGS.S_SINGERS","$singers"));
	}
	if ($raga) { 
	    array_push ($extraClauses1, addClauseToArray("SONGS.S_RAGA","$raga"));
	    array_push ($extraClauses1U, addClauseToArray("USONGS.U_RAGA","$raga"));

	    array_push ($extraClauses5, addClauseToArray("ASONGS.S_RAGA","$raga"));
	    array_push ($extraClauses5U, addClauseToArray("UASONGS.S_RAGA","$raga"));

	}
	if ($genre){ 
	    array_push ($extraClauses1, addClauseToArray("SONGS.S_GENRE","$genre"));
	    array_push ($extraClauses1U, addClauseToArray("USONGS.U_GENRE","$genre"));

	    array_push ($extraClauses5, addClauseToArray("ASONGS.S_GENRE","$genre"));
	    array_push ($extraClauses5U, addClauseToArray("UASONGS.S_GENRE","$genre"));


	}
	if ($musician){
	    array_push ($extraClauses1, addClauseToArray("SONGS.S_MUSICIAN","$musician"));
	    array_push ($extraClauses1U, addClauseToArray("USONGS.U_MUSICIAN","$musician"));
	    array_push ($extraClauses2, addClauseToArray("MOVIES.M_MUSICIAN","$musician"));
	    array_push ($extraClauses2U, addClauseToArray("UMOVIES.M_MUSICIAN","$musician"));

	    array_push ($extraClauses5, addClauseToArray("ASONGS.S_MUSICIAN","$musician"));
	    array_push ($extraClauses5U, addClauseToArray("UASONGS.S_MUSICIAN","$musician"));
	    array_push ($extraClauses6, addClauseToArray("ALBUMS.M_MUSICIAN","$musician"));
	    array_push ($extraClauses6U, addClauseToArray("UALBUMS.M_MUSICIAN","$musician"));

	}
	if ($lyricist) {
	    array_push ($extraClauses1, addClauseToArray("SONGS.S_WRITERS","$lyricist"));
	    array_push ($extraClauses1U, addClauseToArray("USONGS.U_WRITERS","$lyricist"));
	    array_push ($extraClauses2, addClauseToArray("MOVIES.M_WRITERS","$lyricist"));
	    array_push ($extraClauses2U, addClauseToArray("UMOVIES.M_WRITERS","$lyricist"));


	    array_push ($extraClauses5, addClauseToArray("SONGS.S_WRITERS","$lyricist"));
	    array_push ($extraClauses5U, addClauseToArray("USONGS.U_WRITERS","$lyricist"));
	    array_push ($extraClauses6, addClauseToArray("MOVIES.M_WRITERS","$lyricist"));
	    array_push ($extraClauses6U, addClauseToArray("UMOVIES.M_WRITERS","$lyricist"));
	}

	if ($screenplay) {
	    array_push ($extraClauses2, addClauseToArray("MOVIES.M_SCREENPLAY","$screenplay"));
	    array_push ($extraClauses2U, addClauseToArray("UMOVIES.M_SCREENPLAY","$screenplay"));
	}

	if ($story) {
	    array_push ($extraClauses2, addClauseToArray("MOVIES.M_STORY","$story"));
	    array_push ($extraClauses2U, addClauseToArray("UMOVIES.M_STORY","$story"));
	}

	if ($producer) {
	    array_push ($extraClauses2, addClauseToArray("MOVIES.M_PRODUCER","$producer"));
	    array_push ($extraClauses2U, addClauseToArray("UMOVIES.M_PRODUCER","$producer"));
	}
	if ($director){
	    array_push ($extraClauses2, addClauseToArray("MOVIES.M_DIRECTOR","$director"));
	    array_push ($extraClauses2U, addClauseToArray("UMOVIES.M_DIRECTOR","$director"));
	}





	echo "<div style=\"font-size:12apt;font-family:Lucida Sans;font-weight:bold;text-align:center;\">\n";
	

	

	if ($similar != ''){
                $los = strlen($similar);
//		$similar2 = str_replace('r','l',$similar);
		$similar2 = str_replace('a','%a',$similar);
		$similar2 = str_replace('e','%e',$similar2);
//		$similar2 = str_replace('i','%',$similar2);
//		$similar2 = str_replace('y','%',$similar2);
		$similar3 = str_replace(' ','',$similar);
		$similar3 = str_replace('a','%a',$similar3);
// This is the new regexp one
                $similar4 = getRegexpName($similar);
//
		$los2 = strlen($similar2);
                $los3 = strlen($similar3);
                
		$query1 = "SELECT S_ID,M_ID,S_SONG,S_MOVIE,S_YEAR,S_WRITERS,S_MUSICIAN FROM SONGS,USONGS WHERE "; 
		
		if ($_GET['search_type'] == 1) { 
		    array_push ($whereClauses1," SONGS.S_ID=USONGS.U_ID and ( (SONGS.S_SONG regexp \"$similar4\") OR (SONGS.S_SONG like \"$similar2%\") OR (USONGS.U_SONG like \"$similar2%\") OR (SOUNDEX(SONGS.S_SONG)=SOUNDEX(\"$similar\") or SOUNDEX(USONGS.U_SONG)=SOUNDEX(\"$similar\") or SOUNDEX(SUBSTR(SONGS.S_SONG,1,$los)) = SOUNDEX(\"$similar\")) OR (SOUNDEX(SONGS.S_SONG)=SOUNDEX(\"$similar2\") or SOUNDEX(USONGS.U_SONG)=SOUNDEX(\"$similar2\") or SOUNDEX(SUBSTR(SONGS.S_SONG,1,$los2)) = SOUNDEX(\"$similar2\")   or SOUNDEX(SUBSTR(SONGS.S_SONG,1,$los3)) = SOUNDEX(\"$similar3\") ))");
		}
		else {
		    array_push ($whereClauses1," SONGS.S_ID=USONGS.U_ID and ( (SONGS.S_SONG regexp \"$similar4\") OR (SONGS.S_SONG like \"$similar2%\") OR (USONGS.U_SONG like \"$similar2%\")) ");
		}


		    $query2 = "SELECT MOVIES.M_ID,MOVIES.M_MOVIE,MOVIES.M_YEAR,MOVIES.M_WRITERS,MOVIES.M_MUSICIAN,MOVIES.M_DIRECTOR FROM MOVIES,UMOVIES WHERE "; 
		    array_push ($whereClauses2," MOVIES.M_ID=UMOVIES.M_ID and ( (MOVIES.M_MOVIE regexp \"$similar4\") or (SOUNDEX(MOVIES.M_MOVIE)=SOUNDEX(\"$similar\") or SOUNDEX(UMOVIES.M_MOVIE)=SOUNDEX(\"$similar\") or SOUNDEX(SUBSTR(MOVIES.M_MOVIE,1,$los)) = SOUNDEX(\"$similar\")) OR (SOUNDEX(MOVIES.M_MOVIE)=SOUNDEX(\"$similar2\") or SOUNDEX(UMOVIES.M_MOVIE)=SOUNDEX(\"$similar2\") or SOUNDEX(SUBSTR(MOVIES.M_MOVIE,1,$los2)) = SOUNDEX(\"$similar2\")  or SOUNDEX(SUBSTR(MOVIES.M_MOVIE,1,$los3)) = SOUNDEX(\"$similar3\")))");


		$query3 = "SELECT MDETAILS.M_ID,MDETAILS.M_PRODUCER,MOVIES.M_YEAR FROM MOVIES,MDETAILS,UDETAILS WHERE "; 
		array_push ($whereClauses3," MDETAILS.M_ID=UDETAILS.M_ID and MDETAILS.M_ID=MOVIES.M_ID and (MDETAILS.M_CAST like \"%$similar%\"  or MDETAILS.M_CAST like \"%$similar2%\" or UDETAILS.M_CAST like \"%$similar%\"  or UDETAILS.M_CAST like \"%$similar2%\")");

		$query5 = "SELECT ASONGS.S_ID,ASONGS.M_ID,ASONGS.S_SONG,ASONGS.S_MOVIE,ASONGS.S_YEAR,ASONGS.S_WRITERS,ASONGS.S_MUSICIAN FROM ASONGS,UASONGS WHERE "; 
		
		array_push ($whereClauses5," ASONGS.S_ID=UASONGS.S_ID and ( (ASONGS.S_SONG regexp \"$similar4\") OR (SOUNDEX(ASONGS.S_SONG)=SOUNDEX(\"$similar\") or SOUNDEX(UASONGS.S_SONG)=SOUNDEX(\"$similar\") or SOUNDEX(SUBSTR(ASONGS.S_SONG,1,$los)) = SOUNDEX(\"$similar\")) OR (SOUNDEX(ASONGS.S_SONG)=SOUNDEX(\"$similar2\") or SOUNDEX(UASONGS.S_SONG)=SOUNDEX(\"$similar2\") or SOUNDEX(SUBSTR(ASONGS.S_SONG,1,$los2)) = SOUNDEX(\"$similar2\")  or SOUNDEX(SUBSTR(ASONGS.S_SONG,1,$los3)) = SOUNDEX(\"$similar3\")))");
		
		$query6 = "SELECT ALBUMS.M_ID,ALBUMS.M_MOVIE,ALBUMS.M_YEAR,ALBUMS.M_DIRECTOR,ALBUMS.M_WRITERS,ALBUMS.M_MUSICIAN FROM ALBUMS,UALBUMS WHERE "; 
		array_push ($whereClauses6," ALBUMS.M_ID=UALBUMS.M_ID and ( (ALBUMS.M_MOVIE regexp \"$similar4\") OR (SOUNDEX(ALBUMS.M_MOVIE)=SOUNDEX(\"$similar\") or SOUNDEX(UALBUMS.M_MOVIE)=SOUNDEX(\"$similar\") or SOUNDEX(SUBSTR(ALBUMS.M_MOVIE,1,$los)) = SOUNDEX(\"$similar\")) OR (SOUNDEX(ALBUMS.M_MOVIE)=SOUNDEX(\"$similar2\") or SOUNDEX(UALBUMS.M_MOVIE)=SOUNDEX(\"$similar2\") or SOUNDEX(SUBSTR(ALBUMS.M_MOVIE,1,$los2)) = SOUNDEX(\"$similar2\") or SOUNDEX(SUBSTR(ALBUMS.M_MOVIE,1,$los3)) = SOUNDEX(\"$similar3\")))");
		
		
	}
	

	if ($extraClauses1[0] != ""){
	    $query1 .=  "( " . implode (' AND ',$whereClauses1) . ") AND ( (" . implode (' AND ',$extraClauses1) . " OR  ( " . implode ( ' AND ' , $extraClauses1U) . "))) order by S_YEAR limit 0,50";
	}
	else {
	    $query1 .=  implode (' AND ',$whereClauses1) . " order by S_YEAR limit 0,50";
	}
	if ($extraClauses2[0] != ""){
	    $query2 .=  "( " . implode (' AND ',$whereClauses2) . ") AND ( (" . implode (' AND ',$extraClauses2) . " OR  ( " . implode ( ' AND ' , $extraClauses2U) . "))) order by M_YEAR limit 0,50";
	}
	else {
	    $query2 .=  implode (' AND ',$whereClauses2) . " order by M_YEAR limit 0,50";
	}


	$query3 .=  implode (' AND ',$whereClauses3) . " order by MOVIES.M_YEAR limit 0,10";
	$query4 .=  implode (' AND ',$whereClauses4) . " order by SONGS.S_YEAR limit 0,10";

	
	if ($extraClauses6[0] != ""){
	    $query6 .=  "( " . implode (' AND ',$whereClauses6) . ") AND ( (" . implode (' AND ',$extraClauses6) . " OR  ( " . implode ( ' AND ' , $extraClauses6U) . "))) order by S_YEAR limit 0,50";
	}
	else 
	    $query6 .=  implode (' AND ',$whereClauses6) . " order by ALBUMS.M_YEAR limit 0,30";
        }

    if ($extraClauses5[0] != ""){
	$query5 .=  "( " . implode (' AND ',$whereClauses5) . ") AND ( (" . implode (' AND ',$extraClauses5) . " OR  ( " . implode ( ' AND ' , $extraClauses5U) . "))) order by M_YEAR limit 0,50";
    }
    else {
        $query5 .=  implode (' AND ',$whereClauses5) . " order by ASONGS.S_YEAR limit 0,30";
    }


    echo " </div>\n";

     $search_detailed_msg = 'Click Here For Detailed and Precise Search';
     $search_words = 'Words You Typed';
     if ($_GET['lang'] != 'E') { $search_detailed_msg = get_uc($search_detailed_msg,''); $search_words= get_uc($search_words,''); }
     
     if ($db == "movies"){
     echo "<table width=100%><tr><td valign=top>";
     
     	
     if ($_GET['show_sql'] == 1){
	 echo $query2, "<BR>";
     }
    $result        = mysql_query($query2);
    $num_results   = mysql_num_rows($result);
    $tot_results += $num_results;

    $totsongs = 'Total Movies Matched';
	if ($_GET['lang'] != 'E') { $totsongs = get_uc($totsongs,''); }
    if ($tot_results > 0) { 
	echo "<div class=pbiggersubtitle>$totsongs : $tot_results</div>";
    }
//    else {
	$back  = "Go Back To The Search Filter Page";
	if ($_GET['lang'] != 'E') { $back = get_uc($back,''); }
	$qs = $_SERVER['QUERY_STRING'];
	$qsorig = $qs;
	if (strpos($qs,"db=movies") !== false){
	    $qsorig = str_replace("q=","movie=",$qsorig);
	    $sorry = "You just searched Movies Database. You can search the Non-Movies database for the same criteria you used by clicking here";
	    if ($_GET['lang'] != 'E') { $sorry = get_uc($sorry,''); }
	    $qs = str_replace("db=movies","db=albums",$qs);
	    echo "<div class=pbiggersubtitle><a href=\"$searchScript?${qsorig}\">$back</a><BR><a href=\"$_Master_albumlist_script?${qs}\">$sorry</a></div>";
	}
	else if (strpos($qs,"db=albums") !== false){
	    $qsorig = str_replace("q=","album=",$qsorig);
	    $sorry = "You just searched Albums Database. You can search the Movies database for the same criteria you used by clicking here";
	    if ($_GET['lang'] != 'E') { $sorry = get_uc($sorry,''); }

	    $qs = str_replace("db=albums","db=movies",$qs);
	    echo "<div class=pbiggersubtitle><a href=\"$searchScript?${qsorig}\">$back</a><BR><a href=\"$_Master_movielist_script?${qs}\">$sorry</a></div>";
	}
	else if (strpos($qs,"db=albumsongs") !== false){
	    $qsorig = str_replace("q=","songname=",$qsorig);
	    $sorry = "You just searched Albums Database. You can search the Movies database for the same criteria you used by clicking here";
	    if ($_GET['lang'] != 'E') { $sorry = get_uc($sorry,''); }

	    $qs = str_replace("db=albumsongs","db=moviesongs",$qs);
	    echo "<div class=pbiggersubtitle><a href=\"$searchScript?${qsorig}\">$back</a><BR><a href=\"$_Master_songlist_script?${qs}\">$sorry</a></div>";
	}
	else if (strpos($qs,"db=moviesongs") !== false){
	    $qsorig = str_replace("q=","songname=",$qsorig);
	    $sorry = "You just searched Movies Database. You can search the Non-Movies database for the same criteria you used by clicking here";
	    if ($_GET['lang'] != 'E') { $sorry = get_uc($sorry,''); }
	    $qs = str_replace("db=moviesongs","db=albumsongs",$qs);
	    echo "<div class=pbiggersubtitle><a href=\"$searchScript?${qsorig}\">$back</a><BR><a href=\"$_Master_albumsonglist_script?${qs}\">$sorry</a></div>";
	}
//    }   

    $i=0;
    printLongHeaders ('icons/Movie.png','Movies');
    if ($num_results == 0){
	printContents("Writeups/MissingMovieSearch${lang}.html");
    }
   else {
        //printLongHeaders ('Similar Sounding Movies',"$similar");

	echo "<table class=ptables>\n";

	echo "<tr class=tableheader>\n";

	printDetailCellHeads ('Movie');
	printDetailCellHeads ('Year');
	printDetailCellHeads ('Musician');
	printDetailCellHeads ('Lyricist');
	printDetailCellHeads ('Director');
	echo "</tr>\n";
	while ($i < $num_results){
	    $mid = mysql_result($result, $i, "M_ID");
	    $movien = mysql_result($result, $i, "MOVIES.M_MOVIE");
	    echo "<tr class=ptableslist>\n";
	    printDetailCells (mysql_result($result, $i, "MOVIES.M_MOVIE"),"$movieScript?$mid",$i);
	    printDetailCells (mysql_result($result, $i, "MOVIES.M_YEAR"),'',$i);
	    printDetailCells (mysql_result($result, $i, "MOVIES.M_MUSICIAN"),'',$i);
	    printDetailCells (mysql_result($result, $i, "MOVIES.M_WRITERS"),'',$i);
	    printDetailCells (mysql_result($result, $i, "MOVIES.M_DIRECTOR"),'',$i);
	    echo "</tr>";
	    $i++;
	}

	echo "</table>\n";
        echo "<P><div class=psubtitleleft><a href=\"$searchScript?db=\">$search_detailed_msg </a>....</div>";
    }
     echo "</td></tr></table>";
}
else      if ($db == "moviesongs"){
     echo "<table width=100%><tr><td valign=top>";
    if ($_GET['show_sql'] == 1){
       echo $query1, "<BR>";
    }
    $result        = mysql_query($query1);
    $num_results   = mysql_num_rows($result);
    $tot_results += $num_results;

    $totsongs = 'Total Songs Matched';
	if ($_GET['lang'] != 'E') { $totsongs = get_uc($totsongs,''); }
    if ($tot_results > 0) { 
	echo "<div class=pbiggersubtitle>$totsongs : $tot_results <br>$search_words  : $similar_words_typed</div>";
    }
    $i=0;
    printLongHeaders ('icons/Music.png','Songs');
    if ($num_results == 0){
//	printContents("Writeups/MissingSongs.html");
	printContents("Writeups/MissingSongSearch${lang}.html");
    }
    else {
        //printLongHeaders ('Similar Sounding Songs',"$similar");

	$i=0;

	echo "<table class=ptables>\n";

	echo "<tr class=tableheader>\n";
	printDetailCellHeads ('Song');
	printDetailCellHeads ('Movie');
	printDetailCellHeads ('Year');
	printDetailCellHeads ('Musician');
	printDetailCellHeads ('Lyricist');
	echo "</tr>\n";
	while ($i < $num_results){
	    echo "<tr class=ptableslist>\n";
	    $sid = mysql_result($result, $i, "S_ID");
	    $mid = mysql_result($result, $i, "M_ID");
	    printDetailCells (mysql_result($result, $i, "S_SONG"),"$songScript?$sid",$i);
	    printDetailCells (mysql_result($result, $i, "S_MOVIE"),"$movieScript?$mid",$i);
	    printDetailCells (mysql_result($result, $i, "S_YEAR"),'',$i);
	    printDetailCells (mysql_result($result, $i, "S_MUSICIAN"),'',$i);
	    printDetailCells (mysql_result($result, $i, "S_WRITERS"),'',$i);
	    echo "</tr>";
	    $i++;
	}

	echo "</table>\n";
        echo "<P><div class=psubtitleleft><a href=\"$searchScript?db=\">$search_detailed_msg </a>....</div>";

    }
   echo "</td></tr></table>";
}
	

    else if ($db == "albums"){
     echo "<table width=100%><tr><td valign=top>";


		if ($_GET['show_sql'] == 1){
       echo $query6, "<BR>";
    }
    $result        = mysql_query($query6);
    $num_results   = mysql_num_rows($result);
    $tot_results += $num_results;

    $totsongs = 'Total Albums Matched';
	if ($_GET['lang'] != 'E') { $totsongs = get_uc($totsongs,''); }
    if ($tot_results > 0) { 
	echo "<div class=pbiggersubtitle>$totsongs : $tot_results <br>$search_words  : $similar_words_typed</div>";
    }

    $i=0;
    printLongHeaders ('icons/Movie.png','Albums');
    if ($num_results == 0){
//	printContents("Writeups/MissingAlbums.html");
	printContents("Writeups/MissingAlbumSearch${lang}.html");
    }
   else {
        //printLongHeaders ('Similar Sounding Movies',"$similar");

	echo "<table class=ptables>\n";

	echo "<tr class=tableheader>\n";

	printDetailCellHeads ('Album');
	printDetailCellHeads ('Year');
	printDetailCellHeads ('Musician');
	printDetailCellHeads ('Lyricist');
	printDetailCellHeads ('Label');
	echo "</tr>\n";
	while ($i < $num_results){
	    echo "<tr class=ptableslist>\n";

	    $mid = mysql_result($result, $i, "M_ID");

	    printDetailCells (mysql_result($result, $i, "ALBUMS.M_MOVIE"),"$albumScript?$mid",$i);
	    printDetailCells (mysql_result($result, $i, "ALBUMS.M_YEAR"),'',$i);
	    printDetailCells (mysql_result($result, $i, "ALBUMS.M_MUSICIAN"),'',$i);
	    printDetailCells (mysql_result($result, $i, "ALBUMS.M_WRITERS"),'',$i);
	    printDetailCells (mysql_result($result, $i, "ALBUMS.M_DIRECTOR"),'',$i);
	    echo "</tr>";
	    $i++;
	}

	echo "</table>\n";
        echo "<P><div class=psubtitleleft><a href=\"$searchScript?db=albums\">$search_detailed_msg</a> ....</div>";
    }
    echo "</td></tr></table>\n";
}


	

   else  if ($db == "albumsongs"){
     echo "<table width=100%><tr><td valign=top>";

	    if ($_GET['show_sql'] == 1){
       echo $query5, "<BR>";
    }
    $result        = mysql_query($query5);
    $num_results   = mysql_num_rows($result);
    $tot_results += $num_results;

    $totsongs = 'Total Songs Matched';
	if ($_GET['lang'] != 'E') { $totsongs = get_uc($totsongs,''); }
    if ($tot_results > 0) { 
	echo "<div class=pbiggersubtitle>$totsongs : $tot_results <br>$search_words  : $similar_words_typed</div>";
    }

    $i=0;
        printLongHeaders ('icons/Music.png','Album Songs');
    if ($num_results == 0){
//	printContents("Writeups/MissingAlbumSongs.html");
	printContents("Writeups/MissingAlbumSongSearch${lang}.html");
    }
    else {
        //printLongHeaders ('Similar Sounding Songs',"$similar");

	$i=0;

	echo "<table class=ptables>\n";

	echo "<tr class=tableheader>\n";
	printDetailCellHeads ('Song');
	printDetailCellHeads ('Album');
	printDetailCellHeads ('Year');
	printDetailCellHeads ('Musician');
	printDetailCellHeads ('Lyricist');
	echo "</tr>\n";
	while ($i < $num_results){
	    echo "<tr class=ptableslist>\n";
	    $sid = mysql_result($result, $i, "S_ID");
	    $mid = mysql_result($result, $i, "M_ID");
	    printDetailCells (mysql_result($result, $i, "S_SONG"),"$albumsongScript?$sid",$i);
	    printDetailCells (mysql_result($result, $i, "S_MOVIE"),"$albumScript?$mid",$i);
	    printDetailCells (mysql_result($result, $i, "S_YEAR"),'',$i);
	    printDetailCells (mysql_result($result, $i, "S_MUSICIAN"),'',$i);
	    printDetailCells (mysql_result($result, $i, "S_WRITERS"),'',$i);
	    echo "</tr>";
	    $i++;
	}

	echo "</table>\n";
        echo "<P><div class=psubtitleleft><a href=\"$searchScript?db=albumsongs\">$search_detailed_msg </a>....</div>";

    }



    echo "</td></tr></table>\n";

}


	
?>
    


<?php

   
/*
    if ($tot_results == 0) { 
	if ($_GET['lang'] != 'E'){
	    printContents("Writeups/listSimilar_nonefound_malayalam.txt");
	}
	else {
	    printContents("Writeups/listSimilar_nonefound.txt");
	}
    }
*/
    printFancyFooters();
    mysql_close($cLink);

}
function printLongHeaders($icon,$key,$val)
{
   if ($_GET['lang'] != 'E') { $key = get_uc($key,''); }
   if ($icon != ''){
   echo "<div style=\"background-color:#CD5555;color:#ffffff;font-weight:bold;font-size:12pt;height:25px;\"><img src=\"$icon\" border=0 style=\"height:15px;padding-top:5px\" > $key</div>";
   }
  else {
   echo "<div style=\"background-color:#CD5555;color:#ffffff;font-weight:bold;font-size:12pt;\"> $key</div>";
  }
}
?>
