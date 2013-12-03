<?php session_start();
{
    error_reporting (E_ERROR);
    require_once("includes/utils.php");
    require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("_includes/_searchUtils.php");


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
	    if ($director) { array_push ($tagextras, "d=$director"); $similar_words_typed .= ", " . get_uc('Director','') .'=' .  get_uc($director,''); }
	    if ($producer) { array_push ($tagextras, "p=$producer"); $similar_words_typed .= ", " . get_uc('Producer','') .'=' .  get_uc($producer,''); }
	    if ($musician) { array_push ($tagextras, "m=$musician"); $similar_words_typed .= ", " . get_uc('Musician','') .'=' .  get_uc($musician,''); }
	    if ($lyricist) { array_push ($tagextras, "l=$lyricist"); $similar_words_typed .= ", " . get_uc('Lyricist','') .'=' .  get_uc($lyricist,''); }
	    if ($singers) { array_push ($tagextras, "s=$singers"); $similar_words_typed .= ", " . get_uc('Singers','') .'=' .  get_uc($singers,''); }
	    if ($raga) { array_push ($tagextras, "r=$raga"); $similar_words_typed .= ", " . get_uc('Raga','') .'=' . " " . get_uc($raga,''); }
	    if ($genre) { array_push ($tagextras, "g=$genre"); $similar_words_typed .= ", " . get_uc('Genre','') .'=' .  get_uc($genre,''); }
	    if ($story) { array_push ($tagextras, "s=$story"); $similar_words_typed .= ", " . get_uc('Story','') .'=' . " " . get_uc($story,''); }
	    if ($screenplay) { array_push ($tagextras, "sc=$screenplay"); $similar_words_typed .= ", " . get_uc('Screenplay','') .'=' .  get_uc($screenplay,''); }
	}
	else {
	    if ($director) { array_push ($tagextras, "d=$director"); $similar_words_typed .= ", Director = $director "; }
	    if ($producer) { array_push ($tagextras, "p=$producer"); $similar_words_typed .= ", Producer = $producer "; }
	    if ($musician) { array_push ($tagextras, "m=$musician"); $similar_words_typed .= ", Musician = $musician "; }
	    if ($lyricist) { array_push ($tagextras, "l=$lyricist"); $similar_words_typed .= ", Lyricist = $lyricist "; }
	    if ($singers) { array_push ($tagextras, "s=$singers"); $similar_words_typed .= ", Singers = $singers "; }
	    if ($raga) { array_push ($tagextras, "r=$raga"); $similar_words_typed .= ", Raga = $raga "; }
	    if ($genre) { array_push ($tagextras, "g=$genre"); $similar_words_typed .= ", Genre = $genre "; }
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


	if ($producer) {
	    array_push ($extraClauses2, addClauseToArray("MOVIES.M_PRODUCER","$producer"));
	    array_push ($extraClauses2U, addClauseToArray("UMOVIES.M_PRODUCER","$producer"));
	}
	if ($director){
	    array_push ($extraClauses2, addClauseToArray("MOVIES.M_DIRECTOR","$director"));
	    array_push ($extraClauses2U, addClauseToArray("UMOVIES.M_DIRECTOR","$director"));
	}



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





	echo "<div style=\"font-size:12apt;font-family:Lucida Sans;font-weight:bold;text-align:center;\">\n";
	

	

	if ($similar != ''){
                $los = strlen($similar);
//		$similar2 = str_replace('r','l',$similar);
		$similar2 = str_replace('a','%a',$similar);
		$similar2 = str_replace('e','%e',$similar2);
		$similar2 = str_replace(' ','%',$similar2);
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
		$snospace = str_replace(" ","",$similar);
		if ($_GET['search_type'] == 1) { 
		    array_push ($whereClauses1," SONGS.S_ID=USONGS.U_ID and ( (SONGS.S_SONG regexp \"$similar4\") OR (SONGS.S_SONG like \"$similar2%\") OR (USONGS.U_SONG like \"$similar2%\") OR (SOUNDEX(SONGS.S_SONG)=SOUNDEX(\"$similar\") or SOUNDEX(USONGS.U_SONG)=SOUNDEX(\"$similar\") or SOUNDEX(SUBSTR(SONGS.S_SONG,1,$los)) = SOUNDEX(\"$similar\")) OR (SOUNDEX(SONGS.S_SONG)=SOUNDEX(\"$similar2\") or SOUNDEX(USONGS.U_SONG)=SOUNDEX(\"$similar2\") or SOUNDEX(SUBSTR(SONGS.S_SONG,1,$los2)) = SOUNDEX(\"$similar2\")   or SOUNDEX(SUBSTR(SONGS.S_SONG,1,$los3)) = SOUNDEX(\"$similar3\") ))");
		}
		else {
		    $precises = getPreciseMovieSongName($similar,"moviesongs");
		    if ($precises){
			array_push ($whereClauses1," SONGS.M_ID=USONGS.U_ID and ( SONGS.S_SONG like \"$precises\" or USONGS.U_SONG like \"$precises\") ");
		    }
		    else {
			array_push ($whereClauses1," SONGS.S_ID=USONGS.U_ID and ( (SONGS.S_SONG regexp \"$similar4\") OR (SONGS.S_SONG like \"$similar2%\") OR (USONGS.U_SONG like \"$similar2%\") OR (SONGS.S_SONG like \"$snospace\") or (USONGS.U_SONG like \"$snospace\"))  ");
		    }
		}




		$query2 = "SELECT MOVIES.M_ID,MOVIES.M_MOVIE,MOVIES.M_YEAR,MOVIES.M_WRITERS,MOVIES.M_MUSICIAN,MOVIES.M_DIRECTOR FROM MOVIES,UMOVIES WHERE "; 
		$mnospace = str_replace(" ","",$similar);
		if ($_GET['search_type'] == 1){ 
		    array_push ($whereClauses2," MOVIES.M_ID=UMOVIES.M_ID and ( (MOVIES.M_MOVIE regexp \"$similar4\") or (SOUNDEX(MOVIES.M_MOVIE)=SOUNDEX(\"$similar\") or SOUNDEX(UMOVIES.M_MOVIE)=SOUNDEX(\"$similar\") or SOUNDEX(SUBSTR(MOVIES.M_MOVIE,1,$los)) = SOUNDEX(\"$similar\")) OR (SOUNDEX(MOVIES.M_MOVIE)=SOUNDEX(\"$similar2\") or SOUNDEX(UMOVIES.M_MOVIE)=SOUNDEX(\"$similar2\") or SOUNDEX(SUBSTR(MOVIES.M_MOVIE,1,$los2)) = SOUNDEX(\"$similar2\")  or SOUNDEX(SUBSTR(MOVIES.M_MOVIE,1,$los3)) = SOUNDEX(\"$similar3\")))");
		}
		else {
		    $precisem = getPreciseMovieSongName($similar,"movies");
		    if ($precisem){
			array_push ($whereClauses2," MOVIES.M_ID=UMOVIES.M_ID and ( MOVIES.M_MOVIE like \"$precisem\" or UMOVIES.M_MOVIE like \"$precisem\") ");
		    }
		    else {
			array_push ($whereClauses2," MOVIES.M_ID=UMOVIES.M_ID and ( (MOVIES.M_MOVIE regexp \"$similar4\") OR (MOVIES.M_MOVIE like \"$similar2%\") OR (MOVIES.M_MOVIE like \"$similar2%\") OR (MOVIES.M_MOVIE like \"$mnospace\") OR (UMOVIES.M_MOVIE like \"$mnospace\")) ");
		    }
		}


		$query3 = "SELECT MDETAILS.M_ID,MDETAILS.M_PRODUCER,MOVIES.M_YEAR FROM MOVIES,MDETAILS,UDETAILS WHERE "; 
		array_push ($whereClauses3," MDETAILS.M_ID=UDETAILS.M_ID and MDETAILS.M_ID=MOVIES.M_ID and (MDETAILS.M_CAST like \"%$similar%\"  or MDETAILS.M_CAST like \"%$similar2%\" or UDETAILS.M_CAST like \"%$similar%\"  or UDETAILS.M_CAST like \"%$similar2%\")");

		$query5 = "SELECT ASONGS.S_ID,ASONGS.M_ID,ASONGS.S_SONG,ASONGS.S_MOVIE,ASONGS.S_YEAR,ASONGS.S_WRITERS,ASONGS.S_MUSICIAN FROM ASONGS,UASONGS WHERE "; 

		if ($_GET['search_type'] == 1) { 
		    array_push ($whereClauses5," ASONGS.S_ID=UASONGS.S_ID and ( (ASONGS.S_SONG regexp \"$similar4\") OR (SOUNDEX(ASONGS.S_SONG)=SOUNDEX(\"$similar\") or SOUNDEX(UASONGS.S_SONG)=SOUNDEX(\"$similar\") or SOUNDEX(SUBSTR(ASONGS.S_SONG,1,$los)) = SOUNDEX(\"$similar\")) OR (SOUNDEX(ASONGS.S_SONG)=SOUNDEX(\"$similar2\") or SOUNDEX(UASONGS.S_SONG)=SOUNDEX(\"$similar2\") or SOUNDEX(SUBSTR(ASONGS.S_SONG,1,$los2)) = SOUNDEX(\"$similar2\")  or SOUNDEX(SUBSTR(ASONGS.S_SONG,1,$los3)) = SOUNDEX(\"$similar3\")))");
		}
		else {
		    $precises = getPreciseMovieSongName($similar,"albumsongs");
		    if ($precises){
			array_push ($whereClauses5," ASONGS.S_ID=UASONGS.S_ID and ( ASONGS.S_SONG like \"%$precises%\" or UASONGS.S_SONG like \"%$precises%\") ");
		    }
		    else {
			array_push ($whereClauses5," ASONGS.S_ID=UASONGS.S_ID and ( (ASONGS.S_SONG regexp \"$similar4\") OR (ASONGS.S_SONG like \"$similar2%\") OR (UASONGS.S_SONG like \"$similar2%\")) ");
		    }
		}
		$query6 = "SELECT ALBUMS.M_ID,ALBUMS.M_MOVIE,ALBUMS.M_YEAR,ALBUMS.M_DIRECTOR,ALBUMS.M_WRITERS,ALBUMS.M_MUSICIAN FROM ALBUMS,UALBUMS WHERE "; 
		if ($_GET['search_type'] == 1) {
		    array_push ($whereClauses6," ALBUMS.M_ID=UALBUMS.M_ID and ( (ALBUMS.M_MOVIE regexp \"$similar4\") OR (SOUNDEX(ALBUMS.M_MOVIE)=SOUNDEX(\"$similar\") or SOUNDEX(UALBUMS.M_MOVIE)=SOUNDEX(\"$similar\") or SOUNDEX(SUBSTR(ALBUMS.M_MOVIE,1,$los)) = SOUNDEX(\"$similar\")) OR (SOUNDEX(ALBUMS.M_MOVIE)=SOUNDEX(\"$similar2\") or SOUNDEX(UALBUMS.M_MOVIE)=SOUNDEX(\"$similar2\") or SOUNDEX(SUBSTR(ALBUMS.M_MOVIE,1,$los2)) = SOUNDEX(\"$similar2\") or SOUNDEX(SUBSTR(ALBUMS.M_MOVIE,1,$los3)) = SOUNDEX(\"$similar3\")))");
		}
		else {
		    $precisem = getPreciseMovieSongName($similar,"albums");
		    if ($precisem){
			array_push ($whereClauses6," ALBUMS.M_ID=UALBUMS.M_ID and ( ALBUMS.M_MOVIE like \"%$precisem%\" or UALBUMS.M_MOVIE like \"%$precisem%\") ");
		    }
		    else {
			array_push ($whereClauses6," ALBUMS.M_ID=UALBUMS.M_ID and ( (ALBUMS.M_MOVIE regexp \"$similar4\") OR (ALBUMS.M_MOVIE like \"$similar2%\") OR (ALBUMS.M_MOVIE like \"$similar2%\")) ");
		    }
		}
		
		
	}
	

	if ($extraClauses1[0] != ""){
	    $query1 .=  "( " . implode (' AND ',$whereClauses1) . ") AND ( (" . implode (' AND ',$extraClauses1) . " OR  ( " . implode ( ' AND ' , $extraClauses1U) . "))) order by S_YEAR limit 0,50";
	}
	else {
	    $query1 .=  implode (' AND ',$whereClauses1) . " order by S_YEAR limit 0,10";
	}
	if ($extraClauses2[0] != ""){
	    $query2 .=  "( " . implode (' AND ',$whereClauses2) . ") AND ( (" . implode (' AND ',$extraClauses2) . " OR  ( " . implode ( ' AND ' , $extraClauses2U) . "))) order by M_YEAR limit 0,10";
	}
	else {
	    $query2 .=  implode (' AND ',$whereClauses2) . " order by M_YEAR limit 0,10";
	}


	$query3 .=  implode (' AND ',$whereClauses3) . " order by MOVIES.M_YEAR limit 0,10";
	$query4 .=  implode (' AND ',$whereClauses4) . " order by SONGS.S_YEAR limit 0,10";

	
	if ($extraClauses6[0] != ""){
	    $query6 .=  "( " . implode (' AND ',$whereClauses6) . ") AND ( (" . implode (' AND ',$extraClauses6) . " OR  ( " . implode ( ' AND ' , $extraClauses6U) . "))) order by S_YEAR limit 0,10";
	}
	else 
	    $query6 .=  implode (' AND ',$whereClauses6) . " order by ALBUMS.M_YEAR limit 0,10";
        }

    if ($extraClauses5[0] != ""){
	$query5 .=  "( " . implode (' AND ',$whereClauses5) . ") AND ( (" . implode (' AND ',$extraClauses5) . " OR  ( " . implode ( ' AND ' , $extraClauses5U) . "))) order by M_YEAR limit 0,10";
    }
    else {
        $query5 .=  implode (' AND ',$whereClauses5) . " order by ASONGS.S_YEAR limit 0,10";
    }


    echo " </div>\n";

     $search_detailed_msg = 'Click Here For Detailed and Precise Search';
     $search_words = 'Words You Typed';
     if ($_GET['lang'] != 'E') { $search_detailed_msg = get_uc($search_detailed_msg,''); $search_words= get_uc($search_words,''); }

    $directorname  = correctProfileNames($picroot,"Directors",$similar);     	
//    if (!$directorname){     $directorname    = correctPopularProfileNames($picroot,"Directors",$similar);    }
    if (!$directorname){ $directorname  = getAlternateProfiles($picroot,"Directors",$similar);      }

     $producername  = correctProfileNames($picroot,"Producers",$similar);     	
//    if (!$producername){     $producername    = correctPopularProfileNames($picroot,"Producers",$similar);    }
    if (!$producername){	$producername  = getAlternateProfiles($picroot,"Producers",$similar);     	    }

     

    $singername    = correctProfileNames($picroot,"Singers",$similar);     	
//    if (!$singername){     $singername    = correctPopularProfileNames($picroot,"Singer",$similar);    }
     if (!$singername){ $singername = getAlternateProfiles($picroot,"Singers",$similar);}


     $actorname     = correctProfileNames($picroot,"Actors",$similar);     	
//    if (!$actorname){     $actorname    = correctPopularProfileNames($picroot,"Actors",$similar);    }
    if (!$actorname) { $actorname     = getAlternateProfiles($picroot,"Actors",$similar);     	}

     $musicianname  = correctProfileNames($picroot,"Musicians",$similar);     	
//    if (!$musicianname){     $musicianname    = correctPopularProfileNames($picroot,"Musicians",$similar);    }
    if (!$musicianname) { $musicianname  = getAlternateProfiles($picroot,"Musicians",$similar);     	}


     $lyricistname  = correctProfileNames($picroot,"Lyricists",$similar);     	
//    if (!$lyricistname){     $lyricistname    = correctPopularProfileNames($picroot,"Lyricists",$similar);    }
    if (!$lyricistname) { $lyricistname  = getAlternateProfiles($picroot,"Lyricists",$similar);     	}


    $storyname  = correctProfileNames($picroot,"Screenplay",$similar);     	
//    if (!$storyname){     $storyname    = correctPopularProfileNames($picroot,"Screenplay",$similar);    }
    if (!$storyname) {      $storyname  = getAlternateProfiles($picroot,"Screenplay",$similar);     	}



    $screenname  = correctProfileNames($picroot,"Screenplay",$similar);     	
//    if (!$screenname){     $screenname    = correctPopularProfileNames($picroot,"Screen",$similar);    }
    if (!$screenname) {      $screenname  = getAlternateProfiles($picroot,"Screenplay",$similar); 	    }

    $dialogname  = correctProfileNames($picroot,"Screenplay",$similar);     	
//    if (!$dialogname){     $dialogname    = correctPopularProfileNames($picroot,"Screenplay",$similar);    }
    if (!$dialogname) {	$dialogname  = getAlternateProfiles($picroot,"Screenplay",$similar);   }



     $artname  = correctProfileNames($picroot,"Art",$similar);     	
//    if (!$artname){     $artname  = correctPopularProfileNames($picroot,"Art",$similar);     	    }
    if (!$artname){     $artname  = getAlternateProfiles($picroot,"Art",$similar);     	    }


     $editorname  = correctProfileNames($picroot,"Editors",$similar);     
//    if (!$editorname){     $editorname  = correctPopularProfileNames($picroot,"Editors",$similar);         }
    if (!$editorname){     $editorname  = getAlternateProfiles($picroot,"Editors",$similar);         }


     $cameraname  = correctProfileNames($picroot,"Camera",$similar);     	
//    if (!$cameraname){     $cameraname  = correctPopularProfileNames($picroot,"Camera",$similar);     	    }
    if (!$cameraname){     $cameraname  = getAlternateProfiles($picroot,"Camera",$similar);     	    }

    $designname  = correctProfileNames($picroot,"Design",$similar);     	
//    if (!$designname) {     $designname  = correctPopularProfileNames($picroot,"Design",$similar);     	    }
    if (!$designname) {     $designname  = getAlternateProfiles($picroot,"Design",$similar);     	    }


     $makeupname  = correctProfileNames($picroot,"Makeup",$similar);     	











    $artvals = array(
	       'Singers'    => $singername,
	       'Actors'     => $actorname,
	       'Musicians'  => $musicianname,
	       'Lyricists'  => $lyricistname,
	       'Directors'  => $directorname,
	       'Producers'  => $producername,
	       'Story'      => $storyname,
	       'Screenplay' => $screenname,
	       'Dialog'     => $dialogname,
	       'Art'        => $artname,
	       'Editors'    => $editorname,
	       'Camera'     => $cameraname,
	       'Design'     => $designname,
	       'Makeup'     => $makeupname,
	);

    $opct = 0;
    $mat = 0;
    foreach ($artvals as $a=>$v){
	$qryx = "SELECT val from SLOOKUP where name=\"$similar\" and (category like \"%$a%\" or category = \"\")";
        $r = runQuery("$qryx",'val');
	if ($_GET['show_details']==1) { echo "$qryx<BR>"; }
	if ($r) {
	    $art = $a;
	    $val = $r;
	    if ($_GET['show_details']==1) { echo "$qryx<BR>$art $val $mat $pct<BR>";}
	    break;
	}
	similar_text($v,$similar,$pct);
	if ($pct > $opct){
	    $art = $a;
	    $val = $v;
	    $mat = $pct;
	    $opct = $pct;
	    if ($_GET['show_details']==1) { echo "$art $val $mat $pct<BR>";}
	}
    }

	
    $artistpics = array();
    if ($directorname && $art == 'Directors') { 
	$artistname = $directorname; 
	$artistpics = addPicture($picroot,"Directors",$artistname,"director");
	$_field = "Directors";
    }
    else if ($producername && $art == 'Producers') { 
	$artistname = $producername; 
	$artistpics = addPicture($picroot,"Producers",$artistname,"producer");
	$_field = "Producers";
    }
    else if ($singername && $art == 'Singers') { 
	$artistname = $singername;
	$artistpics = addPicture($picroot,"Singers",$artistname,"singers");
	$_field = "Singers";
    }
    else if ($actorname && $art == 'Actors') { 
	$artistname = $actorname; 
	$artistpics = addPicture($picroot,"Actors",$artistname,"actors");
	$_field = "Actors";
    }
    else if ($musicianname && $art == 'Musicians') { 
	$artistname = $musicianname; 
	$artistpics = addPicture($picroot,"Musicians",$artistname,"musician");
	$_field = "Composers";
    }
    else if ($lyricistname && $art == 'Lyricists') { 
	$artistname = $lyricistname; 
	$artistpics = addPicture($picroot,"Lyricists",$artistname,"lyricist");
	$_field = "Lyricists";
    }

    else if ($storyname && $art == 'Story'){
	$artistname = $storyname; 
	$artistpics = addPicture($picroot,"Screenplay",$artistname,"story");
	$_field = "Story";
    }
    else if ($screenname && $art == 'Screenplay') { 
	$artistname = $screenname; 
	$artistpics = addPicture($picroot,"Screenplay",$artistname,"screenplay");
	$_field = "Screenplay";
    }
    else if ($dialogname && $art == 'Dialog') { 
	$artistname = $dialogname; 
	$artistpics = addPicture($picroot,"Dialog",$artistname,"dialog");
	$_field = "Dialog";
    }

    else if ($artname && $art == 'Art') { 
	$artistname = $artname; 
	$artistpics = addPicture($picroot,"Art",$artistname,"art");
	$_field = "Art Director";
    }
    else if ($editorname && $art == 'Editors') { 
	$artistname = $editorname; 
	$artistpics = addPicture($picroot,"Editors",$artistname,"editor");
	$_field = "Editor";
    }
    else if ($cameraname && $art == 'Camera') { 
	$artistname = $cameraname; 
	$artistpics = addPicture($picroot,"Camera",$artistname,"camera");
	$_field = "Camera";
    }

    else if ($designname && $art == 'Design') { 
	$artistname = $designname; 
	$artistpics = addPicture($picroot,"Design",$artistname,"design");
	$_field = "Design";
    }

    else if ($makeupname && $art == 'Makeup') { 
	$artistname = $makeupname; 
	$artistpics = addPicture($picroot,"Makeup",$artistname,"makeup");
	$_field = "Makeup";
    }


    if ($_GET['lang'] != 'E'){
	$_artistname = get_uc($artistname,'');
	$_prof       = get_uc('Profile','');
	$_field      = get_uc($_field,'');
    }
    else {
	$_artistname = $artistname;
	$_prof = 'Profile';
    }

     $profileScript = $_Master_profile_script;

    $art_fld = $art;
    if ($_GET['lang'] != 'E') { $art_fld = get_uc($art_fld, ''); }
    if ($artistpics[0] == ''){ 
	$artistpics[0] = "pics/NoPhoto.jpg";
    }
    echo "<div class=pbiggersubtitle>\n";
    if ($directorname && $art == 'Directors') { 

	echo "<a href=\"${profileScript}?category=director&artist=$directorname\"><img src=\"",$artistpics[0],"\" height=75><BR>", $_artistname, " ( $_prof )</a>\n";
	getProfiles(array("$directorname"),2000);
	getArticles(array("$directorname"));
    }
    else if ($producername && $art == 'Producers') { 
	echo "<a href=\"${profileScript}?category=producer&artist=$producername\"><img src=\"",$artistpics[0],"\" height=75><BR>", $_artistname, " ( $_prof )</a>\n";
	getProfiles(array("$producername"),2000);
	getArticles(array("$producername"));
    }
    else if ($singername && $art == 'Singers') { 
	echo "<a href=\"${profileScript}?category=singers&artist=$singername\"><img src=\"", $artistpics[0],"\" height=75><BR>",$_artistname, " ( $_prof ) </a>\n";
	getProfiles(array("$singername"),2000);
	getArticles(array("$singername"));
    }
    else if ($actorname && $art == 'Actors') { 
	echo "<a href=\"${profileScript}?category=actors&artist=$actorname\"><img src=\"",$artistpics[0],"\" height=75><BR>", $_artistname , " ( $_prof )</a>\n";
	getProfiles(array("$actorname"),2000);
	getArticles(array("$actorname"));
    }

    else if ($musicianname && $art == 'Musicians') { 
	echo "<a href=\"${profileScript}?category=musician&artist=$musicianname\"><img src=\"",$artistpics[0],"\" height=75><BR>", $_artistname , " ( $_prof )</a>\n";
	getProfiles(array("$musicianname"),2000);
	getArticles(array("$musicianname"));
    }
    else if ($lyricistname && $art == 'Lyricists') { 
	echo "<a href=\"${profileScript}?category=lyricist&artist=$lyricistname\"><img src=\"",$artistpics[0],"\" height=75><BR>", $_artistname , " ( $_prof )</a>\n";
	getProfiles(array("$lyricistname"),2000);
	getArticles(array("$lyricistname"));
    }
    else if ($storyname && $art == 'Story'){
	echo "<a href=\"${profileScript}?category=story&artist=$storyname\"><img src=\"",$artistpics[0],"\" height=75><BR>", $_artistname, " ( $_prof )</a>\n";
	getProfiles(array("$storyname"),2000);
	getArticles(array("$storyname"));
    }
    else if ($screenname && $art == 'Screenplay') { 

	echo "<a href=\"${profileScript}?category=screenplay&artist=$screenname\"><img src=\"",$artistpics[0],"\" height=75><BR>", $_artistname, " ( $_prof )</a>\n";
	getProfiles(array("$screenname"),2000);
	getArticles(array("$screenname"));
    }
    else if ($dialogname && $art == 'Dialog') { 

	echo "<a href=\"${profileScript}?category=dialog&artist=$dialogname\"><img src=\"",$artistpics[0],"\" height=75><BR>", $_artistname, " ( $_prof )</a>\n";
	getProfiles(array("$dialogname"));
	getArticles(array("$dialogname"));
    }
    else if ($editorname && $art == 'Editors') { 
	echo "<a href=\"${profileScript}?category=editor&artist=$editorname\"><img src=\"",$artistpics[0],"\" height=75><BR>", $_artistname, " ( $_prof )</a>\n";
	getProfiles(array("$editorname"),2000);
	getArticles(array("$editorname"));
    }


    else if ($cameraname && $art == 'Camera') { 
	echo "<a href=\"${profileScript}?category=camera&artist=$cameraname\"><img src=\"",$artistpics[0],"\" height=75><BR>", $_artistname, " ( $_prof )</a>\n";
	getProfiles(array("$cameraname"),2000);
	getArticles(array("$cameraname"));
    }
    else if ($artname && $art == 'Art') { 

	echo "<a href=\"${profileScript}?category=art%20director&artist=$artname\"><img src=\"",$artistpics[0],"\" height=75><BR>", $_artistname, " ( $_prof )</a>\n";
	getProfiles(array("$artname"),2000);
	getArticles(array("$artname"));
    }
    else if ($designname && $art == 'Design') { 
	echo "<a href=\"${profileScript}?category=design&artist=$designname\"><img src=\"",$artistpics[0],"\" height=75><BR>", $_artistname, " ( $_prof )</a>\n";
	getProfiles(array("$designname"),2000);
	getArticles(array("$designname"));
    }
    else if ($makeupname && $art == 'Makeup') { 
	echo "<a href=\"${profileScript}?category=makeup&artist=$makeupname\"><img src=\"",$artistpics[0],"\" height=75><BR>", $_artistname, " ( $_prof )</a>\n";
	getProfiles(array("$makeupname"),2000);
	getArticles(array("$makeupname"));
    }


    echo "</div>";
    echo "<P>";

//    if (!$artistname){

	echo "<div class=ptables>\n";
	$_msgtitle = "Words Searched";
	if ($_GET['lang'] != 'E') { $_msgtitle = get_uc($_msgtitle,''); }

	echo "<div class=ptables>\n";
	echo "$_msgtitle : $similar";
	echo "</div><P>";
	

     if ($_GET['show_sql'] == 1){
	 echo $query2, "<BR>";
     }
    $result        = mysql_query($query2);
    $num_results   = mysql_num_rows($result);
    $tot_results += $num_results;

     if ($_GET['debug2013'] == 1) { echo "$query2: $num_results ***<BR>";}
    $i=0;

	$pop_movies = PopularMoviesAvailable($similar,'Movies');
	$pop_songs  = PopularSongsAvailable($similar,'Movies');
	$pop_albums = PopularMoviesAvailable($similar,'Albums');
	$pop_asongs = PopularSongsAvailable($similar,'Albums');

    if ($num_results == 0 && $pop_movies == 0){
//	printContents("Writeups/MissingMovieSearch${lang}.html");
    }
   else {
        //printLongHeaders ('Similar Sounding Movies',"$similar");
	printLongHeaders ('icons/Movie.png','Movies');


	if (PopularMoviesAvailable($similar,'Movies')){
	    if (printPopularMovies($similar,'Movies')){
		if ($num_results > 0) { 
		    $othsongs = 'Search Results';
		    if ($_GET['lang'] != 'E') { $othsongs = get_uc($othsongs,''); }
		    echo "<div class=pleftsubheading>$othsongs</div>";
		}
	    }
	}

     if ($_GET['debug2013'] == 1) { echo "$num_results ***<BR>";}

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
        echo "<div class=psubtitle><a href=\"$searchScript?db=movies&movie=$similar\">$search_detailed_msg</a> ....</div>";
    }
     echo "</td></tr></table>";

     echo "<table class=ptables><tr><td valign=top>";
    if ($_GET['show_sql'] == 1){
       echo $query1, "<BR>";
    }
    $result        = mysql_query($query1);
    $num_results   = mysql_num_rows($result);
    $tot_results += $num_results;

    $i=0;

    if ($num_results == 0 && $pop_songs == 0){
//	printContents("Writeups/MissingSongs.html");
//	printContents("Writeups/MissingSongSearch${lang}.html");
    }
    else {
        //printLongHeaders ('Similar Sounding Songs',"$similar");
    printLongHeaders ('icons/Music.png','Songs');
	$i=0;


	if (PopularSongsAvailable($similar,'Movies')){
	    if (printPopularSongs($similar,'Movies')){
		if ($num_results > 0) { 
		    $othsongs = 'Search Results';
		    if ($_GET['lang'] != 'E') { $othsongs = get_uc($othsongs,''); }
		    echo "<div class=pleftsubheading>$othsongs</div>";
		}
	    }
	}


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
        echo "<P><div class=psubtitle><a href=\"$searchScript?db=moviesongs&song=$similar\">$search_detailed_msg </a>....</div>";

    }
   echo "</td></tr></table>";

     echo "<table class=ptables><tr><td valign=top>";


     if ($_GET['show_sql'] == 1){
	 echo $query6, "<BR>";
     }
    $result        = mysql_query($query6);
    $num_results   = mysql_num_rows($result);
    $tot_results += $num_results;


    $i=0;

    if ($num_results == 0 && $pop_albums == 0){
//	printContents("Writeups/MissingAlbums.html");
//	printContents("Writeups/MissingAlbumSearch${lang}.html");
    }
   else {
        //printLongHeaders ('Similar Sounding Movies',"$similar");
        printLongHeaders ('icons/Movie.png','Albums');

	if (PopularMoviesAvailable($similar,'Albums')){
	    if (printPopularMovies($similar,'Albums')){
		if ($num_results > 0) { 
		    $othsongs = 'Search Results';
		    if ($_GET['lang'] != 'E') { $othsongs = get_uc($othsongs,''); }
		    echo "<div class=pleftsubheading>$othsongs</div>";
		}
	    }
	}


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
        echo "<P><div class=psubtitle><a href=\"$searchScript?db=albums&album=$similar\">$search_detailed_msg</a> ....</div>";
    }
    echo "</td></tr></table>\n";

     echo "<tabe class=ptables><tr><td valign=top>";

	    if ($_GET['show_sql'] == 1){
       echo $query5, "<BR>";
    }
    $result        = mysql_query($query5);
    $num_results   = mysql_num_rows($result);
    $tot_results += $num_results;


    $i=0;

    if ($num_results == 0 && $pop_asongs == 0){
//	printContents("Writeups/MissingAlbumSongs.html");
//	printContents("Writeups/MissingAlbumSongSearch${lang}.html");
    }
    else {
        //printLongHeaders ('Similar Sounding Songs',"$similar");
        printLongHeaders ('icons/Music.png','Album Songs');
	$i=0;

	if (PopularSongsAvailable($similar,'Albums')){	
	    if (printPopularSongs($similar,'Albums')){		
		if ($num_results > 0) { 
		    $othsongs = 'Search Results';
		    if ($_GET['lang'] != 'E') { $othsongs = get_uc($othsongs,''); }
		    echo "<div class=pleftsubheading>$othsongs</div>";
		}
	    }
	}

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
	    printDetailCellsSmall (mysql_result($result, $i, "S_YEAR"),'',$i);
	    printDetailCells (mysql_result($result, $i, "S_MUSICIAN"),'',$i);
	    printDetailCells (mysql_result($result, $i, "S_WRITERS"),'',$i);
	    echo "</tr>";
	    $i++;
	}

	echo "</table>\n";
        echo "<P><div class=psubtitle><a href=\"$searchScript?db=albumsongs&songname=$similar\">$search_detailed_msg </a>....</div>";

    }



    echo "</td></tr></table>\n";
    echo "</div>";
//    }

	
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
   echo "<div style=\"width:1000px;border-radius:10px 10px 10px 10px; table-layout:fixed;background-color:#CD5555;color:#ffffff;font-weight:bold;font-size:12pt;height:25px;\"><img src=\"$icon\" border=0 style=\"height:15px;padding-top:5px\" > $key</div>";
   }
  else {
   echo "<div style=\"background-color:#CD5555;color:#ffffff;font-weight:bold;font-size:12pt;\"> $key</div>";
  }
}
?>
